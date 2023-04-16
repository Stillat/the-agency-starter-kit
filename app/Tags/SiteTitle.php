<?php

namespace App\Tags;

use Illuminate\Support\Str;
use Statamic\Facades\Entry;
use Statamic\Facades\GlobalSet;
use Statamic\Facades\Site;
use Statamic\Tags\Tags;

class SiteTitle extends Tags
{
    public function index()
    {
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
