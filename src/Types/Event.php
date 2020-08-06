<?php

namespace Marshmallow\GoogleAnalytics\Types;

use Marshmallow\GoogleAnalytics\EventDataException;
use Marshmallow\GoogleAnalytics\Contracts\GoogleAnalyticsType;

class Event implements GoogleAnalyticsType
{
    protected $label;
    protected $value;
    protected $action;
    protected $category;

    public function category($category)
    {
        $this->category = $category;
        return $this;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function action($action)
    {
        $this->action = $action;
        return $this;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function label($label)
    {
        $this->label = $label;
        return $this;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function value($value)
    {
        $this->value = $value;
        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function validate(): void
    {
        if (!$this->getCategory()) {
            throw new EventDataException('Please provide a category to your event. This is mandatory by Google.');
        }
        if (!$this->getAction()) {
            throw new EventDataException('Please provide an action to your event. This is mandatory by Google.');
        }
    }
}
