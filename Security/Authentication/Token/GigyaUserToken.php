<?php

namespace Proyecto404\GigyaBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

/**
 * {@inheritDoc}
 */
class GigyaUserToken extends AbstractToken
{
    private $providerKey;

    /**
     * {@inheritDoc}
     */
    public function __construct($providerKey, $gigyaUserId = '', array $roles = array())
    {
        parent::__construct($roles);

        $this->setUser($gigyaUserId);

        if (!empty($gigyaUserId)) {
            $this->setAuthenticated(true);
        }

        $this->providerKey = $providerKey;
    }

    public function getCredentials()
    {
        return '';
    }

    public function getProviderKey()
    {
        return $this->providerKey;
    }

    public function serialize()
    {
        return serialize(array($this->providerKey, parent::serialize()));
    }

    public function unserialize($str)
    {
        list($this->providerKey, $parentStr) = unserialize($str);
        parent::unserialize($parentStr);
    }
}
