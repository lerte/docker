<?php
require_once('BaiduTongjiLoginConnection.inc.php');

class BaiduTongjiLoginService {
    private $loginUrl;
    private $uuid;
    public function __construct($loginUrl, $uuid, $account_type) {
        $this->loginUrl = $loginUrl;
        $this->uuid = $uuid;
      	$this->account_type = $account_type;
    }
    public function preLogin($userName, $token) {
        $preLogin = new BaiduTongjiLoginConnection();
        $preLogin->init($this->loginUrl, $this->uuid, $this->account_type);
        $preLoginData = array(
            'username' => $userName,
            'token' => $token,
            'functionName' => 'preLogin',
            'uuid' => $this->uuid,
            'request' => array(
                'osVersion' => 'windows',
                'deviceType' => 'pc',
                'clientVersion' => '1.0',
            ),
        );
        $preLogin->POST($preLoginData);
        if ($preLogin->returnCode === 0) {
            $retData = gzdecode($preLogin->retData);
            $retArray = json_decode($retData, true);
            if (!isset($retArray['needAuthCode']) || $retArray['needAuthCode'] === true) {
                return false;
            }else if ($retArray['needAuthCode'] === false) {
                return true;
            }else {
                return false;
            }
        }else {
            return false;
        }
    }

    public function doLogin($userName, $password, $token) {
        $doLogin = new BaiduTongjiLoginConnection();
        $doLogin->init($this->loginUrl, $this->uuid, $this->account_type);
        $doLoginData = array(
            'username' => $userName,
            'token' => $token,
            'functionName' => 'doLogin',
            'uuid' => $this->uuid,
            'request' => array(
                'password' => $password,
            ),
        );
        $doLogin->POST($doLoginData);

        if ($doLogin->returnCode === 0) {
            $retData = gzdecode($doLogin->retData);
            $retArray = json_decode($retData, true);
            if (!isset($retArray['retcode']) || !isset($retArray['ucid']) || !isset($retArray['st'])) {
                return null;
            }else if ($retArray['retcode'] === 0) {
                return array(
                    'ucid' => $retArray['ucid'],
                    'st' => $retArray['st'],
                );
            }else {
                return null;
            }
        }else {
            return null;
        }
    }

    public function doLogout($userName, $token, $ucid, $st) {
        $doLogout = new BaiduTongjiLoginConnection();
        $doLogout->init($this->loginUrl);
        $doLogoutData = array(
            'username' => $userName,
            'token' => $token,
            'functionName' => 'doLogout',
            'uuid' => $this->uuid,
            'request' => array(
                'ucid' => $ucid,
                'st' => $st,
            ),
        );
        $doLogout->POST($doLogoutData);

        if ($doLogout->returnCode === 0) {
            $retData = gzdecode($doLogout->retData);
            $retArray = json_decode($retData, true);
            if (!isset($retArray['retcode'])) {
                return false;
            }else if ($retArray['retcode'] === 0 ) {
                return true;
            }else {
                return false;
            }
        }else {
            return false;
        }
    }
}
