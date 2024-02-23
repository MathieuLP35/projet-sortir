<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use League\Csv\Reader;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ImportUsersCommand extends Command
{
    protected static $defaultName = 'app:import-users';
    private $entityManager;
    private $passwordHasher;
    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Load the CSV file in the public directory
        $csv = Reader::createFromPath('./public/users.csv', 'r');
        $csv->setHeaderOffset(0); // assuming the first row contains the column headers

        foreach ($csv as $record) {
            // Create user logic here using Symfony's UserManager
            $user = new User();
            $user->setUsername($record['username']);
            $user->setFirstname($record['firstname']);
            $user->setName($record['name']);
            $user->setEmail($record['email']);
            $user->setPassword($this->passwordHasher->hashPassword($user, $record['password']));
            $user->setPhone($record['phone']);
            $user->setRoles(['ROLE_USER']);
            $user->setIsActive(true);
            
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        $output->writeln('Users imported successfully.');

        return Command::SUCCESS;
    }
}