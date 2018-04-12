<?php
$classMap = [
    'Smart\\SmartRequest' => '../src/SmartRequest.php',

];
spl_autoload_register(function ($class) use ($classMap) {
    include $classMap[$class];
});

use Smart\SmartRequest;

$res = SmartRequest::request('get', 'https://api.douban.com/v2/book/search', ['q' => 'golang', 'start' => 0, 'count' => 1], [], [], true);

if (!$res) {
    var_dump(SmartRequest::getError());
} else {
    var_dump($res);
}
