<?php

namespace App\Tags;

use Statamic\Tags\Tags;
use Statamic\View\State\ResetsState;

/**
 * The Capture Tag can be used to save "capture" a block
 * of content for later use. This is useful for saving
 * content that you want to use in multiple places on
 * a page, but don't want to duplicate/run the same
 * logic multiple times.
 *
 * The Capture Tag is a "wildcard" tag, meaning that
 * it can be used with any method name. The method name
 * is used as the "name" of the capture. This allows
 * you to have multiple Capture Tags on a page, each
 * with a different name.
 *
 * When used as a pair, the Capture Tag will save the
 * content between the opening and closing tags for
 * later use. When used as a single tag, the Capture
 * Tag will return the content that was saved for the
 * given name.
 *
 * @see resources/views/components/nav/_main.antlers.html
 */
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
