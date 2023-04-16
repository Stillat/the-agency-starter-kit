<?php

namespace App\Tags;

use Statamic\Tags\Tags;
use Statamic\View\State\ResetsState;

class Capture extends Tags implements ResetsState
{
    protected static $capturedContent = [];

    public static function resetStaticState()
    {
        self::$capturedContent = [];
    }

    protected function reset()
    {
        $name = $this->params->get('name', null);

        if ($name == null) {
            return;
        }

        unset(self::$capturedContent[$name]);
    }

    public function wildcard($method)
    {
        $cacheKey = '_'.md5($method);

        if (! $this->isPair) {
            if (method_exists($this, $method)) {
                return $this->$method();
            }

            if (! array_key_exists($cacheKey, self::$capturedContent)) {
                return '';
            }

            return self::$capturedContent[$cacheKey];
        }

        self::$capturedContent[$cacheKey] = $this->parse();

        return '';
    }
}
