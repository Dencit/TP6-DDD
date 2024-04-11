<?php

namespace extend\GrowingIO;


abstract class Consumer
{
    public abstract function consume($event);
}