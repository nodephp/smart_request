# smartRequest use php-curl


## install
composer require forfire/smart_request

## use 
```
use Smart\SmartRequest;

$res = SmartRequest::request('get', 'https://api.douban.com/v2/book/search', ['q' => 'golang', 'start' => 0, 'count' => 1], [], [], true);
```

## desc
field|type|desc|example
---|---|---|---|---
method|string|request type|get/post
url|string|request url|https://api.douban.com/v2/book/search
params|array|request params|['q' => 'golang', 'start' => 0, 'count' => 1]
headers|array|request header|['Content-Type: application/json'] 
options|array|curl options|
needJson|bool|need json parse|default true

## explames

### POST with header
```
$params = ['fql' => ['tp' => 'note', 'con' => ['keywords' => '吃'], 'page' => ['start' => 0, 'num' => 1]]];
$headers = ['Content-Type: application/json'];
$res = SmartRequest::request('post', 'https://api.douban.com/v2/book/search', $params, $headers, [], true);
```

### add options

$params = ['fql' => ['tp' => 'note', 'con' => ['keywords' => '吃'], 'page' => ['start' => 0, 'num' => 1]]];
$options = [CURLOPT_HEADER => 1,CURLOPT_RETURNTRANSFER => 1];
$res = SmartRequest::request('post', 'https://api.douban.com/v2/book/search', $params, [], $options, true);




