<h1 align="center"> douyin </h1>

## 安装

```shell
$ composer require peimengc/douyin
```

## 使用

```
use Peimengc\Douyin\Douyin

$douyin = new Douyin();
```

### 获取/检测二维码

```
$jar = new CookieJar();
$response = $douyin->setGuzzleOptions(['cookies' => $jar])->checkQrcode($token);
```
请求之后 `$jar` 已包含cookie
返回示例:
```

```

### 获取用户信息
```
// 使用扫码的 $jar
$response = $douyin->setGuzzleOptions(['cookies' => $jar])->getUserInfo();
```
返回示例:
```

```