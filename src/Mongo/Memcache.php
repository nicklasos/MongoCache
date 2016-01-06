<?php
namespace Mongo;

class Memcache implements CacheInterface
{
    /**
     * @var \Memcache
     */
    private $memcache;

    /**
     * @var int
     */
    private $millis;

    /**
     * @param \Memcache $memcache
     * @param int $millis
     */
    public function __construct(\Memcache $memcache, $millis)
    {
        $this->memcache = $memcache;
        $this->millis = $millis;
    }

    public function set($key, $value, $millis = null)
    {
        $this->memcache->set($key, $value, MEMCACHE_COMPRESSED, $millis ?: $this->millis);
    }

    public function get($key)
    {
        return $this->memcache->get($key);
    }
}
