<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    
    /**
     * passwordEncoder
     *
     * @var mixed
     */
    private $passwordEncoder;
    
    /**
     * __construct
     *
     * @param  mixed $passwordEncoder
     * @return void
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }



    /**
     * listAction
     *
     * @param  mixed $userRepository
     * @return void
     * 
     * @Route("/users", name="user_list", methods={"GET"})
     */
    public function listAction(UserRepository $userRepository)
    {
        if ($this->getUser()->getRole() !== 'ROLE_ADMIN') {
            $this->addFlash('error', 'Veuillez vous connecter');
            return $this->redirectToRoute('login');
        }
        return $this->render('user/list.html.twig', [
            'users' => $userRepository->findAll()
        ]);
    }
   
    /**
     * createAction
     *
     * @param  mixed $request
     * @param  mixed $passwordEncoder
     * @return void
     * 
     * @Route("/users/create", name="user_create", methods={"GET", "POST"})
     */
    public function createAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $password = $this->passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");
            return $this->redirectToRoute('user_list');
        }
        return $this->render('user/user_form.html.twig', ['form' => $form->createView()]);
    }
 
    /**
     * editAction
     *
     * @param  mixed $user
     * @param  mixed $request
     * @param  mixed $passwordEncoder
     * @return void
     * 
     * @Route("/users/{id}/edit", name="user_edit", methods={"GET", "POST"})
     */
    public function editAction(User $user, Request $request)
    {
        if ($this->getUser() === $user || $this->getUser()->getRole() === 'ROLE_ADMIN') {
            $form = $this->createForm(UserType::class, $user);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $password = $this->passwordEncoder->encodePassword($user, $user->getPassword());
                $user->setPassword($password);

                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('success', "L'utilisateur a bien été modifié");
                return $this->redirectToRoute('user_list');
            }
            return $this->render('user/user_form.html.twig', ['form' => $form->createView(), 'user' => $user]);
        }
        $this->addFlash('error', ' Vous n\'avez pas accès à ce profil');
        return $this->redirectToRoute('user_list');
    }
}
