<?php

namespace App\Listeners;

use App\Fields\GlobalFields;
use Statamic\Events\BlueprintSaving;

class BlueprintSavingListener
{
    public function handle(BlueprintSaving $event)
    {
        // Remove all globally added fields when saving to prevent them from
        // being saved to the blueprint. If we didn't do this, any changes
        // made to the custom fields (such as title/description) in the
        // app/Fields/GlobalFields.php file would be reflected in the
        // Control Panel without updating each collection's blueprint.
        //
        // If you want to add fields to a blueprint, you can simply
        // comment out this code, or remove the listener's registration
        // from the app/Providers/EventServiceProvider.php file.
        collect(GlobalFields::getFieldNames())->each(function ($field) use ($event) {
            $event->blueprint->removeField($field);
        });
    }
}
