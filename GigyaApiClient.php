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

    public function accounts()
    {
        $this->methodNamespace = 'accounts';

        return $this;
    }

    public function audit()
    {
        $this->methodNamespace = 'audit';

        return $this;
    }

    public function chat()
    {
        $this->methodNamespace = 'chat';

        return $this;
    }

    public function comments()
    {
        $this->methodNamespace = 'comments';

        return $this;
    }

    public function dataStore()
    {
        $this->methodNamespace = 'ds';

        return $this;
    }

    public function gameMechanics()
    {
        $this->methodNamespace = 'gm';

        return $this;
    }

    public function identityStorage()
    {
        $this->methodNamespace = 'ids';

        return $this;
    }

    public function reports()
    {
        $this->methodNamespace = 'reports';

        return $this;
    }

    public function socialize()
    {
        $this->methodNamespace = 'socialize';

        return $this;
    }

    public function __call($name, array $arguments)
    {
        if (false === strpos($name, '.') && !is_null($this->methodNamespace)) {
            $name = $this->methodNamespace . '.' . $name;
        }

        return $this->sendRequest($name, $arguments);
    }
}