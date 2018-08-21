<?php
require_once('DataApiConnection.inc.php');

class ReportService {
    private $apiUrl;
    private $userName;
    private $account_type;
    private $uuid;
    private $token;
    private $ucid;
    private $st;

    public function __construct($apiUrl, $userName, $account_type, $uuid, $token, $ucid, $st) {
        $this->apiUrl = $apiUrl;
        $this->userName = $userName;
        $this->account_type = $account_type;
        $this->uuid = $uuid;
        $this->token = $token;
        $this->ucid = $ucid;
        $this->st = $st;
    }
    
    public function getSiteList() {
        $apiConnection = new DataApiConnection();
        $apiConnection->init($this->apiUrl . '/getSiteList', $this->uuid, $this->ucid);
        $apiConnectionData = array(
            'header' => array(
                'username' => $this->userName,
                'password' => $this->st,
                'token' => $this->token,
                'account_type' => $this->account_type,
            ),
            'body' => null,
        );
        $apiConnection->POST($apiConnectionData);
        return array(
            'header' => $apiConnection->retHead,
            'body' => $apiConnection->retBody,
            'raw' => $apiConnection->retRaw,
        );
    }

    public function getData($parameters) {
        $apiConnection = new DataApiConnection();
        $apiConnection->init($this->apiUrl . '/getData', $this->uuid, $this->ucid);
        $apiConnectionData = array(
            'header' => array(
                'username' => $this->userName,
                'password' => $this->st,
                'token' => $this->token,
                'account_type' => $this->account_type,
            ),
            'body' => $parameters,
        );
        $apiConnection->POST($apiConnectionData);
        return array(
            'header' => $apiConnection->retHead,
            'body' => $apiConnection->retBody,
            'raw' => $apiConnection->retRaw,
        );
    }
}
