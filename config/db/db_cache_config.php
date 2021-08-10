<?php

$cacheDriver = new \Doctrine\Common\Cache\PhpFileCache(__DIR__.'/db_cache');
$cacheDriver->save('cache_id', 'my_data');