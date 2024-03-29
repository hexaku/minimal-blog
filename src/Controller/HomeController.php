<?php

namespace App\Controller;

use App\Entity\NewsletterSubscriber;
use App\Form\NewsletterSubscriberType;
use App\Repository\NewsletterSubscriberRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    #[Route('/', name: 'app_home')]
    public function index(PostRepository $postRepository, NewsletterSubscriberRepository $newsletterSubscriberRepository, Request $request): Response
    {
        $newsletterSubscriber = new NewsletterSubscriber();
        $newsletterSubscriberForm = $this->createForm(NewsletterSubscriberType::class, $newsletterSubscriber);

        $newsletterSubscriberForm->handleRequest($request);
        if($newsletterSubscriberForm->isSubmitted() && $newsletterSubscriberForm->isValid()){
            $newsletterSubscriberRepository->add($newsletterSubscriber, true);

            return $this->redirectToRoute('app_home');
        }
    
        // Current home template will display 6 posts maximum
        $posts = $postRepository->findLastXPosts(6);

        return $this->renderForm('index.html.twig', [
            'posts' => $posts,
            'newsletterSubscriberForm' => $newsletterSubscriberForm,
            'buttonLabelNewsletter' => "Subscribe"
        ]);
    }
}