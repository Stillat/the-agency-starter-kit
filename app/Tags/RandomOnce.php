<?php

namespace App\Tags;

use Statamic\Tags\Tags;

class RandomOnce extends Tags
{
    protected static $variables = [];

    public function index()
    {
        self::$variables = $this->params->get('choices');

        return $this->parse();
    }

    public function next()
    {
        if (empty(self::$variables)) {
            return null;
        }

        $randomIndex = array_rand(self::$variables);
        $randomElement = self::$variables[$randomIndex];

        array_splice(self::$variables, $randomIndex, 1);

        return $randomElement;
    }
}
