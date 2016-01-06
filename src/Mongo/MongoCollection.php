<?php
namespace Mongo;

class MongoCollection extends \MongoCollection
{
    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @param \MongoDB $db
     * @param string $name
     * @param CacheInterface $cache
     * @throws Exception
     * @return MongoCollection
     */
    public function __construct(\MongoDB $db, $name, CacheInterface $cache)
    {
        parent::__construct($db, $name);

        $this->cache = $cache;
    }

    /**
     * @param array|stdClass $query
     * @param null $limit
     * @param null $skip
     * @return int
     */
    public function count($query = [], $limit = null, $skip = null)
    {
        $options = $limit;

        if (is_array($options) && isset($options['cache']) && $options['cache'] === false) {
            return parent::count($query);
        }

        $hash = $this->hash($query);
        $result = $this->cache->get($hash);

        if (!$result) {
            $result = parent::count($query);
            $this->cache->set($hash, $result, $this->getCacheTime($options));
        }

        return $result;
    }

    /**
     * @param array $pipeline
     * @param array $op
     * @param null $op1
     * @return array
     */
    public function aggregate($pipeline, $op = null, $op1 = null)
    {
        $op = $op ?: [];

        if (is_array($op) && isset($op['cache']) && $op['cache'] === false) {
            unset($op['cache']);
            return parent::aggregate($pipeline, $op);
        }

        if (!isset($pipeline[0])) {
            $pipeline = func_get_args();
        }

        $hash = $this->hash($pipeline);
        $result = $this->cache->get($hash);

        if (!$result) {
            $result = parent::aggregate($pipeline, $op);
            $this->cache->set($hash, $result, $this->getCacheTime($op));
        }

        return $result;
    }

    /**
     * @param $query
     * @return string
     */
    private function hash($query)
    {
        return md5(serialize($query));
    }

    /**
     * @param array $options
     * @return int|null
     */
    private function getCacheTime($options)
    {
        $millis = null;
        if (is_array($options) && isset($options['cacheTime'])) {
            $millis = (int) $options['cacheTime'];
        }

        return $millis;
    }
}
