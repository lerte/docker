<?php
class BaiduTongjiRsaPublicEncrypt {
    private $publicKey;
    public function __construct(){
    }

    public function setupPublicKey(){
        if(is_resource($this->publicKey)){
            return true;
        }
        $file = plugins_url('', __FILE__) .  '/api_pub.key';
        $puk = file_get_contents($file);
        $this->publicKey = openssl_pkey_get_public($puk);
        return true;
    }

    public function pubEncrypt($data){
        if(!is_string($data)){
            return null;
        }
        $this->setupPublicKey();
        $ret = openssl_public_encrypt($data, $encrypted, $this->publicKey);
        if($ret){
            return $encrypted;
        }else{
            return null;
        }
    }
    
    public function __destruct(){
        @fclose($this->publicKey);
    }
}

if (!function_exists('gzdecode')) {
    function gzdecode($data) { 
        $flags = ord(substr($data, 3, 1)); 
        $headerlen = 10; 
        $extralen = 0; 
        $filenamelen = 0; 
        if ($flags & 4) {
            $extralen = unpack('v' ,substr($data, 10, 2)); 
            $extralen = $extralen[1]; 
            $headerlen += 2 + $extralen; 
        }       
        if ($flags & 8) {
            $headerlen = strpos($data, chr(0), $headerlen) + 1; 
        }
        if ($flags & 16) {
            $headerlen = strpos($data, chr(0), $headerlen) + 1; 
        }
        if ($flags & 2) {
            $headerlen += 2; 
        }
        $unpacked = @gzinflate(substr($data, $headerlen)); 
        if ($unpacked === false) {
            $unpacked = $data;
        }
        return $unpacked; 
    } 
}
