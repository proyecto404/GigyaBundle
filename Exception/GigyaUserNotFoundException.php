<?php

namespace Proyecto404\GigyaBundle\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * {@inheritDoc}
 */
class GigyaUserNotFoundException extends AuthenticationException
{
    private $gigyaUserId;

    /**
     * {@inheritDoc}
     */
    public function getMessageKey()
    {
        return 'The Gigya user could not be retrieved from the session.';
    }

    /**
     * Get the gigyaUserId.
     *
     * @return string
     */
    public function getGigyaUserId()
    {
        return $this->gigyaUserId;
    }

    /**
     * Set the gigyaUserId.
     *
     * @param string $gigyaUserId
     */
    public function setGigyaUserId($gigyaUserId)
    {
        $this->gigyaUserId = $gigyaUserId;
    }

    /**
     * {@inheritDoc}
     */
    public function serialize()
    {
        return serialize(array(
            $this->gigyaUserId,
            parent::serialize(),
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function unserialize($str)
    {
        list($this->gigyaUserId, $parentData) = unserialize($str);

        parent::unserialize($parentData);
    }
}
