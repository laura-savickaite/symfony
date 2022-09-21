<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserTypeController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function update(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        // dump($this->getUser());
        // die;

        $user = $this->getUser();
        // $login = $user->getLogin();

        // dump ($login);
        // die;

        if(!($user)) {
            return $this->redirectToRoute("app_login");
        }

        $userform = $this->createForm(UserType::class, $user,  [
            'action' => $request->getRequestUri()
        ]);
        $userform->handleRequest($request);

        $password = $user->getPassword();

        if($userform->isSubmitted() && $userform->isValid()) {
            if(!empty($password)) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $userform->get('plainPassword')->getData()
                    )
                ); 

                $entityManager->persist($user);
                $entityManager->flush();
            }

                $entityManager->persist($user);
                $entityManager->flush();
            
        }

        // dd($userform->createView());
        return $this->render('profil/update.html.twig', [
            'updateForm' => $userform->createView(),
        ]);

    }
}