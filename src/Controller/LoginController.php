<?php

// Don't let this controller confuse you. Its job is only to render the form: the form_login authenticator will handle the form submission automatically. If the user submits an invalid email or password, that authenticator will store the error and redirect back to this controller, where we read the error (using AuthenticationUtils) so that it can be displayed back to the user.

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]

    public function index(AuthenticationUtils $authenticationUtils): Response
    {
       // get the login error if there is one
       $error = $authenticationUtils->getLastAuthenticationError();
       // last username entered by the user
       $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }
}
