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

    //视频列表
    public function mediaAwemePost($status = 0, $max_cursor = 0)
    {
        $url = 'https://creator.douyin.com/web/api/media/aweme/post/';
        $query = [
            'scene' => 'star_atlas',
            'status' => $status,//0全部1已发布
            'count' => '12',
            'max_cursor' => $max_cursor,
            'cookie_enabled' => 'true',
            'screen_width' => '1536',
            'screen_height' => '864',
            'browser_language' => 'zh-CN',
            'browser_platform' => 'Win32',
            'browser_name' => 'Mozilla',
            'browser_version' => '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36',
            'browser_online' => 'true',
            'timezone_name' => 'Asia/Shanghai',
            'aid' => '1128',
            '_signature' => ''
        ];
        $headers = [
            'authority' => 'creator.douyin.com',
            'sec-ch-ua' => '"Google Chrome";v="95", "Chromium";v="95", ";Not A Brand";v="99"',
            'accept' => 'application/json, text/plain, */*',
            'sec-ch-ua-mobile' => '?0',
            'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36',
            'sec-ch-ua-platform' => '"Windows"',
            'sec-fetch-site' => 'same-origin',
            'sec-fetch-mode' => 'cors',
            'sec-fetch-dest' => 'empty',
            'referer' => 'https://creator.douyin.com/creator-micro/content/manage',
            'accept-language' => 'zh-CN,zh;q=0.9',
        ];
        $contents = $this->getHttpClient()->get($url, compact('query', 'headers'))->getBody()->getContents();
        return json_decode($contents, true);
    }

    //设置视频权限
    public function mediaAwemeUpdate($xSecsdkCsrfToken, $item_id, $visibility_type, $download = 0)
    {
        $url = 'https://creator.douyin.com/web/api/media/aweme/update/';
        $query = [
            'cookie_enabled' => 'true',
            'screen_width' => '1536',
            'screen_height' => '864',
            'browser_language' => 'zh-CN',
            'browser_platform' => 'Win32',
            'browser_name' => 'Mozilla',
            'browser_version' => '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36',
            'browser_online' => 'true',
            'timezone_name' => 'Asia/Shanghai',
            'aid' => '1128',
            '_signature' => ''
        ];
        $headers = [
            'authority' => 'creator.douyin.com',
            'sec-ch-ua' => '"Google Chrome";v="95", "Chromium";v="95", ";Not A Brand";v="99"',
            'accept' => 'application/json, text/plain, */*',
            'content-type' => 'application/x-www-form-urlencoded;charset=UTF-8',
            'x-secsdk-csrf-token' => $xSecsdkCsrfToken,
            'sec-ch-ua-mobile' => '?0',
            'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36',
            'sec-ch-ua-platform' => '"Windows"',
            'origin' => 'https://creator.douyin.com',
            'sec-fetch-site' => 'same-origin',
            'sec-fetch-mode' => 'cors',
            'sec-fetch-dest' => 'empty',
            'referer' => 'https://creator.douyin.com/creator-micro/content/manage',
            'accept-language' => 'zh-CN,zh;q=0.9',
        ];
        $form_params = [
            'item_id' => $item_id,
            'download' => $download,
            'visibility_type' => $visibility_type,//1私密0公开2好友可见
        ];
        $contents = $this->getHttpClient()->post($url, compact('query', 'headers', 'form_params'))->getBody()->getContents();
        return json_decode($contents, true);
    }

    //获取x-secsdk-csrf-token
    public function mediaAwemeUpdateHead()
    {
        $url = 'https://creator.douyin.com/web/api/media/aweme/update/';
        $headers = [
            'authority' => 'creator.douyin.com',
            'sec-ch-ua' => '"Google Chrome";v="95", "Chromium";v="95", ";Not A Brand";v="99"',
            'x-secsdk-csrf-version' => '1.2.7',
            'x-secsdk-csrf-request' => '1',
            'sec-ch-ua-mobile' => '?0',
            'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36',
            'sec-ch-ua-platform' => '"Windows"',
            'accept' => '*/*',
            'sec-fetch-site' => 'same-origin',
            'sec-fetch-mode' => 'cors',
            'sec-fetch-dest' => 'empty',
            'referer' => 'https://creator.douyin.com/creator-micro/content/manage',
            'accept-language' => 'zh-CN,zh;q=0.9',
        ];
        $response = $this->getHttpClient()->head($url, compact('headers'));
        return explode(',', $response->getHeader('x-ware-csrf-token')[0])[1];
    }

    //根据商品链接获取商品信息
    public function mediaShopPromotionLink($promotionLink, $xSecsdkCsrfToken)
    {
        $uri = 'https://creator.douyin.com/web/api/media/shop/promotion/link/';
        return $this->creatorRequest('POST', $uri, [
            'headers' => [
                'x-secsdk-csrf-token' => $xSecsdkCsrfToken,
            ],
            'form_params' => [
                'promotion_link' => $promotionLink
            ]
        ]);
    }

    //shop_draft_id
    public function mediaShopDraftUpdate(string $rawDraft, $xSecsdkCsrfToken)
    {
        $uri = 'https://creator.douyin.com/web/api/media/shop/draft/update/';
        return $this->creatorRequest('POST', $uri, [
            'headers' => [
                'x-secsdk-csrf-token' => $xSecsdkCsrfToken,
            ],
            'form_params' => [
                'raw_draft' => $rawDraft
            ]
        ]);
    }

    //创建视频
    public function mediaAwemeCreate(array $params, $xSecsdkCsrfToken)
    {
        $uri = 'https://creator.douyin.com/web/api/media/aweme/create/';
        $default = [
            //'video_id' => 'v0200fg10000c6a5forc77u4lg0ftosg',
            'ifLongTitle' => 'true',
            //'text' => '#马桶刷 #硅胶马桶刷 #马桶除臭 哈哈哈',
            'record' => 'null',
            'source_info' => '',
            //'text_extra' => '[{"start":0,"end":4,"user_id":"","type":1,"hashtag_name":"马桶刷"},{"start":5,"end":11,"user_id":"","type":1,"hashtag_name":"硅胶马桶刷"},{"start":12,"end":17,"user_id":"","type":1,"hashtag_name":"马桶除臭"}]',
            'challenges' => '[]',
            'mentions' => '[]',
            'hashtag_source' => '"recommend/recommend/recommend"',
            'visibility_type' => '0',
            'download' => '0',
            'upload_source' => '1',
            'is_preview' => '0',
            'hot_sentence' => '',
            'cover_text_uri' => '',
            'cover_text' => '',
            //'poster' => 'tos-cn-p-0015/093bd8078b0d4f0a8d6025006e249385',
            'poster_delay' => '0',
            //'shop_draft_id' => '3515671237245439260',
            'music_source' => '0',
            'music_id' => ','
        ];
        return $this->creatorRequest('POST', $uri, [
            'headers' => [
                'x-secsdk-csrf-token' => $xSecsdkCsrfToken,
            ],
            'form_params' => array_merge($default, $params)
        ]);
    }

    // 创作着平台请求
    protected function creatorRequest($method, $uri, array $options, $raw = false)
    {
        $query = [
            'cookie_enabled' => 'true',
            'screen_width' => '1536',
            'screen_height' => '864',
            'browser_language' => 'zh-CN',
            'browser_platform' => 'Win32',
            'browser_name' => 'Mozilla',
            'browser_version' => '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36',
            'browser_online' => 'true',
            'timezone_name' => 'Asia/Shanghai',
            'aid' => '1128',
            '_signature' => ''
        ];
        $headers = [
            'authority' => 'creator.douyin.com',
            'sec-ch-ua' => '"Google Chrome";v="95", "Chromium";v="95", ";Not A Brand";v="99"',
            'accept' => 'application/json, text/plain, */*',
            'sec-ch-ua-mobile' => '?0',
            'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36',
            'sec-ch-ua-platform' => '"Windows"',
            'sec-fetch-site' => 'same-origin',
            'sec-fetch-mode' => 'cors',
            'sec-fetch-dest' => 'empty',
            'referer' => 'https://creator.douyin.com/creator-micro/content/manage',
            'accept-language' => 'zh-CN,zh;q=0.9',
        ];
        $options['headers'] = array_merge($headers, $options['headers'] ?? []);
        $options['query'] = array_merge($query, $options['query'] ?? []);
        $response = $this->getHttpClient()->request($method, $uri, $options);
        if ($raw) return $response;
        return json_decode($response->getBody()->getContents(), true);
    }
}