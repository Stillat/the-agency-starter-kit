<?php

namespace App\Tags;

use Illuminate\Support\Str;
use Statamic\Facades\Entry;
use Statamic\Facades\GlobalSet;
use Statamic\Facades\Site;
use Statamic\Tags\Tags;

/**
 * The SiteTitle Tag is used to generate the title
 * that appears in the <title> tag of each page.
 * A custom Tag was used to simplify the logic
 * within the Antlers template itself (some
 * things are just easier in PHP 🙂).
 */
class SiteTitle extends Tags
{
    public function index()
    {
        // Returns the Site's name from the site_settings
        // Global Set. This is used as the default title
        // if we are on the homepage, as well as the
        // final part of the title if we are on a nested
        // page (e.g. "Blog Post / The Buzz / The Agency").
        $siteName = GlobalSet::findByHandle('site_settings')
                        ->inCurrentSite()->get('site_name');

        // If we are on the homepage, just return the site name.
        if ($this->context->get('is_homepage') === true) {
            return $siteName;
        }

        $parts = [
            (string) $this->context->get('title'),
        ];

        // If we have a segment_2 variable, we are on a nested page.
        // Our collection setup doesn't explicitly have any nested
        // pages configured, but we can retrieve the "parent"
        // by looking up the entry based on its URI :)
        if ($this->context->get('segment_2') != null) {
            $entry = Entry::findByUri(
                // Add a leading slash to the segment_1 value
                // if it doesn't already have one.
                Str::start($this->context->get('segment_1'), '/'),

                // Ensure that we find the entry in the current site.
                Site::current()
            );

            if ($entry != null) {
                $parts[] = $entry->get('title');
            }
        }

        $parts[] = $siteName;

        // Return the parts, joined by a slash.
        return implode(' / ', array_filter($parts));
    }
}
