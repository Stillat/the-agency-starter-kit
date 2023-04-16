<?php

namespace App\Tags;

use Statamic\Facades\Blueprint;
use Statamic\Tags\Tags;

/**
 * The GetBlueprint Tag can be used to retrieve field
 * information for the requested blueprint. This Tag
 * is used within this Starter Kit to retrieve
 * the fields for the project_request blueprint.
 *
 * Specifically, we use this Tag to retrieve the
 * select options for the following fields:
 * - project_type
 * - budget_Range
 *
 * @see resources/views/pages/contact.antlers.html
 */
class GetBlueprint extends Tags
{
    public function index()
    {
        $blueprint = Blueprint::find($this->params->get('handle', ''));

        if ($blueprint == null) {
            return [];
        }

        $fields = $blueprint->fields()->all()->map(function ($field) {
            return $field->toPublishArray();
        })->all();

        $alias = $this->params->get('as', 'fields');

        return [
            $alias => $fields,
        ];
    }
}
