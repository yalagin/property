<?php

namespace CelebrityAgent\Twig;

use CelebrityAgent\Service\ApplicationService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Enable ApplicationService functionality in Twig templates.
 */
class ApplicationServiceExtension extends AbstractExtension
{
    /**
     * @var ApplicationService
     */
    private $applicationService;

    /**
     * Constructor.
     *
     * @param ApplicationService $applicationService An ApplicationService instance.
     */
    public function __construct(ApplicationService $applicationService)
    {
        $this->applicationService = $applicationService;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('is_administrator', [$this->applicationService, 'isAdministrator']),
            new TwigFunction('is_system_administrator', [$this->applicationService, 'isSystemAdministrator']),
            new TwigFunction('user_is_granted', [$this->applicationService, 'userHasRole'])
        ];
    }
}
