<?php

namespace App\Utilities;

/**
 * A simple HTML minifier implementation.
 */
class Minifier
{
    /**
     * Determines if the content should be minified.
     *
     * @param  string  $content
     * @return bool
     */
    protected function shouldMinify($content)
    {
        // If any of these conditions are met, the content should not be minified:
        // 1. 'skipmin' string appears in the content.
        // 2. A '<pre>' or '<textarea>' tag is found.
        // 3. An attribute value contains multiple spaces.
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
        // If the content should be minified, proceed with minification.
        if ($this->shouldMinify($content)) {
            // Define the search and replace patterns for minification.
            $replace = [
                "/<\?php/" => '<?php ',         // Add a space after '<?php' to avoid syntax errors.
                "/\n([\S])/" => ' $1',          // Remove newlines, but keep a space before non-whitespace characters.
                "/\r/" => '',                   // Remove carriage returns.
                "/\n/" => '',                   // Remove newlines.
                "/\t/" => ' ',                  // Replace tabs with a single space.
                '/ +/' => ' ',                  // Replace multiple spaces with a single space.
            ];

            // Perform the replacements and return the minified content.
            return preg_replace(array_keys($replace), array_values($replace), $content);
        }

        // If the content should not be minified, return the original content.
        return $content;
    }
}
