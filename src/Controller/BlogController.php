<?php

namespace App\Controller;

use App\Service\VeryBadDesign;
use App\Service\Greeting;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Constraints\DateTime;

/**
* @Route("/blog")
*/
class BlogController //extends AbstractController
{
    /**
    * @var Greeting
    */
    private $greeting;
    /**
    * @var VeryBadDesign
    */
    private $badDesign;

    private $twig;

    private $session;

    public function __construct(\Twig_Environment $twig, SessionInterface $session, RouterInterface $router)
    {
        // $this->greeting = $greeting;
        // $this->badDesign = $badDesign;
        $this->twig = $twig;
        $this->session = $session;
        $this->router = $router;
    }
    /**
    * @Route("/",name="blog_index")
    */
    public function index()
    {
        //dd('works i think');
        //$this->get('app.greeting');
        //$name = $request->get('name')? :'John doe';
        // return $this->render('base.html.twig', ['message'=> $this->greeting->greet(
        //     $name).$this->badDesign->moreGreeting('Every'),'name'=>$name]);

        $html = $this->twig->render('blog/index.html.twig', [
           'posts' => $this->session->get('posts')
        ]);
        //dd($html);
        return new Response($html);
    }
    /**
    * @Route("/add",name="blog_add")
    */
    public function add()
    {
        $posts = $this->session->get('posts');
        $posts[uniqid()] = [
        'title' => 'A random title '.rand(1, 500),
        'text' => 'Some random text nr '.rand(1, 500),
        'date' => new DateTime()
      ];
        $this->session->set('posts', $posts);

        return new RedirectResponse($this->router->generate('blog_index'));
    }
    /**
    * @Route("/show/{id}",name="blog_show")
    */
    public function show($id)
    {
        $posts = $this->session->get('posts');
        if (!$posts || !isset($posts[$id])) {
            //return new RedirectResponse($this->router->generate('blog_index'));
            throw new NotFoundHttpException('Post not found');
        }

        $html = $this->twig->render(
          'blog/post.html.twig',
          [
            'id'=>$id,
            'post'=>$posts[$id]
          ]
        );

        return new Response($html);
    }
    /**
    * @Route("/deleteAll",name="delete_all")
    */
    public function deleteAll()
    {
        $this->session->remove('posts');
        return new RedirectResponse($this->router->generate('blog_index'));
    }
}
