<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminCreateCommand extends Command
{
    protected static $defaultName = 'app:admin-create';
    protected static $defaultDescription = 'Add a short description for your command';

    /**
     * @var UserPasswordHasherInterface
    */
    private $passwordEncoder;

    /**
     * @var EntityManagerInterface
    */
    private $em;

    public function __construct(UserPasswordHasherInterface $passwordEncoder, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->passwordEncoder = $passwordEncoder;
        $this->em = $em;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Admin email')
            ->addArgument('password', InputArgument::REQUIRED, 'Password')
//            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $pasPlain = $input->getArgument('password');
        $email = $input->getArgument('email');

        $adminUser = new User($email);
        $pasHash = $this->passwordEncoder->hashPassword($adminUser, $pasPlain);
        $adminUser->setRoles(['ROLE_ADMIN']);
        $adminUser->setPassword($pasHash);

        try {
            $this->em->persist($adminUser);
            $this->em->flush();

            $io->success('User with ADMIN privileges created!');
            return Command::SUCCESS;
        } catch (UniqueConstraintViolationException $ex) {
            $io->error(sprintf('User with email \'%s\' already exists', $email));
            return Command::FAILURE;
        }
    }
}
