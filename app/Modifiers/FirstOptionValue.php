<?php

namespace App\Modifiers;

use Statamic\Modifiers\Modifier;

class FirstOptionValue extends Modifier
{
    public function index($value, $params, $context)
    {
        foreach ($value as $k => $v) {
            if (! empty($k) && $k != 'null') {
                return $v;
            }
        }

        return null;
    }
}
