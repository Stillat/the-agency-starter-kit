<?php

namespace App\Modifiers;

use Statamic\Modifiers\Modifier;

class ToList extends Modifier
{
    /**
     * The to_list modifier will iterate each option
     * in a select field and return an array of
     * key/value pairs. The intended use is to
     * pass the select options as an array to
     * a JavaScript component.
     *
     * i.e.,
     *  {{ select_field | to_list | to_json }}
     *
     * @return array
     */
    public function index($value, $params, $context)
    {
        $list = [];

        foreach ($value as $k => $v) {
            if (empty($k) || $k == 'null') {
                continue;
            }

            $list[] = ['value' => $k, 'label' => $v];
        }

        return $list;
    }
}
