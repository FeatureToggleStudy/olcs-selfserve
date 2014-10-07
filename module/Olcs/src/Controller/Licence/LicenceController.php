<?php

/**
 * Licence Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 */
namespace Olcs\Controller\Licence;

use Olcs\View\Model\Licence\Overview;

/**
 * Licence Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 */
class LicenceController extends AbstractLicenceController
{
    /**
     * Licence overview
     */
    public function indexAction()
    {
        $data = $this->getEntityService('Licence')->getOverview(
            $this->params('id')
        );
        return new Overview($data, []);
    }
}
