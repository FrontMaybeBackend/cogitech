<?php

namespace App\ConsoleCommand;
require __DIR__.'/../../vendor/autoload.php';


use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use http\Client\Response;
use PhpParser\Node\Expr\Cast\Int_;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;


class GetPostCommand extends  Command
{
    protected static $defaultName = 'app:get-posts';

    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
       $this->setDescription('get posts from API and added it to database');

    }

    protected function execute(InputInterface $input, OutputInterface $output): Int
    {
        $request = HttpClient::create();
        $posts = $request->request('GET','https://jsonplaceholder.typicode.com/posts');

        if($posts->getStatusCode() === 200){

            $content = $posts->getContent();
            $data = json_decode($content,true);

            foreach($data as $value) {
                $newPost = new Post();
                $postId = $value['userId'];
                $userId = $this->entityManager->getRepository(User::class)->find($postId);
                $userName = $userId->getName();
                $name = $userId->getUsername();


                if(!$userName){
                  $output->writeln('Najpierw dodaj userow!');
                  return Command::FAILURE;

                }

                $newPost->setUsername($userName);
                $newPost->setName($name);
                $newPost->setUserId($userId);
                $newPost->setTitle($value['title']);
                $newPost->setBody($value['body']);


                $this->entityManager->persist($newPost);
                $this->entityManager->flush();
            }
            $output->writeln('Udało się dodać posty do bazy:)');
            return Command::SUCCESS;

        }else{
            $output->writeln('Nie Udało się dodać postów do bazy, najpierw dodaj userów :)');
            return Command::FAILURE;
        }

    }


}