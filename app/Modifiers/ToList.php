<?php

namespace App\Modifiers;

use Statamic\Modifiers\Modifier;

class ToList extends Modifier
{
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
