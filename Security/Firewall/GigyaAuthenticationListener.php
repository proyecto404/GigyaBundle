<?php

namespace Proyecto404\GigyaBundle\Security\Firewall;

use Proyecto404\GigyaBundle\Exception\GigyaUserNotFoundException;
use Proyecto404\GigyaBundle\GigyaApiClient;
use Proyecto404\GigyaBundle\Security\Authentication\Token\GigyaUserToken;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface;

/**
 * Gigya authentication listener.
 */
class GigyaAuthenticationListener extends AbstractAuthenticationListener
{
    protected $gigyaApiClient;

    public function __construct(
        SecurityContextInterface $securityContext,
        AuthenticationManagerInterface $authenticationManager,
        SessionAuthenticationStrategyInterface $sessionStrategy,
        HttpUtils $httpUtils,
        $providerKey,
        AuthenticationSuccessHandlerInterface $successHandler,
        AuthenticationFailureHandlerInterface $failureHandler,
        GigyaApiClient $gigyaApiClient,
        array $options = array(),
        LoggerInterface $logger = null,
        EventDispatcherInterface $dispatcher = null
    ) {
        $this->gigyaApiClient = $gigyaApiClient;

        parent::__construct($securityContext, $authenticationManager, $sessionStrategy, $httpUtils, $providerKey, $successHandler, $failureHandler, $options, $logger, $dispatcher);
    }

    protected function requiresAuthentication(Request $request)
    {
        return $this->httpUtils->checkRequestPath($request, $this->options['check_path']);
    }

    protected function attemptAuthentication(Request $request)
    {
        $uid = $request->request->get('UID');
        $uidSignature = $request->request->get('UIDSignature');
        $signatureTimestamp = $request->request->get('signatureTimestamp');

        // Invalid signature
        if (!$this->gigyaApiClient->validateUserSignature($uid, $signatureTimestamp, $uidSignature)) {
            throw new GigyaUserNotFoundException();
        }

        return $this->authenticationManager->authenticate(new GigyaUserToken($this->providerKey, $uid, array()));
    }
}
