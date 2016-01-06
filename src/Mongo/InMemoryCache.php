<?php
namespace Mongo;

class InMemoryCache implements CacheInterface
{
    private $storage = [];

    public function set($key, $value, $time = null)
    {
        $this->storage[$key] = $value;
    }

    public function get($key)
    {
        return isset($this->storage[$key]) ? $this->storage[$key] : null;
    }
}
