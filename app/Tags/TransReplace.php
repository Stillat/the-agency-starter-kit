<?php

namespace App\Tags;

use Statamic\Tags\Tags;

class TransReplace extends Tags
{
    public function wildcard($tag)
    {
        return trans($tag, $this->params->all());
    }
}
