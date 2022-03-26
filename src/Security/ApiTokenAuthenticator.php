<?php

namespace App\Security;

use App\Entity\ApiToken;
use App\Entity\User;
use App\Repository\ApiTokenRepository;
use App\Service\ApiLogger;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiTokenAuthenticator extends AbstractAuthenticator
{
    /**
     * @var ApiTokenRepository
     */
    private ApiTokenRepository $apiTokenRepository;

    /**
     * @var ApiLogger
     */
    private ApiLogger $logger;

    /**
     * @var ApiToken|null
     */
    private ?ApiToken $apiToken = null;

    public function __construct(ApiTokenRepository $apiTokenRepository, ApiLogger $logger)
    {
        $this->apiTokenRepository = $apiTokenRepository;
        $this->logger = $logger;
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization')
            && 0 === strpos($request->headers->get('Authorization'), 'Bearer ');
    }

    public function authenticate(Request $request): PassportInterface
    {
        $apiToken = substr($request->headers->get('Authorization'), 7);

        $apiToken = $this->apiTokenRepository->findOneBy(['token' => $apiToken]);

        if (null === $apiToken) {
            throw new CustomUserMessageAuthenticationException('Invalid Token.');
        }

        if (true === $apiToken->isExpired()) {
            throw new CustomUserMessageAuthenticationException('Token is expired.');
        }

        $this->apiToken = $apiToken;

        $user = $apiToken->getUser();

        return new SelfValidatingPassport(new UserBadge($user->getEmail()));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $this->logger->info('Вход по API', [
            'user' => $token->getUser()->getUserIdentifier(),
            'token' => $this->apiToken->getToken(),
            'route' => $request->attributes->get('_route'),
            'url' => $request->getUri()
        ]);

        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}
