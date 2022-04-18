<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

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
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function register(
        Request $request,
        UserPasswordHasherInterface $hasher,
        UserAuthenticatorInterface $userAuthenticator,
        LoginFormAuthenticator $authenticator,
        EntityManagerInterface $em
    ): Response {

        $form = $this->createForm(UserRegistrationFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();

            $user
                ->setIsActive(true)
                ->setPassword(
                    $hasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

            $em->persist($user);
            $em->flush();

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
            'error' => ''
        ]);
    }
}
