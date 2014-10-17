<?php

/**
 * Operating Centres Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Olcs\Controller\Application;

use Common\Controller\Traits\Lva;
use Common\Service\Entity\LicenceEntityService;
use Zend\Form\Form;

/**
 * Operating Centres Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class OperatingCentresController extends AbstractApplicationController
{
    use Lva\OperatingCentresTrait;

    protected function getIdentifier()
    {
        return $this->getApplicationId();
    }
}
