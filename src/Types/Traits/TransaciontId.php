<?php

namespace Marshmallow\GoogleAnalytics\Types\Traits;

trait TransaciontId
{
    protected $id;

    public function id($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }
}
