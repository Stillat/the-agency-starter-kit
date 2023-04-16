<?php

namespace App\Fields;

/**
 * The GlobalFields class is a simple class that provides custom
 * field information. The following event listeners utilize
 * fields and sections in this file to make them available
 * to all Blueprints within the Control Panel:
 *
 * - App\Listeners\BlueprintSavingListener
 * - App\Listeners\MetaBlueprintFieldsListener
 */
class GlobalFields
{
    public static function all()
    {
        return [
            // The key of each entry in this array will become
            // the section name within the Control Panel.
            __('meta.section_name') => [
                // We can add existing Blueprint handles to the
                // "exclude_blueprints" array to not have our
                // dynamic fields added to the publish forms
                // within the Control Panel.
                'exclude_blueprints' => [
                    'project_request',
                ],
                'fields' => [
                    'meta_keywords' => [
                        'type' => 'text',
                        'display' => __('meta.keywords'),
                        'instructions' => __('meta.keyword_instructions'),
                        'width' => 100,
                    ],
                    'meta_description' => [
                        'type' => 'textarea',
                        'display' => __('meta.description'),
                        'instructions' => __('meta.description_instructions'),
                        'width' => 100,
                    ],
                ],
            ],
        ];
    }

    public static function getFieldNames()
    {
        return collect(self::all())->map(function ($section) {
            return collect($section['fields'])->keys();
        })->flatten()->toArray();
    }
}
