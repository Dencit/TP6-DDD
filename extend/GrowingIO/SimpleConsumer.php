<?php

namespace extend\GrowingIO;

class SimpleConsumer extends Consumer
{
    private $uploader;

    public function __construct($options)
    {
        $this->uploader = new JSonUploader($options);
    }

    public function consume($event)
    {
        $this->uploader->uploadEvents(array($event));
    }
}