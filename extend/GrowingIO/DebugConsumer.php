<?php

namespace extend\GrowingIO;

class DebugConsumer extends Consumer
{
    private $uploader;
    
    public function __construct($options)
    {
        $this->uploader = new JSonUploader($options);
    }

    public function consume($event)
    {
        printf(json_encode($event)."\n");
        $this->uploader->uploadEvents(array($event));
    }
}