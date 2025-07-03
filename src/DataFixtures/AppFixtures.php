<?php
namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    // Inject the password hasher in the constructor
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('admin@example.com');

        // Log the execution
        dump('Creating admin user...');

        $hashedPassword = $this->passwordHasher->hash('password'); // Replace with your desired password
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_ADMIN']); // Assign role

        $manager->persist($user);
        $manager->flush();

        // Log after saving
        dump('Admin user created and saved!');
    }
}