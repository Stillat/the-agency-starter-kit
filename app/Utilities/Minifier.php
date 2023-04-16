<?php

namespace App\Utilities;

class Minifier
{
    protected function shouldMinify($content)
    {
        if (preg_match('/skipmin/', $content)
            || preg_match('/<(pre|textarea)/', $content)
            || preg_match('/value=("|\')(.*)([ ]{2,})(.*)("|\')/', $content)
        ) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Compresses the content.
     *
     * @param  string  $content
     * @return string
     */
    public function minify($content)
    {
        if ($this->shouldMinify($content)) {
            $replace = [
                "/<\?php/" => '<?php ',
                "/\n([\S])/" => ' $1',
                "/\r/" => '',
                "/\n/" => '',
                "/\t/" => ' ',
                '/ +/' => ' ',
            ];

            return preg_replace(array_keys($replace), array_values($replace), $content);
        }

        return $content;
    }
}
