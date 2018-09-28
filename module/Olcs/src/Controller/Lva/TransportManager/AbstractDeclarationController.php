<?php

namespace OLCS\Controller\Lva\TransportManager;

use Common\Controller\Lva\AbstractTransportManagersController;
use Common\Controller\Lva\Traits\TransportManagerApplicationTrait;
use \Common\Form\Form;
use Olcs\Controller\Lva\Traits\ExternalControllerTrait;
use Common\Controller\Lva\AbstractController;
use Zend\Mvc\MvcEvent;
use \Zend\View\Model\ViewModel as ZendViewModel;

abstract class AbstractDeclarationController extends AbstractController
{
    use ExternalControllerTrait,
        TransportManagerApplicationTrait;

    protected $declarationMarkup;

    protected $tma;

    public function onDispatch(MvcEvent $e)
    {
        $tmaId = (int)$this->params('child_id');
        $this->tma = $this->getTransportManagerApplication($tmaId);
        $this->lva = $this->returnApplicationOrVariation();
        parent::onDispatch($e);
    }

    /**
     * Index action for the lva-transport_manager/tm_declaration and lva-transport_manager/declaration routes
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction(): ZendViewModel
    {
        $tmaId = (int)$this->params('child_id');
        $this->tma = $this->getTransportManagerApplication($tmaId);

        if ($this->getRequest()->isPost()) {
            if ($this->params()->fromPost('content')['isDigitallySigned'] === 'Y') {
                $this->digitalSignatureAction();
            } else {
                $this->physicalSignatureAction();
            }
        }
        return $this->renderDeclarationPage();
    }

    /**
     * @param array $tma
     *
     * @return ZendViewModel
     */
    private function renderDeclarationPage(): ZendViewModel
    {
        $translationHelper = $this->getServiceLocator()->get('Helper\Translation');

        $content = $translationHelper->translate($this->declarationMarkup);
        $params = [
            'content' => $content,
            'tmFullName' => $this->getTmName($this->tma),
            'backLink' => $this->getBackLink()
        ];

        $formHelper = $this->getServiceLocator()->get('Helper\Form');
        $form = $formHelper->createForm('TransportManagerApplicationDeclaration');
        /* @var $form \Common\Form\Form */
        $formHelper->setFormActionFromRequest($form, $this->getRequest());

        $this->alterDeclarationForm($form);

        $this->getServiceLocator()->get('Script')->loadFiles(['tm-lva-declaration']);

        $layout = $this->render('transport-manager-application.declaration', $form, $params);
        /* @var $layout \Zend\View\Model\ViewModel */

        $content = $layout->getChildrenByCaptureTo('content')[0];
        $content->setTemplate('pages/lva-tm-details-action');

        return $layout;
    }

    protected function digitalSignatureAction()
    {
        // write method body
    }

    /**
     * Action for when the operator chooses to physically sign the transport manager application
     *
     * @param array $tma
     *
     * @return \Zend\Http\Response
     */
    private function physicalSignatureAction()
    {
        $response = $this->handlePhysicalSignatureCommand();

        if ($response->isOk()) {
            return $this->redirect()->toRoute(
                "lva-" . $this->returnApplicationOrVariation() . "/transport_manager_details",
                [
                    'child_id' => $this->tma["id"],
                    'application' => $this->tma["application"]["id"]
                ]
            );
        } else {
            $this->flashMessenger()->addErrorMessage('unknown-error');
        }
    }

    protected abstract function handlePhysicalSignatureCommand(): \Common\Service\Cqrs\Response;

    protected abstract function getSubmitActionLabel(): string;

    protected abstract function getBackLink(): string;

    /**
     * Alter declaration form
     *
     * @param Form $form
     *
     * @return void
     */
    protected function alterDeclarationForm(Form $form): void
    {
        $label = $this->getSubmitActionLabel();

        $form->get('form-actions')->get('submit')->setLabel($label);

        if ($this->tma['disableSignatures']) {
            $form->remove('content');
        }
    }

    /**
     * Returns "application" or "variation"
     *
     * @param array $tma
     *
     * @return string
     */
    protected function returnApplicationOrVariation(): string
    {
        if ($this->tma["application"]["isVariation"]) {
            return "variation";
        }
        return "application";
    }
}
