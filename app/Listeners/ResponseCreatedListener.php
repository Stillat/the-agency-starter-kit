<?php

namespace App\Listeners;

use App\Utilities\Minifier;
use Statamic\Events\ResponseCreated;

class ResponseCreatedListener
{
    public function handle(ResponseCreated $event)
    {
        // The ResponseCreated event is fired each time
        // Statamic returns a Statamic\Http\Responses\DataResponse
        // object. This event will be fired for every page
        // that is loaded on the site, including when
        // using the static site generator (SSG).

        // We can take advantage of this to minify the
        // HTML that is returned to the browser. The
        // minifier implementation can be found at:
        // app\Utilities\Minifier.php
        $content = $event->response->getContent();
        $content = (new Minifier)->minify($content);
        $event->response->setContent($content);
    }
}
