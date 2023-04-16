<?php

namespace App\Listeners;

use App\Fields\GlobalFields;
use Statamic\Events\EntryBlueprintFound;

class MetaBlueprintFieldsListener
{
    public function handle(EntryBlueprintFound $event)
    {
        foreach (GlobalFields::all() as $sectionName => $fields) {
            if (in_array($event->blueprint->handle(), $fields['exclude_blueprints'])) {
                continue;
            }

            foreach ($fields['fields'] as $fieldHandle => $fieldConfig) {
                $event->blueprint->ensureField($fieldHandle, $fieldConfig, $sectionName);
            }
        }
    }
}
