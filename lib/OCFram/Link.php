<?php
namespace OCFram;

class Link
{
    use Hydrator;

    protected $name;
    protected $uri;
    protected $access = [];

    public function __construct(array $options = [])
    {
        if (!empty($options))
        {
            $this->hydrate($options);
        }
    }

    public function name()
    {
        return $this->name;
    }

    public function uri()
    {
        return $this->uri;
    }

    public function access()
    {
        return $this->access;
    }

    public function setUri($uri)
    {
        if (is_string($uri))
        {
            $this->uri = $uri;
        }
    }

    public function setName($name)
    {
        if (is_string($name))
        {
            $this->name = $name;
        }
    }

    public function setAccess(array $accesses)
    {
        foreach ($accesses as $right)
        {
            if (is_integer($right))
            {
                $this->access[] = $right;
            }
        }
    }
}