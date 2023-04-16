<?php

namespace App\Tags;

use Statamic\Facades\Blueprint;
use Statamic\Tags\Tags;

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
