<?php

namespace Peimengc\Douyin;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;

class Douyin
{
    const CHECK_QRCODE_URI = "https://www.douyin.com/passport/web/check_qrconnect/?aid=1128&next=https%3A%2F%2Fwww.douyin.com%2Flogin%2Fcallback%2F%3Fnext%3Dhttps%253A%252F%252Fwww.douyin.com%252Fpages%252Fdouyin_recharge%252Findex.html&token=";
    const USER_INFO_URI = 'https://creator.douyin.com/web/api/media/user/info/?_signature=_02B4Z6wo00d01QJRSVQAAIDCyu2AS1DahZUCVU3AACBnSjveA3YRfmmMyKX45Xlzbn4EkeaDkghdrEKIURC22MPvAVNO';
    protected $guzzleOptions = [];
    protected $xSecsdkCsrfToken;
    protected $middlewares = [];
    protected $handlerStack;

    public function getHttpClient()
    {
        return new Client($this->guzzleOptions);
    }

    public function pushMiddleware(callable $middleware, $name)
    {
        $this->middlewares[$name] = $middleware;
        return $this;
    }

    public function setGuzzleOptions(array $options)
    {
        $this->guzzleOptions = $options;
        return $this;
    }

    public function checkQrcode($token)
    {
        $uri = static::CHECK_QRCODE_URI . $token;
        return $this->request('GET', $uri);
    }

    public function getUserInfo()
    {
        return $this->request('GET', self::USER_INFO_URI);
    }

    //信誉分 口碑分
    public function getUserReputation()
    {
        $url = 'https://aweme.snssdk.com/aweme/v2/shop/user/reputation/?request_tag_from=rn&os_api=23&device_type=MI+5s&ssmix=a&manifest_version_code=120701&dpi=320&app_name=aweme&version_name=12.7.0&ts=1608271408&cpu_support64=false&storage_type=0&app_type=normal&host_abi=armeabi-v7a&update_version_code=12709900&channel=aweGW&device_platform=android&version_code=120700&language=zh&device_brand=Xiaomi&aid=1128';
        return $this->request('GET', $url);
    }

    //可提现佣金
    public function getUserCommission()
    {
        $url = 'https://lianmeng.snssdk.com/ies/v2/author/withdrawPageInfo?b_type_new=2&__t=&b_type=2&is_vcd=1&request_tag_from=h5&os_api=23&device_type=MI9&ssmix=a&manifest_version_code=110001&dpi=270&uuid=&app_name=aweme&version_name=11.0.0&ts=&cpu_support64=false&app_type=normal&ac=wifi&host_abi=armeabi-v7a&update_version_code=11009900&channel=360_aweme&_rticket=&device_platform=android&iid=&version_code=110000&openudid=&device_id=&os_version=6.0.1&language=zh&device_brand=Xiaomi&aid=1128&mcc_mnc=46003';
        return $this->request('GET', $url);
    }

    //视频列表
    public function mediaAwemePost($status = 0, $max_cursor = 0)
    {
        $uri = 'https://creator.douyin.com/web/api/media/aweme/post/';
        $query = [
            'scene' => 'star_atlas',
            'status' => $status,//0全部1已发布
            'count' => '12',
            'max_cursor' => $max_cursor,
        ];
        return $this->creatorRequest('GET', $uri, compact('query'));
    }

    //设置视频权限
    public function mediaAwemeUpdate($item_id, $visibility_type, $download = 0)
    {
        $uri = 'https://creator.douyin.com/web/api/media/aweme/update/';
        $form_params = [
            'item_id' => $item_id,
            'download' => $download,
            'visibility_type' => $visibility_type,//1私密0公开2好友可见
        ];
        return $this->creatorRequest('POST', $uri, compact('form_params'));
    }

    //获取x-secsdk-csrf-token
    public function withXSecsdkCsrfToken()
    {
        $this->xSecsdkCsrfToken = null;
        $uri = 'https://creator.douyin.com/web/api/media/aweme/update/';
        $headers = [
            'x-secsdk-csrf-version' => '1.2.7',
            'x-secsdk-csrf-request' => '1',
        ];
        $response = $this->creatorRequest('HEAD', $uri, compact('headers'), true);
        $this->xSecsdkCsrfToken = explode(',', $response->getHeader('x-ware-csrf-token')[0])[1];
        return $this;
    }

    //根据商品链接获取商品信息
    public function mediaShopPromotionLink($promotionLink)
    {
        $uri = 'https://creator.douyin.com/web/api/media/shop/promotion/link/';
        return $this->creatorRequest('POST', $uri, [
            'form_params' => [
                'promotion_link' => $promotionLink
            ]
        ]);
    }

    //shop_draft_id
    public function mediaShopDraftUpdate(string $rawDraft)
    {
        $uri = 'https://creator.douyin.com/web/api/media/shop/draft/update/';
        return $this->creatorRequest('POST', $uri, [
            'form_params' => [
                'raw_draft' => $rawDraft
            ]
        ]);
    }

