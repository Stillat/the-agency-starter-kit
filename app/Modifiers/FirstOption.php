<?php

namespace App\Modifiers;

use Statamic\Modifiers\Modifier;

class FirstOption extends Modifier
{
    public function index($value, $params, $context)
    {
        foreach ($value as $k => $v) {
            if (! empty($k) && $k != 'null') {
                return $k;
            }
        }

        return null;
    }
}
