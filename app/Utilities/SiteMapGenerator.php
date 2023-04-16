<?php

namespace App\Utilities;

use Statamic\Entries\Collection;
use Statamic\Facades\Collection as CollectionAPI;
use Statamic\Facades\Site;
use XMLWriter;

class SiteMapGenerator
{
    /**
     * Generate an XML sitemap for the website.
     *
     * @return string The generated XML sitemap as a string.
     */
    public function generate()
    {
        $excludedCollections = config('sitemap.exclude_collections', []);

        // Retrieve all collections and map them to an array of their handles.
        $collections = CollectionAPI::all()->filter(function (Collection $collection) use ($excludedCollections) {
            if (in_array($collection->handle(), $excludedCollections)) {
                return false;
            }

            return $collection->route(Site::default()->handle()) != null;
        })->map(function ($collection) {
            return $collection->handle();
        })->toArray();

        // Initialize an XMLWriter instance for building the XML sitemap.
        $writer = new XMLWriter;
        $writer->openMemory();
        $writer->startDocument('1.0', 'UTF-8');
        $writer->setIndent(true);

        // Start the root element of the sitemap.
        $writer->startElement('urlset');
        $writer->writeAttribute('xmlns', 'https://www.sitemaps.org/schemas/sitemap/0.9');

        // Loop through each collection.
        foreach ($collections as $collection) {
            // Retrieve all entries in the collection.
            $entries = Collection::findByHandle($collection)->queryEntries()->get();

            // Loop through each entry and add its information to the sitemap.
            foreach ($entries as $entry) {
                $writer->startElement('url');
                $writer->writeElement('loc', $entry->absoluteUrl());
                $writer->writeElement('lastmod', $entry->lastModified()->toAtomString());
                $writer->writeElement('changefreq', 'weekly');
                $writer->writeElement('priority', '0.5');
                $writer->endElement();
            }
        }

        // Close the root element and finish the XML document.
        $writer->endElement();
        $writer->endDocument();

        // Return the generated XML sitemap as a string.
        return $writer->outputMemory();
    }
}
