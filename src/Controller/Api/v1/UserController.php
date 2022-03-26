<?php

namespace App\Controller\Api\v1;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/api/v1/user", name="app_api_v1_user")
     */
    public function index(): Response
    {
        return $this->json($this->getUser());
    }
}
