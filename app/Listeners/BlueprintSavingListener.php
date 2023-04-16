<?php

namespace App\Listeners;

use App\Fields\GlobalFields;
use Statamic\Events\BlueprintSaving;

class BlueprintSavingListener
{
    public function handle(BlueprintSaving $event)
    {
        // Remove all globally added fields
        // when saving to prevent them from
        // being saved to the blueprint.
        collect(GlobalFields::getFieldNames())->each(function ($field) use ($event) {
            $event->blueprint->removeField($field);
        });
    }
}
