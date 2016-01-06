# MongoCache

```php
$memcache = new Memcache();
$memcache->connect('localhost', 11211);

$mongo = new \Mongo\MongoDB(
    new \MongoClient('mongodb://localhost:27017'),
    'dbName',
    new \Mongo\Memcache(
        $memcache,
        60 * 30 // 30 mins
    )
);

$collection = $mongo->selectCollection('foo');
$collection->insert(['foo' => 1]);

assert($collection->count() == 1);

$collection->insert(['foo' => 1]);

assert($collection->count() == 1);

sleep(60 * 30 + 1);

assert($collection->count() == 2);
```

###Supported methods
* count
* aggregate