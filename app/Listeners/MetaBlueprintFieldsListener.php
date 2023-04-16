<?php

namespace App\Listeners;

use App\Fields\GlobalFields;
use Statamic\Events\EntryBlueprintFound;

class MetaBlueprintFieldsListener
{
    public function handle(EntryBlueprintFound $event)
    {
        // The EntryBlueprintFound event is fired for every blueprint
        // that is found. We can take advantage of this to add fields
        // to any blueprint that we want using custom PHP.
        //
        // In our case, we are using the GlobalFields class to
        // provide a list of fields that we want to add to all
        // blueprints. We can also exclude blueprints from having
        // these fields added by adding their handle to the
        // 'exclude_blueprints' array.
        foreach (GlobalFields::all() as $sectionName => $fields) {
            // If the blueprint is in the exclude list, skip it.
            if (in_array($event->blueprint->handle(), $fields['exclude_blueprints'])) {
                continue;
            }

            // Add the fields to the blueprint.
            foreach ($fields['fields'] as $fieldHandle => $fieldConfig) {
                $event->blueprint->ensureField($fieldHandle, $fieldConfig, $sectionName);
            }
        }
    }
}
