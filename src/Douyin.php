<?php

namespace Peimengc\Douyin;

use GuzzleHttp\Client;

class Douyin
{
    const CHECK_QRCODE_URI = "https://www.douyin.com/passport/web/check_qrconnect/?aid=1128&next=https%3A%2F%2Fwww.douyin.com%2Flogin%2Fcallback%2F%3Fnext%3Dhttps%253A%252F%252Fwww.douyin.com%252Fpages%252Fdouyin_recharge%252Findex.html&token=";
    const USER_INFO_URI = 'https://creator.douyin.com/web/api/media/user/info/?_signature=_02B4Z6wo00d01QJRSVQAAIDCyu2AS1DahZUCVU3AACBnSjveA3YRfmmMyKX45Xlzbn4EkeaDkghdrEKIURC22MPvAVNO';
    protected $key;
    protected $guzzleOptions = [];

    public function getHttpClient()
    {
        return new Client($this->guzzleOptions);
    }

    public function setGuzzleOptions(array $options)
    {
        $this->guzzleOptions = $options;
        return $this;
    }

    public function checkQrcode($token)
    {
        $uri = static::CHECK_QRCODE_URI . $token;
        $contents = $this->getHttpClient()->get($uri)->getBody()->getContents();
        $result = json_decode($contents, true);
        if ($result['message'] !== 'success') {
            throw new ResponseContentsException("二维码检测失败！", $contents);
        }
        return $result;
    }

    public function getUserInfo()
    {
        $contents = $this->getHttpClient()->get(self::USER_INFO_URI)->getBody()->getContents();
        $result = json_decode($contents, true);
        if ($result['status_code'] !== 0) {
            throw new ResponseContentsException("获取用户信息失败！", $contents);
        }
        return $result;
    }
}