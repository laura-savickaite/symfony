<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserTypeController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function update(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        // dump($this->getUser());
        // die;

        $user = $this->getUser();

        if(!($user)) {
            return $this->redirectToRoute(('security.login'));
        }

        // if($this->getUser() === $user) {
        //     return $this->redirectToRoute(('security.login'));
        // }

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);


        return $this->render('profil/update.html.twig', [
            'updateForm' => $form->createView()
        ]);


        // // usually you'll want to make sure the user is authenticated first,
        // // see "Authorization" below
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // // returns your User object, or null if the user is not authenticated
        // // use inline documentation to tell your editor your exact User class
        // /** @var \App\Entity\User $user */
        // $user = $this->getUser();

        // // Call whatever methods you've added to your User class
        // // For example, if you added a getFirstName() method, you can use that.
        // return new Response('Well hi there '.$user->getLogin());
    }
}