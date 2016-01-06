<?php
namespace Mongo;

class MongoDBTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MongoDB
     */
    private $mongo;

    public function setUp()
    {
        parent::setUp();

        $this->mongo = new MongoDB(
            new \MongoClient('mongodb://localhost:27017'),
            'mongo_test',
            new InMemoryCache()
        );
    }

    public function testCollection()
    {
        $this->assertInstanceOf(MongoCollection::class, $this->mongo->selectCollection('test'));
    }

    public function testGet()
    {
        $this->assertInstanceOf(MongoCollection::class, $this->mongo->test);
    }
}
