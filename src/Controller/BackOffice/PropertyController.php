<?php


namespace CelebrityAgent\Controller\BackOffice;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Display the property dashboard screen.
 * @IsGranted("ROLE_USER")
 */
class PropertyController extends AbstractController
{
    /**
     * @Route("/property", name="backoffice_property")
     * @Template
     * @param Request $request
     * @return array
     */
    public function index(Request $request)
    {
        return ['fluid_container'=>'container'];
    }
}