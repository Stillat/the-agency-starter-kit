<?php

namespace App\Tags;

use Statamic\Tags\Tags;

/**
 * The TransReplace Tag provides a wrapper around
 * Laravel's translate function. This Tag differs
 * from the Core Statamic Tag in that it allows
 * for replacements to be made within the translated
 * string.
 *
 * For more information, see:
 * https://laravel.com/docs/10.x/localization#replacing-parameters-in-translation-strings
 *
 * Additionally, this Starter Kit makes use of "Short Keys" for all
 * custom translations. For more information on Short Keys, see:
 * https://laravel.com/docs/10.x/localization#using-short-keys
 */
class TransReplace extends Tags
{
    public function wildcard($tag)
    {
        return trans($tag, $this->params->all());
    }
}
