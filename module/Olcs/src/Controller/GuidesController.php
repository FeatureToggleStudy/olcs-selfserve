<?php

/**
 * Guides Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Olcs\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Guides Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class GuidesController extends AbstractActionController
{
    const GUIDE_ADV_OC_GB = 'advertising-your-operating-centre-gb';
    const GUIDE_ADV_OC_NI = 'advertising-your-operating-centre-ni';
    const GUIDE_PRIVACY_AND_COOKIES = 'privacy-and-cookies';
    const GUIDE_TERMS_AND_CONDITIONS = 'terms-and-conditions';

    protected $availableGuides = [
        self::GUIDE_ADV_OC_GB,
        self::GUIDE_ADV_OC_NI,
        self::GUIDE_PRIVACY_AND_COOKIES,
        self::GUIDE_TERMS_AND_CONDITIONS,
    ];

    public function indexAction()
    {
        $guide = $this->params('guide');

        if (!in_array($guide, $this->availableGuides)) {
            return $this->notFoundAction();
        }

        $view = new ViewModel(['guide' => $guide]);
        $view->setTemplate('pages/guides/default');

        return $view;
    }
}
