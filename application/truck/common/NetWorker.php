<?php
namespace app\truck\common;

class NetWorker{
    
    public function sentPost($header, $data, $url)
    {
        // create curl resource
        $ch = curl_init();
        
        // set url
        curl_setopt($ch, CURLOPT_URL, $url);
        
        // return the transfer as a string
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        curl_setopt($ch, CURLOPT_TIMEOUT, 300); // 设置超时限制防止死循环
        curl_setopt($ch, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        
        // 设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        
        // $output contains the output string
        $output = curl_exec($ch);
        
        if ($output === false) {
            echo '出现错误！';
            echo curl_errno($ch);
            curl_close($ch);
            exit();
        }
        
        // close curl resource to free up system resources
        curl_close($ch);
        
        
        return $output;
    }
    
    
    // 简单demo，默认支持为GET请求
    public function multiRequest($urls) {
        $mh = curl_multi_init();
        $urlHandlers = [];
        $urlData = [];
        // 初始化多个请求句柄为一个
        foreach($urls as $value) {
            $ch = curl_init();
            $url = $value['url'];
            $url .= strpos($url, '?') ? '&' : '?';
            $params = $value['params'];
            $url .= is_array($params) ? http_build_query($params) : $params;
            curl_setopt($ch, CURLOPT_URL, $url);
            // 设置数据通过字符串返回，而不是直接输出
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $urlHandlers[] = $ch;
            curl_multi_add_handle($mh, $ch);
        }
        $active = null;
        // 检测操作的初始状态是否OK，CURLM_CALL_MULTI_PERFORM为常量值-1
        do {
            // 返回的$active是活跃连接的数量，$mrc是返回值，正常为0，异常为-1
            $mrc = curl_multi_exec($mh, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        // 如果还有活动的请求，同时操作状态OK，CURLM_OK为常量值0
        while ($active && $mrc == CURLM_OK) {
            // 持续查询状态并不利于处理任务，每50ms检查一次，此时释放CPU，降低机器负载
            usleep(50000);
            // 如果批处理句柄OK，重复检查操作状态直至OK。select返回值异常时为-1，正常为1（因为只有1个批处理句柄）
            if (curl_multi_select($mh) != -1) {
                do {
                    $mrc = curl_multi_exec($mh, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }
        // 获取返回结果
        foreach($urlHandlers as $index => $ch) {
            $urlData[$index] = curl_multi_getcontent($ch);
            // 移除单个curl句柄
            curl_multi_remove_handle($mh, $ch);
        }
        curl_multi_close($mh);
        return $urlData;
    }


    /**
     * @param array $header
     * @param $data
     * @param $url
     * @return bool|string
     */
    public static function getPostResult(array $header, $data, $url)
    {
        $worker = new NetWorker();
        $result = $worker->sentPost($header, $data, $url);
        return $result;
    }

    /** 从cache中获取 cookie
     * @return mixed|string
     */
    public static function getCookiesCache(){
        $cookie = cache('truckCookie');
        if ($cookie == null){
            return 'empty_cookie';
        }

        else
            return $cookie;
    }

    public  static function getHeader($cookie, $data)
    {
        $dataLen = strlen($data);
        return array(
            'Accept:application/json, text/javascript, */*; q=0.01',
            'Accept-Encoding:gzip, deflate, br',
            'Accept-Language:zh-CN,zh;q=0.9',
            'Connection:keep-alive',
            'Content-Length:' . $dataLen,
            'Content-Type:application/x-www-form-urlencoded',
            'Cookie:JSESSIONID=' . $cookie,
            'Host:jg.gghypt.net',
            'Origin:https://jg.gghypt.net',
            'Referer:https://jg.gghypt.net/hyjg/statisticsAction!statisticsVehicleAlarm.action',
            'User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36',
            'X-Requested-With:XMLHttpRequest'
        );
    }

    public static function setCookie($cookie){
        cache('truckCookie', $cookie);
    }


}