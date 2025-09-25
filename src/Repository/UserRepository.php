<?php

namespace App\Repository;

use App\Model\User;

/**
 * A safe-to-share repository skeleton for managing users.
 * No connection to a private database or sensitive entities.
 */
class UserRepository
{
    /**
     * Simulates upgrading a user's password.
     *
     * @param User $user
     * @param string $newHashedPassword
     */
    public function upgradePassword(User $user, string $newHashedPassword): void
    {
        // Mock logic: just assign the password in memory
        $user->setPassword($newHashedPassword);
        // No database persistence here
    }

    /**
     * Example method for fetching all users.
     *
     * @return User[]
     */
    public function findAll(): array
    {
        return [];
    }

    /**
     * Example method for filtering users.
     *
     * @return User[]
     */
    public function findByExampleField($value): array
    {
        return [];
    }

    /**
     * Example method for fetching a single user.
     *
     * @return User|null
     */
    public function findOneBySomeField($value): ?User
    {
        return null;
    }
}
