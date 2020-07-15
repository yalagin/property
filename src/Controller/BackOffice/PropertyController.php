<?php


namespace CelebrityAgent\Controller\BackOffice;


use CelebrityAgent\Entity\Property;
use CelebrityAgent\Repository\PropertyRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

/**
 * Display the property dashboard screen.
 * @IsGranted("ROLE_USER")
 * @Route("/property")
 */
class PropertyController extends AbstractController
{
    /**
     * @Route("/{id}", name="backoffice_property")
     * @Template
     * @param Property $property
     * @return array
     */
    public function index(Property $property)
    {
        // todo add pagination for activities
        $property->getActivities();
        return ['property'=> $property];
    }
}