<?php

namespace App\Modifiers;

use Statamic\Modifiers\Modifier;

class FirstOption extends Modifier
{
    /**
     * The first_option modifier will iterate each option
     * in a select field and return the first non-empty
     * option "key" value. An empty option value is one
     * that is either empty or is the string 'null'.
     *
     * @return int|string|null
     */
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
