<?php
namespace Mongo;

class MongoCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MongoDB
     */
    private $mongo;

    /**
     * @var MongoCollection
     */
    private $collection;

    public function setUp()
    {
        parent::setUp();

        $this->mongo = new MongoDB(
            new \MongoClient('mongodb://localhost:27017'),
            'mongo_test',
            new InMemoryCache()
        );

        $this->collection = $this->mongo->selectCollection('test');

        for ($i = 0; $i < 2; $i++) {
            $this->collection->insert(['foo' => 1]);
        }
    }

    public function testCount()
    {
        $this->assertEquals(3, $this->collection->count());
        $this->assertEquals(2, $this->collection->count(['foo' => 1]));
        $this->assertEquals(1, $this->collection->count(['foo' => 2]));
    }

    public function testCacheCount()
    {
        $this->assertEquals(2, $this->collection->count(['foo' => 1]));

        $this->collection->insert(['foo' => 1]);

        $this->assertEquals(2, $this->collection->count(['foo' => 1]));
        $this->assertEquals(4, $this->collection->count());

    }

    public function testSkipCache()
    {
        $this->assertEquals(2, $this->collection->count(['foo' => 1]));

        $this->collection->insert(['foo' => 1]);

        $this->assertEquals(3, $this->collection->count(['foo' => 1], ['cache' => false]));
    }

    public function testAggregate()
    {
        $result = $this->collection->aggregate([
            [
                '$group' => [
                    '_id' => 'foo',
                    'sum' => ['$sum' => '$foo'],
                ],
            ],
        ]);

        $this->assertEquals(4, $result['result'][0]['sum']);
    }

    public function testCacheAggregate()
    {
        $result = $this->collection->aggregate([
            [
                '$group' => [
                    '_id' => 'foo',
                    'sum' => ['$sum' => '$foo'],
                ],
            ],
        ]);

        $this->assertEquals(4, $result['result'][0]['sum']);

        $this->collection->insert(['foo' => 3]);

        $result = $this->collection->aggregate([
            [
                '$group' => [
                    '_id' => 'foo',
                    'sum' => ['$sum' => '$foo'],
                ],
            ],
        ]);

        $this->assertEquals(4, $result['result'][0]['sum']);

        $result = $this->collection->aggregate([
            [
                '$group' => [
                    'sum' => ['$sum' => '$foo'],
                    '_id' => 'foo',
                ],
            ],
        ]);

        $this->assertEquals(7, $result['result'][0]['sum']);
    }

    public function testSkipCacheAggregate()
    {
        $result = $this->collection->aggregate([
            [
                '$group' => [
                    '_id' => 'foo',
                    'sum' => ['$sum' => '$foo'],
                ],
            ],
        ]);

        $this->assertEquals(4, $result['result'][0]['sum']);

        $this->collection->insert(['foo' => 3]);

        $result = $this->collection->aggregate([
            [
                '$group' => [
                    '_id' => 'foo',
                    'sum' => ['$sum' => '$foo'],
                ],
            ],
        ], ['cache' => false]);

        $this->assertEquals(7, $result['result'][0]['sum']);
    }

    public function testPipeline()
    {
        $result = $this->collection->aggregate([
            '$group' => [
                '_id' => 'foo',
                'sum' => ['$sum' => '$foo'],
            ],
        ]);

        $this->assertEquals(4, $result['result'][0]['sum']);
    }
}
