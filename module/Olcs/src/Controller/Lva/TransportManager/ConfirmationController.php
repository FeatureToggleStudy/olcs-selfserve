<?php

namespace OLCS\Controller\Lva\TransportManager;

use Common\Controller\Lva\AbstractTransportManagersController;
use Common\RefData;
use Olcs\Controller\Lva\Traits\ApplicationControllerTrait;

class ConfirmationController extends AbstractTransportManagersController
{
    use ApplicationControllerTrait;

    // todo make sure url is not accessible to every ss user

    /**
     * index action for /transport-manager/:TmaId/confirmation route
     *
     * @param array $tma TM application
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $tmaId = (int)$this->params('application');
        $tma = $this->getTmaDetails($tmaId);

        $this->getCurrentUser();
        $translationHelper = $this->getServiceLocator()->get('Helper\Translation');

        $confirmationMarkup = $tma["isOwner"] === "N" ? 'markup-tma-confirmation-tm' :
            'markup-tma-confirmation-operator';

        $params = [
            'content' => $translationHelper->translateReplace($confirmationMarkup,
                [$this->getCurrentUserFullName(), $this->getVerifySignatureDate($tma), $this->getBacklink()]),
            'tmFullName' => $this->getTmFullName($tma),
        ];

        return $this->renderTmAction(null, null, $tma, $params);
    }

    /**
     * Render the Transport manager application confirmation pages
     *
     * @param string            $title  Title
     * @param \Common\Form\Form $form   Form
     * @param array             $tma    TM application
     * @param array             $params Params
     *
     * @return \Zend\View\Model\ViewModel
     */
    private function renderTmAction($title, $form, $tma, $params)
    {
        $defaultParams = [
            'tmFullName' => $this->getTmFullName($tma),
            'backLink' => $this->getBacklink(),
        ];

        $params = array_merge($defaultParams, $params);

        $layout = $this->render($title, $form, $params);
        /* @var $layout \Zend\View\Model\ViewModel */

        $content = $layout->getChildrenByCaptureTo('content')[0];
        $content->setTemplate('pages/confirmation');

        return $layout;
    }

    /**
     * Get the URL/link to go back
     *
     * @return string
     */
    private function getBacklink()
    {
        if ($this->isTransportManagerRole()) {
            return $this->url()->fromRoute('dashboard');
        } else {
            return $this->url()->fromRoute(
                "lva-{$this->lva}/transport_managers",
                ['application' => $this->getIdentifier()],
                [],
                false
            );
        }
    }

    /**
     * is the logged in user just TM, eg not an admin
     *
     * @return bool
     */
    private function isTransportManagerRole()
    {
        return ($this->isGranted(RefData::PERMISSION_SELFSERVE_TM_DASHBOARD) &&
            !$this->isGranted(RefData::PERMISSION_SELFSERVE_LVA));
    }

    protected function getTmaDetails($tmaId)
    {
        /* @var $response \Common\Service\Cqrs\Response */
        $response = $this->handleQuery(
            \Dvsa\Olcs\Transfer\Query\TransportManagerApplication\GetDetails::create(['id' => $tmaId])
        );

        // this is need for use in the processFiles callbacks
        $this->tma = $response->getResult();

        return $response->getResult();
    }

    private function getTmFullName($tma)
    {
        return trim($tma['transportManager']['homeCd']['person']['forename'] . ' '
            . $tma['transportManager']['homeCd']['person']['familyName']);
    }

    private function getVerifySignatureDate($tma)
    {
        $unixTimeStamp = strtotime($tma['digitalSignature']['createdOn']);
        return date("j M Y", $unixTimeStamp);
    }

    private function getCurrentUserFullName()
    {
        $user = $this->currentUser()->getUserData();
        return trim($user["contactDetails"]["person"]["forename"] . ' '
            . $user["contactDetails"]["person"]["familyName"]);
    }

}