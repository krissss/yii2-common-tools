<?php

namespace kriss\tools;

class AES
{
    protected static function getKEY()
    {
        // 生成方式：base64_encode(openssl_random_pseudo_bytes(32));
        return base64_decode('ZULnwdUJGWoX0OPJFdgYfM1zEJNaSSP6+etzAX1lVPE=');
    }

    protected static function getIV()
    {
        // 生成方式：base64_encode(openssl_random_pseudo_bytes(16));
        return base64_decode('GXo1x3fsrl6k0uAODL5HBg==');
    }

    /**
     * 加密
     * @param $str
     * @return string
     */
    public static function encrypt($str)
    {
        $encrypted = openssl_encrypt($str, 'aes-256-cbc', static::getKEY(), OPENSSL_RAW_DATA, (static::getIV()));
        return strtr(base64_encode($encrypted), '+/', '_-');
    }

    /**
     * 解密
     * @param $str
     * @return string
     */
    public static function decrypt($str)
    {
        $encrypted = base64_decode(strtr($str, '_-', '+/'));
        return openssl_decrypt($encrypted, 'aes-256-cbc', static::getKEY(), OPENSSL_RAW_DATA, static::getIV());
    }
}