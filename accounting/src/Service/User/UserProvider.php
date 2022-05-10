<?php

namespace Accounting\Service\User;

use Accounting\Entity\User;
use Accounting\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', \get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getPublicId());
    }

    public function supportsClass(string $class)
    {
        return $class === User::class;
    }

    public function loadUserByUsername(string $publicId)
    {
        return $this->userRepository->findOneBy([
            'publicId' => $publicId,
        ]);
    }
}
