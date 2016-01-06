<?php
namespace Mongo;

class MongoDB extends \MongoDB
{
    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * MongoDB constructor.
     * @param \MongoClient $conn
     * @param string $name
     * @param CacheInterface $cache
     */
    public function __construct(\MongoClient $conn, $name, CacheInterface $cache)
    {
        parent::__construct($conn, $name);

        $this->cache = $cache;
    }

    /**
     * @param string $name
     * @throws Exception if the collection name is invalid.
     * @return MongoCollection
     */
    public function selectCollection($name)
    {
        return new MongoCollection($this, $name, $this->cache);
    }

    /**
     * @param string $name The name of the collection.
     * @return MongoCollection
     */
    public function __get($name)
    {
        return $this->selectCollection($name);
    }
}
