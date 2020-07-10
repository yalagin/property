<?php

namespace CelebrityAgent\Controller\BackOffice;

use CelebrityAgent\Form\Type\LoginType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="backoffice_login")
     * @Template
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request)
    {
        // create a named form with no name to eliminate the name prefix
        $loginForm = $this->get('form.factory')->createNamed(
            '',
            LoginType::class,
            null,
            [
                'action' => $this->generateUrl('backoffice_login_check')
            ]
        );

        // if there was a login error, add it to the form and set
        // it as invalid (which must be done manually since there
        // was no actual form submission, it went to login_check)
        if (($error = $authenticationUtils->getLastAuthenticationError()) instanceof BadCredentialsException) {

            // propagate the supplied username
            $loginForm->get('_username')->setData($error->getToken()->getUsername());
            $loginForm->get('_username')->addError(new FormError('Bad credentials'));

            // construct the view and mark the form invalid
            $loginFormView = $loginForm->createView();
            $loginFormView['_username']->vars['valid'] = false;

        } else {
            $loginFormView = $loginForm->createView();
        }

        return [
            'loginForm' => $loginFormView
        ];
    }

    /**
     * @Route("/login_check", name="backoffice_login_check")
     */
    public function loginCheck()
    {
    }

    /**
     * @Route("/logout", name="backoffice_logout")
     */
    public function logout()
    {
    }
}