    //创建视频
    public function mediaAwemeCreate(array $params)
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
            'hashtag_source' => '',
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
            'music_id' => ''
        ];
        return $this->creatorRequest('POST', $uri, [
            'form_params' => array_merge($default, $params)
        ]);
    }

    //删除已发布视频
    public function mediaAwemeDelete($itemId)
    {
        $uri = 'https://creator.douyin.com/web/api/media/aweme/delete/';
        return $this->creatorRequest('POST', $uri, [
            'form_params' => [
                'item_id' => $itemId
            ]
        ]);
    }

    // 视频详情带车信息
    public function bluevItemInfo($id)
    {
        $uri = 'https://e.douyin.com/aweme/v1/bluev/item/info';
        return $this->request()('GET', $uri, [
            'query' => ['id' => $id],
            'headers' => [
                'authority' => 'e.douyin.com',
                'sec-ch-ua' => '"Google Chrome";v="95", "Chromium";v="95", ";Not A Brand";v="99"',
                'accept' => 'application/json, text/plain, */*',
                'sec-ch-ua-mobile' => '?0',
                'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36',
                'sec-ch-ua-platform' => '"Windows"',
                'sec-fetch-site' => 'same-origin',
                'sec-fetch-mode' => 'cors',
                'sec-fetch-dest' => 'empty',
                'referer' => 'https://e.douyin.com/site/operation-center/video-manage/self/' . $id,
                'accept-language' => 'zh-CN,zh;q=0.9',
            ]
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
        if ($this->xSecsdkCsrfToken) {
            $headers['x-secsdk-csrf-token'] = $this->xSecsdkCsrfToken;
        }
        $options['headers'] = array_merge($headers, $options['headers'] ?? []);
        $options['query'] = array_merge($query, $options['query'] ?? []);
        return $this->request($method, $uri, $options, $raw);
    }

    //发送请求
    protected function request($method, $uri, array $options = [], $raw = false)
    {
        $options = array_merge($this->guzzleOptions, $options, ['handler' => $this->getHandlerStack()]);
        $response = $this->getHttpClient()->request($method, $uri, $options);
        if ($raw) return $response;
        return json_decode($response->getBody()->getContents(), true);
    }

    protected function getHandlerStack()
    {
        if ($this->handlerStack) {
            return $this->handlerStack;
        }

        $this->handlerStack = HandlerStack::create(new  CurlHandler());

        foreach ($this->middlewares as $name => $middleware) {
            $this->handlerStack->push($middleware, $name);
        }

        return $this->handlerStack;
    }

    protected function num2str($num)
    {
        $map = [
            "1" => "34",
            "2" => "37",
            "3" => "36",
            "4" => "31",
            "5" => "30",
            "6" => "33",
            "7" => "32",
            "9" => "3c",
            "0" => "35",
            "8" => "3d",
        ];
        $new = [];
        $len = strlen($num);
        for ($i = 0; $i < $len; $i++) {
            $cha = $num[$i];
            $new[] = $map[$cha];
        }
        return implode("", $new);
    }

    //app请求
    public function sendCode($mobile)
    {
        $uri = 'https://aweme.snssdk.com/passport/mobile/send_code/v1/';
        $form_params = [
            'is_vcd' => '1',
            'auto_read' => '0',
            'account_sdk_source' => 'app',
            'mix_mode' => '1',
            'multi_login' => '1',
            'type' => '3731',
            'unbind_exist' => '35',
            'mobile' => '2e3d3325' . $this->num2str($mobile),
        ];
        return $this->appRequest('POST', $uri, compact('form_params'));
    }

    public function smsLogin($mobile, $code)
    {
        $uri = 'https://aweme.snssdk.com/passport/mobile/sms_login/';

        $form_params = [
            'is_vcd' => '1',
            'account_sdk_source' => 'app',
            'mix_mode' => '1',
            'auth_opposite' => '0',
            'multi_login' => '1',
            'mobile' => '2e3d3325' . $this->num2str($mobile),
            'code' => $this->num2str($code),
        ];

        return $this->appRequest('POST', $uri, compact('form_params'));
    }

    public function upsmsQueryVerify($verify_ticket)
    {
        $uri = 'https://aweme.snssdk.com/passport/upsms/query_verify/';
        $query = [
            'verify_ticket' => $verify_ticket,
            'upstream_verify_type' => 1
        ];
        return $this->appRequest('GET', $uri, compact('query'));
    }

    public function upsmsLogin($verify_ticket)
    {
        $uri = 'https://aweme.snssdk.com/passport/upsms/login/';
        $query = [
            'verify_ticket' => $verify_ticket,
            'multi_login' => 1
        ];
        return $this->appRequest('GET', $uri, compact('query'));
    }

    protected function appRequest($method, $uri, array $options, $raw = false)
    {
        $query = [
            'passport-sdk-version' => '18',
            'os_api' => '23',
            'device_type' => 'MI 5s',
            'ssmix' => 'a',
            'manifest_version_code' => '160201',
            'dpi' => '270',
            'uuid' => '330000000204900',
            'app_name' => 'aweme',
            'version_name' => '16.2.0',
            'ts' => time(),
            'cpu_support64' => 'false',
            'app_type' => 'normal',
            'appTheme' => 'dark',
            'ac' => 'wifi',
            'host_abi' => 'armeabi-v7a',
            'update_version_code' => '16209900',
            'channel' => 'wandoujia_lesi_1128_0525',
            '_rticket' => '1637543891920',
            'device_platform' => 'android',
            'iid' => '4424888695142616',
            'version_code' => '160200',
            'cdid' => '72610f89-9d24-433d-8069-05ea849e7b5e',
            'is_android_pad' => '0',
            'openudid' => '694c6a45b8731b45',
            'device_id' => '71570929069',
            'resolution' => '810*1440',
            'os_version' => '6.0.1',
            'language' => 'zh',
            'device_brand' => 'Xiaomi',
            'aid' => '1128',
            'minor_status' => '0'
        ];
        $options['query'] = array_merge($query, $options['query'] ?? []);
        return $this->request($method, $uri, $options, $raw);
    }
}