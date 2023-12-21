<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/lista', name: 'app_list')]
    public function index(PostRepository $postRepository): Response
    {
        $getPost = $postRepository->findAll();

        return $this->render('post/list.html.twig', [
            'posts' => $getPost,
        ]);
    }

    #[Route('/posts', name: 'app_posts', methods: 'GET')]
    public function showPost(PostRepository $postRepository): Response
    {
        $getPost = $postRepository->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $getPost,
        ]);
    }

    #[Route('/lists/delete/{id}', name: 'app_list_hm')]
    public function delete(EntityManagerInterface $entityManager, PostRepository $postRepository, Request $request,int $id): Response
    {
        if($request->isMethod('GET')){
            $post = $postRepository->find(['id'=>$id]);
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_list');
    }


}
