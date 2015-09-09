<?php

namespace Proyecto404\GigyaBundle;

class GigyaApiClient
{
    private $apiKey;
    private $secretKey;
    private $methodNamespace;

    public function __construct($apiKey, $secretKey)
    {
        $this->apiKey = $apiKey;
        $this->secretKey = $secretKey;
    }

    public function sendRequest($method, $params = array(), $apiDomain = '')
    {
        $request = new \GSRequest($this->apiKey, $this->secretKey, $method);

        foreach ($params as $key => $value) {
            $request->setParam($key, $value);
        }

        if ($apiDomain != '') {
            $request->setAPIDomain($apiDomain);
        }

        return $request->send();
    }

    public function responseHasSignature(\GSResponse $response)
    {
        return $response->getString('UIDSignature','') != '';
    }

    public function validateResponseSignature(\GSResponse $response)
    {
        if ($response->getErrorCode() != 0 || $this->responseHasSignature($response)) {
            return new \Exception('Invalid response');
        }

        $uid = $response->getString('UID','');
        $timestamp = $response->getString('signatureTimestamp','');
        $uidSignature = $response->getString('UIDSignature','');

        return \SigUtils::validateUserSignature($uid, $timestamp, $this->secretKey, $uidSignature);
    }

    public function validateUserSignature($uid, $signatureTimestamp, $uidSignature) {
        return \SigUtils::validateUserSignature($uid, $signatureTimestamp, $this->secretKey, $uidSignature);
    }
}