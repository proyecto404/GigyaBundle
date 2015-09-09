<?php

namespace Proyecto404\GigyaBundle\Security\Authentication\Provider;

use Proyecto404\GigyaBundle\Exception\GigyaUserNotFoundException;
use Proyecto404\GigyaBundle\Security\Authentication\Token\GigyaUserToken;
use Proyecto404\GigyaBundle\Security\User\UserManagerInterface;
use Proyecto404\GigyaBundle\GigyaApiClient;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationServiceException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * {@inheritDoc}
 */
class GigyaAuthenticationProvider implements AuthenticationProviderInterface
{
    /**
     * @var GigyaApiClient
     */
    protected $gigyaApiClient;
    protected $providerKey;
    protected $userProvider;
    protected $userChecker;

    public function __construct($providerKey, GigyaApiClient $gigyaApiClient, UserProviderInterface $userProvider = null, UserCheckerInterface $userChecker = null)
    {
        if (null !== $userProvider && null === $userChecker) {
            throw new \InvalidArgumentException('$userChecker cannot be null, if $userProvider is not null.');
        }

        if (!$userProvider instanceof UserManagerInterface) {
            throw new \InvalidArgumentException('The $userProvider must implement UserManagerInterface');
        }

        $this->providerKey = $providerKey;
        $this->gigyaApiClient = $gigyaApiClient;
        $this->userProvider = $userProvider;
        $this->userChecker = $userChecker;
    }

    public function authenticate(TokenInterface $token)
    {
        if (!$this->supports($token)) {
            return null;
        }

        $user = $token->getUser();

        // If token has valid user, check post validations, recreate token and return it
        if ($user instanceof UserInterface) {
            $this->userChecker->checkPostAuth($user);

            $newToken = new GigyaUserToken($this->providerKey, $user, $user->getRoles());
            $newToken->setAttributes($token->getAttributes());

            return $newToken;
        }

        if (!$user) {
            throw new GigyaUserNotFoundException();
        }

        try {
            $response = $this->gigyaApiClient->sendRequest('accounts.getAccountInfo', array('UID' => $user, 'extraProfileFields' => 'phones'));
        } catch (\Exception $e) {
            throw new GigyaUserNotFoundException();
        }

        if ($response->getErrorCode() != 0) {
            throw new GigyaUserNotFoundException();
        }

        $newToken = $this->createAuthenticatedToken($user, $response);
        $newToken->setAttributes($token->getAttributes());

        return $newToken;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof GigyaUserToken && $this->providerKey === $token->getProviderKey();
    }

    protected function createAuthenticatedToken($gigyaUserId, \GSResponse $accountInfoResponse)
    {
        if (null === $this->userProvider) {
            return new GigyaUserToken($this->providerKey, $gigyaUserId, array());
        }

        $user = $this->userProvider->loadUserByGigyaUserId($gigyaUserId);
        if ($user == null) {
            $user = $this->userProvider->createUserWithGigyaAccount($gigyaUserId, $accountInfoResponse->getData());
        } else {
            $this->userProvider->updateUserWithGigyaAccount($user, $accountInfoResponse->getData());
        }

        if ($user instanceof UserInterface) {
            $this->userChecker->checkPreAuth($user);
            $this->userChecker->checkPostAuth($user);
        } else {
            throw new AuthenticationServiceException('The user provider must return a UserInterface object.');
        }

        return new GigyaUserToken($this->providerKey, $user, $user->getRoles());
    }
}
