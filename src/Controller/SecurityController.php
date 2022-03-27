<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     *
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException(
            'This method can be blank - it will be intercepted by the logout key on your firewall.'
        );
    }

    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param UserPasswordHasherInterface $hasher
     * @param UserAuthenticatorInterface $userAuthenticator
     * @param LoginFormAuthenticator $authenticator
     *
     * @return Response
     */
    public function register(
        Request $request,
        UserPasswordHasherInterface $hasher,
        UserAuthenticatorInterface $userAuthenticator,
        LoginFormAuthenticator $authenticator
    ): Response {
        if (true === $request->isMethod('POST')
            && $this->isCsrfTokenValid('authenticate', $request->request->get('_csrf_token'))
        ) {
            $user = new User();

            $user
                ->setIsActive(true)
                ->setFirstName($request->request->get('firstName'))
                ->setEmail($request->request->get('email'))
                ->setPassword($hasher->hashPassword($user, $request->request->get('password')));

            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->flush();

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('security/register.html.twig', ['error' => '']);
    }
}
