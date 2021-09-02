<?php

namespace App\Classes;

class Mcrypt
{
    private $iv;
    private $key;
    private $iv2;
    private $key2;

    function __construct()
    {
        $this->iv = getenv('APP_MCRYPT_IV');
        $this->key = getenv('APP_MCRYPT_KEY');
        $this->iv2 = getenv('APP_MCRYPT_IV2');
        $this->key2 = getenv('APP_MCRYPT_KEY2');
    }

    function encrypt($str): string
    {

        //$this->key = $this->hex2bin($this->key);
        $iv = $this->iv;
        //$this->key = pack('H*', "5e024b2a5670d9823631e1bcce6f6be0ed21d638fd9bbcb4d47c2a76cf532dbde497e708a7c1a13ff84a49639ab53f22e305a19b71991f4361d1ca230c39fa4c");
        $td = @mcrypt_module_open('rijndael-128', '', 'cbc', $iv);

        @mcrypt_generic_init($td, $this->key, $iv);
        $encrypted = @mcrypt_generic($td, $str);

        @mcrypt_generic_deinit($td);
        @mcrypt_module_close($td);
        //return base64_encode($encrypted);
        return bin2hex($encrypted);
    }

    function encryptBase64Encode($str): string
    {

        //$this->key = $this->hex2bin($this->key);
        $iv = $this->iv;
        //$this->key = pack('H*', "5e024b2a5670d9823631e1bcce6f6be0ed21d638fd9bbcb4d47c2a76cf532dbde497e708a7c1a13ff84a49639ab53f22e305a19b71991f4361d1ca230c39fa4c");
        $td = @mcrypt_module_open('rijndael-128', '', 'cbc', $iv);

        @mcrypt_generic_init($td, $this->key, $iv);
        $encrypted = @mcrypt_generic($td, $str);

        @mcrypt_generic_deinit($td);
        @mcrypt_module_close($td);
        return base64_encode($encrypted);
        //return bin2hex($encrypted);
    }

    function encrypt2($str): string
    {

        //$key = $this->hex2bin($key);
        $iv = $this->iv2;
        $key = $this->key2;
        $td = @mcrypt_module_open('rijndael-128', '', 'cbc', $iv);

        @mcrypt_generic_init($td, $key, $iv);
        $encrypted = @mcrypt_generic($td, $str);

        @mcrypt_generic_deinit($td);
        @mcrypt_module_close($td);
        return bin2hex($encrypted);
        //return base64_encode($encrypted);
    }

    function decrypt($code)
    {
        //$key = $this->hex2bin($key);
        $code = base64_decode($code);
        print_r($code);
        $iv = $this->iv;

        $td = @mcrypt_module_open('rijndael-128', '', 'cbc', $iv);

        @mcrypt_generic_init($td, $this->key, $iv);
        $decrypted = mdecrypt_generic($td, $code);

        @mcrypt_generic_deinit($td);
        @mcrypt_module_close($td);

        return utf8_encode(trim($decrypted));
    }

    protected function hex2bin($hexdata): string
    {
        $bindata = '';

        for ($i = 0; $i < strlen($hexdata); $i += 2) {
            $bindata .= chr(hexdec(substr($hexdata, $i, 2)));
        }

        return $bindata;
    }


}
