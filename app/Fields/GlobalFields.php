<?php

namespace App\Fields;

class GlobalFields
{
    public static function all()
    {
        return [
            __('meta.section_name') => [
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
