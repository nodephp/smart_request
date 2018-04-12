<?php
namespace Smart;

class SmartRequest
{
    const JSON_PARSE_ERROR = "json parse error";
    private static $error = [];
    private static $curlInfo;
    private static $connectTimeout = 5;
    private static $curlTimeout = 8;

    /**
     * 设置timeout时间
     * @param int $connectTimeout  连接超时时间
     * @param int $curlTimeout  总超时时间
     * @return mixed
     */
    public static function setTimeout($connectTimeout, $curlTimeout)
    {
        self::$connectTimeout = $connectTimeout;
        self::$curlTimeout = $curlTimeout;
    }

    /**
     * 发起请求
     * @param string $method get/post
     * @param string $url 请求地址
     * @param array $params 请求参数
     * @param array $headers 请求头参数
     * @param array $addOptions 添加options
     * @param bool $needJson 是否需要json解析
     * @return mixed
     */
    public static function request($method, $url, $params = [], $headers = [], $addOptions = [], $needJson = true)
    {
        if (!$url || !function_exists('curl_init')) {
            return false;
        }
        $ch = curl_init();
        if ($method == 'post') {
            curl_setopt($ch, CURLOPT_POST, true);
        }
        if ($params) {
            if ($method == 'get') {
                $str_param = http_build_query($params);
                if (strpos($url, "?") !== false) {
                    $url = $url . "&" . $str_param;
                } else {
                    $url = $url . "?" . $str_param;
                }
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            }
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::$connectTimeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, self::$curlTimeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($addOptions && is_array($addOptions)) {
            curl_setopt_array($ch, $addOptions);
        }
        if ($headers) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        $result = curl_exec($ch);
        $httpInfo = curl_getinfo($ch);
        self::setInfo($httpInfo);

        if (!$result) {
            $errmsg = curl_error($ch);
            curl_close($ch);
            self::setError($errmsg);
            return false;
        } else {
            if ($httpInfo['http_code'] != 200) {
                curl_close($ch);
                self::setError('http error code ' . $httpInfo['http_code']);
                return false;
            } else {
                if ($needJson) {
                    $json = json_decode($result, true);
                    if (!$json) {
                        self::setError(self::JSON_PARSE_ERROR);
                        return false;
                    } else {
                        return $json;
                    }
                } else {
                    return $result;
                }
            }

        }
    }

    public static function getInfo()
    {
        return self::$curlInfo;
    }

    public static function getError()
    {
        return self::$error;
    }

    private static function setInfo($info)
    {
        self::$curlInfo = $info;
    }

    private static function setError($msg)
    {
        self::$error = $msg;
    }

}
