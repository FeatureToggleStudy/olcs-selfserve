<?php

/**
 * TransportManagers Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 */
namespace Olcs\Controller\Application;

use Common\View\Model\Section;

/**
 * TransportManagers Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 */
class TransportManagersController extends AbstractApplicationController
{
    public function indexAction()
    {
        return new Section(
            array(
                'title' => 'Transport managers'
            )
        );
    }
}
