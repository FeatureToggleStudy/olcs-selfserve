<?php

/**
 * External Type Of Licence Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Olcs\Controller\Lva\Application;

use Common\Controller\Lva\Application\AbstractTypeOfLicenceController;
use Dvsa\Olcs\Transfer\Command\Application\CreateApplication;
use Dvsa\Olcs\Transfer\Command\Application\UpdateTypeOfLicence;
use Zend\Form\Form;
use Common\View\Model\Section;
use Olcs\Controller\Lva\Traits\ApplicationControllerTrait;
use Zend\Http\Response;

/**
 * External Type Of Licence Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class TypeOfLicenceController extends AbstractTypeOfLicenceController
{
    use ApplicationControllerTrait;

    protected $location = 'external';
    protected $lva = 'application';

    public function confirmationAction()
    {
        $request = $this->getRequest();

        if ($request->isPost()) {

            $query = (array)$this->params()->fromQuery();

            $dto = UpdateTypeOfLicence::create(
                [
                    'id' => $this->getIdentifier(),
                    'version' => $query['version'],
                    'operatorType' => $query['operator-type'],
                    'licenceType' => $query['licence-type'],
                    'niFlag' => $query['operator-location'],
                    'confirm' => true
                ]
            );

            $command = $this->getServiceLocator()->get('TransferAnnotationBuilder')
                ->createCommand($dto);

            /** @var \Common\Service\Cqrs\Response $response */
            $response = $this->getServiceLocator()->get('CommandService')->send($command);

            if ($response->isOk()) {
                return $this->redirect()->toRouteAjax(
                    'lva-application',
                    ['application' => $response->getResult()['id']['application']]
                );
            }

            $this->getServiceLocator()->get('Helper\FlashMessenger')
                ->addErrorMessage('unknown-error');

            return $this->redirect()->toRouteAjax(null, ['action' => null], [], true);
        }

        $formHelper = $this->getServiceLocator()->get('Helper\Form');
        $form = $formHelper->createForm('GenericConfirmation');
        $formHelper->setFormActionFromRequest($form, $this->getRequest());

        return $this->render(
            'application_type_of_licence_confirmation',
            $form,
            ['sectionText' => 'application_type_of_licence_confirmation_subtitle']
        );
    }

    /**
     * Render the section
     *
     * @param string $titleSuffix
     * @param \Zend\Form\Form $form
     * @return \Common\View\Model\Section
     */
    protected function renderCreateApplication($titleSuffix, Form $form = null)
    {
        return new Section(
            [
                'title' => 'lva.section.title.' . $titleSuffix, 'form' => $form,
                'stepX' => '1',
            ]
        );
    }

    /**
     * Create application action
     */
    public function createApplicationAction()
    {
        if ($this->isButtonPressed('cancel')) {
            return $this->redirect()->toRouteAjax('dashboard');
        }

        $request = $this->getRequest();

        $form = $this->getServiceLocator()->get('FormServiceManager')
            ->get('lva-application-type-of-licence')
            ->getForm();

        $form->get('form-actions')->remove('saveAndContinue')
            ->get('save')->setLabel('continue.button')->setAttribute('class', 'action--primary large');

        if ($request->isPost()) {
            $data = (array)$request->getPost();

            $form->setData($data);

            if ($form->isValid()) {

                $dto = CreateApplication::create(
                    [
                        'organisation' => $this->getCurrentOrganisationId(),
                        'niFlag' => $data['type-of-licence']['operator-location'],
                        'operatorType' => $data['type-of-licence']['operator-type'],
                        'licenceType' => $data['type-of-licence']['licence-type']
                    ]
                );

                $command = $this->getServiceLocator()->get('TransferAnnotationBuilder')->createCommand($dto);

                /** @var \Common\Service\Cqrs\Response $response */
                $response = $this->getServiceLocator()->get('CommandService')->send($command);

                if ($response->isOk()) {
                    return $this->goToOverview($response->getResult()['id']['application']);
                }

                if ($response->isClientError()) {
                    $this->mapErrors($form, $response->getResult()['messages']);
                } else {
                    $this->getServiceLocator()->get('Helper\FlashMessenger')
                        ->addErrorMessage('unknown-error');
                }
            }
        }

        $this->getServiceLocator()->get('Script')->loadFile('type-of-licence');

        return $this->renderCreateApplication('type_of_licence', $form);
    }
}
