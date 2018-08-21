<?php
require_once('Utility.inc.php');

class LoginConnection{
    private $url;
    private $headers;
    private $postData;
    public $returnCode;
    public $retData;
    public function init($url, $uuid, $account_type) {
        $this->url = $url;
        $this->headers = array('UUID: '.$uuid, 'account_type: '.$account_type, 'Content-Type:  data/gzencode and rsa public encrypt;charset=UTF-8');
    }
    public function genPostData($data) {
        $gzData = gzencode(json_encode($data), 9);
        $rsa = new RsaPublicEncrypt();
        for ($index = 0, $enData = ''; $index < strlen($gzData); $index += 117) {
            $gzPackData = substr($gzData, $index, 117);
            $enData .= $rsa->pubEncrypt($gzPackData);
        }
        $this->postData = $enData;
    }
    public function POST($data) {
        $this->genPostData($data);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->postData);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $tmpInfo = curl_exec($curl);
        if (curl_errno($curl)) {
            echo '[error] CURL ERROR: ' . curl_error($curl). PHP_EOL;
        }
        curl_close($curl);
        $this->returnCode = ord($tmpInfo[0])*64 + ord($tmpInfo[1]);
        if ($this->returnCode === 0) {
            $this->retData = substr($tmpInfo, 8);
        }
    }
}
