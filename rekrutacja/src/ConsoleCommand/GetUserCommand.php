<?php

namespace App\ConsoleCommand;
require __DIR__.'/../../vendor/autoload.php';


use App\Entity\Address;
use App\Entity\Company;
use App\Entity\Geo;
use App\Entity\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;


class GetUserCommand extends Command
{

    protected static $defaultName = 'app:get-users';

    private $entityManager;

    private $passwordHasher;


    public function __construct(EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;

        parent::__construct();

    }

    protected function configure()
    {
        $this->setDescription('get users from API and added it to database');

    }

    protected function execute(InputInterface $input, OutputInterface $output): Int
    {
        $request = HttpClient::create();
        $users = $request->request('GET','https://jsonplaceholder.typicode.com/users');

        if($users->getStatusCode() === 200){

            $content = $users->getContent();
            $data = json_decode($content,true);

                foreach ($data as $value) {
                    $entityUser = new User();
                    $entityUser->setName($value['name']);
                    $entityUser->setUsername($value['username']);
                    $passwordToHash = $value['username'] . "test";
                    $hashedPass = $this->passwordHasher->hashPassword($entityUser,$passwordToHash);
                    $entityUser->setPassword($hashedPass);
                    $entityUser->setEmail($value['email']);
                    $entityUser->setPhone($value['phone']);
                    $entityUser->setWebsite($value['website']);


                    $entityAddres = new Address();
                    $entityAddres->setStreet($value['address']['street']);
                    $entityAddres->setSuite($value['address']['suite']);
                    $entityAddres->setCity($value['address']['city']);
                    $entityAddres->setZipcode($value['address']['zipcode']);
                    $entityUser->setAddress($entityAddres);

                    $entityGeo = new Geo();
                    $entityGeo->setLat($value['address']['geo']['lat']);
                    $entityGeo->setLng($value['address']['geo']['lng']);
                    $entityAddres->setGeo($entityGeo);

                    $entityCompany = new Company();
                    $entityCompany->setName($value['company']['name']);
                    $entityCompany->setCatchPhrase($value['company']['catchPhrase']);
                    $entityCompany->setBs($value['company']['bs']);
                    $entityUser->setCompany($entityCompany);

                    $this->entityManager->persist($entityUser);
                    $this->entityManager->flush();

                }
                $output->writeln('Udało się dodać userów do bazy:)');
            return Command::SUCCESS;

        }else{
            return Command::FAILURE;
        }

    }


}