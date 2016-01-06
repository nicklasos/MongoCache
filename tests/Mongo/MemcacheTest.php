<?php
namespace Mongo;

class MemcacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Memcache
     */
    private $memcache;

    public function setUp()
    {
        parent::setUp();

        //$this->memcache = new \Memcache();
        //$this->memcache->connect(
        //    'localhost',
        //    '11211'
        //);
    }

    public function testTest()
    {
        return;

        $mongo = new MongoDB(
            new \MongoClient('mongodb://localhost:27017'),
            'mongo_test',
            new Memcache($this->memcache, 1)
        );

        $collection = $mongo->selectCollection('test_memcache');

        $collection->insert(['foo' => 1]);
        $this->assertEquals(1, $collection->count());

        sleep(1);

        $collection->insert(['foo' => 1]);
        $this->assertEquals(2, $collection->count());
    }

    protected function tearDown()
    {
        parent::tearDown();

        //$this->memcache->flush();
    }
}
