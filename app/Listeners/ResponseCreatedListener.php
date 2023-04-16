<?php

namespace App\Listeners;

use App\Utilities\Minifier;
use Statamic\Events\ResponseCreated;

class ResponseCreatedListener
{
    public function handle(ResponseCreated $event)
    {
        $content = $event->response->getContent();
        $content = (new Minifier)->minify($content);
        $event->response->setContent($content);
    }
}
