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
        return json_decode($contents, true);
    }

    public function getUserInfo()
    {
        $contents = $this->getHttpClient()->get(self::USER_INFO_URI)->getBody()->getContents();
        return json_decode($contents, true);
    }

    //信誉分 口碑分
    public function getUserReputation()
    {
        $url = 'https://aweme.snssdk.com/aweme/v2/shop/user/reputation/?request_tag_from=rn&os_api=23&device_type=MI+5s&ssmix=a&manifest_version_code=120701&dpi=320&app_name=aweme&version_name=12.7.0&ts=1608271408&cpu_support64=false&storage_type=0&app_type=normal&host_abi=armeabi-v7a&update_version_code=12709900&channel=aweGW&device_platform=android&version_code=120700&language=zh&device_brand=Xiaomi&aid=1128';
        $contents = $this->getHttpClient()->get($url)->getBody()->getContents();
        return json_decode($contents, true);
    }

    //可提现佣金
    public function getUserCommission()
    {
        $url = 'https://lianmeng.snssdk.com/ies/v2/author/withdrawPageInfo?b_type_new=2&__t=&b_type=2&is_vcd=1&request_tag_from=h5&os_api=23&device_type=MI9&ssmix=a&manifest_version_code=110001&dpi=270&uuid=&app_name=aweme&version_name=11.0.0&ts=&cpu_support64=false&app_type=normal&ac=wifi&host_abi=armeabi-v7a&update_version_code=11009900&channel=360_aweme&_rticket=&device_platform=android&iid=&version_code=110000&openudid=&device_id=&os_version=6.0.1&language=zh&device_brand=Xiaomi&aid=1128&mcc_mnc=46003';
        $contents = $this->getHttpClient()->get($url)->getBody()->getContents();
        return json_decode($contents, true);
    }
}