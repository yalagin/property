<?php

namespace CelebrityAgent\Controller\BackOffice;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Display the backoffice dashboard screen.
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/administration", name="backoffice_administration")
     * @IsGranted("ROLE_ADMIN")
     * @Template
     */
    public function administration(Request $request)
    {
        return [];
    }

    /**
     * @Route("/", name="backoffice_dashboard")
     * @Template
     */
    public function index(Request $request)
    {
        return [];
    }
}
