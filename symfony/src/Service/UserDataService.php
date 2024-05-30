<?php
namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserDataService
{
    public function __construct(
        private EntityManagerInterface      $entityManager,
        private UserPasswordHasherInterface $passwordEncoder
    ) {}

    /**
     * Create user
     *
     * @param array $userData
     * @return void
     */
    public function createUser(array $userData): void
    {
        $user = new User();
        $user->setEmail($userData['email']);
        $user->setRoles([$userData['role']]);
        $user->setPassword($this->passwordEncoder->hashPassword($user, $userData['password']));

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * Edit user
     *
     * @param int $userId
     * @param array $userData
     * @return void
     */
    public function editUser(int $userId, array $userData): void
    {
        $user = $this->entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            throw new \InvalidArgumentException("User with ID $userId not found.");
        }

        $user->setEmail($userData['email']);
        $user->setRoles([$userData['role']]);
        if (isset($userData['password'])) {
            $user->setPassword($this->passwordEncoder->hashPassword($user, $userData['password']));
        }

        $this->entityManager->flush();
    }

    /**
     * Get user by ID
     *
     * @param int $userId
     * @return array|null
     */
    public function getUserById(int $userId): ?array
    {
        $user = $this->entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            return null;
        }
        return [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'role' => $user->getRoles(),
        ];
    }

    /**
     * Delete user by ID
     *
     * @param int $userId
     * @return bool
     */
    public function deleteUserById(int $userId): bool
    {
        try {
            $user = $this->entityManager->getRepository(User::class)->find($userId);

            if (!$user) {
                throw new \InvalidArgumentException("User with ID $userId not found.");
            }

            $this->entityManager->remove($user);
            $this->entityManager->flush();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}