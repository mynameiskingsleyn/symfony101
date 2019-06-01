<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Repository\MicroPostRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\MicroPostType;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
* @Route("/micro-post")
*/
class MicroPostController extends Controller
{
    private $twig;

    private $microPostRepo;

    private $formFactory;

    private $entityManager;

    private $router;

    private $flashBag;

    private $authorizationChecker;

    public function __construct(
        \Twig_Environment $twig,
        MicroPostRepository $microPostRepo,
        FormFactoryInterface $formFactory,
      EntityManagerInterface $entityManager,
      RouterInterface $router,
        FlashBagInterface $flashBag,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->twig = $twig;
        $this->microPostRepo =$microPostRepo;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->entityManager = $entityManager;
        $this->flashBag = $flashBag;
        $this->authorizationChecker = $authorizationChecker;
    }
    /**
    * @Route("/",name="micro_post_index")
    */
    public function index()
    {
        //$posts = $this->microPostRepo->findAll();
        $posts = $this->microPostRepo->findBy([], ['created_at'=>'DESC']);
        $html = $this->twig->render(
          'micro-post/index.html.twig',
          [
            'posts'=>$posts
          ]

      );
        //dd($posts[0]->getCreatedAt());
        return new Response($html);
    }

    /**
    * @Route("/edit/{id}", name="micro_post_edit")
    * @Security("is_granted('edit',microPost)",message="Access denied")
    */
    public function edit(MicroPost $microPost, Request $request)
    {
        //$this->denyUnlessGranted('edit',$microPost);
        // if (!$this->authorizationChecker->isGranted('edit', $microPost)) {
        //     throw new UnauthorizedHttpException();
        // }
        $form = $this->formFactory->create(MicroPostType::class, $microPost);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //dd($microPost);
            //$this->entityManager->persist($microPost);
            $this->entityManager->flush();
            $this->flashBag->add('notice', 'edit saved');
            return new RedirectResponse($this->router->generate('micro_post_index'));
        }
        return new Response(
        $this->twig->render(
            'micro-post/add.html.twig',
                  ['form'=> $form->createView()]
        )
      );
    }
    /**
    * @Route("/delete/{id}",name="micro_post_delete")
    * @Security("is_granted('delete',microPost)",message="Access denied")
    */
    public function delete(MicroPost $microPost)
    {
        $this->entityManager->remove($mocroPost);
        $this->entityManager->flush();
        $this->flashBag->add('notice', 'Micro post was deleted');

        return new RedirectResponse($this->router->generate('micro_post_index'));
    }

    /**
    * @Route("/add",name="micro_post_add")
    * @Security("is_granted('ROLE_USER')")
    */
    public function add(Request $request, TokenStorageInterface $tokenStorage)
    {
        //$user = $this->getUser();
        $user = $tokenStorage->getToken()->getUser();
        $microPost = new MicroPost();
        $microPost->setUser($user);
        //$microPost->setCreatedAt(new \DateTime());
        $form = $this->formFactory->create(MicroPostType::class, $microPost);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //dd($microPost);
            $this->entityManager->persist($microPost);
            $this->entityManager->flush();
            $this->flashBag->add('notice', 'New post added');
            return new RedirectResponse($this->router->generate('micro_post_index'));
        }
        return new Response(
          $this->twig->render(
              'micro-post/add.html.twig',
                    ['form'=> $form->createView()]
          )
        );
    }
    /**
    * @Route("/user/{username}/posts",name="micro_post_user")
    */
    public function userPosts(User $userWithPost)
    {
        //$posts = $this->microPostRepo->findBy(['user'=>$userWithPost], ['created_at'=>'DESC']);
        $posts = $userWithPost->getPosts();
        $html = $this->twig->render(
        'micro-post/index.html.twig',
        [
          'posts'=>$posts
        ]

    );
        //dd($posts[0]->getCreatedAt());
        return new Response($html);
    }

    /**
    * @Route("/{id}", name="micro_post_post")
    */
    public function post(MicroPost $post)
    {
        //$post = $this->microPostRepo->find($id);
        if ($post) {
            return new Response(
          $this->twig->render(
              'micro-post/post.html.twig',
              ['post'=>$post]
            )
          );
        } else {
            return new RedirectResponse($this->router->generate('micro_post_index'));
        }
    }
}
