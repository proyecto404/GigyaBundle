<?php

namespace Proyecto404\GigyaBundle\Security\User;

use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * {@inheritDoc}
 */
interface UserManagerInterface extends UserProviderInterface
{
    public function loadUserByGigyaUserId($gigyaUserId);

    public function createUserWithGigyaAccount($gigyaUserId, \GSObject $userAccountInfo);

    public function updateUserWithGigyaAccount($user, \GSObject $userAccountInfo);
}
