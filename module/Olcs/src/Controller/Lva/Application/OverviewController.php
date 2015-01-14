<?php

/**
 * Application Overview Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Olcs\Controller\Lva\Application;

use Common\Controller\Lva\AbstractController;
use Olcs\View\Model\Application\ApplicationOverview;
use Olcs\Controller\Lva\Traits\ApplicationControllerTrait;
use Common\Service\Entity\ApplicationEntityService;

/**
 * Application Overview Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class OverviewController extends AbstractController
{
    use ApplicationControllerTrait;

    protected $lva = 'application';
    protected $location = 'external';

    /**
     * Application overview
     */
    public function indexAction()
    {
        $applicationId = $this->getApplicationId();

        if (!$this->checkAccess($applicationId)) {
            return $this->redirect()->toRoute('dashboard');
        }

        $data = $this->getServiceLocator()->get('Entity\Application')->getOverview($applicationId);
        $data['idIndex'] = $this->getIdentifierIndex();

        $sections = $this->setEnabledAndCompleteFlagOnSections(
            $this->getAccessibleSections(false),
            $data['applicationCompletions'][0]
        );

        $fee = $this->getServiceLocator()->get('Entity\Fee')
            ->getLatestOutstandingFeeForApplication($applicationId);

        $formHelper = $this->getServiceLocator()->get('Helper\Form');

        $form = $formHelper->createForm('Lva\PaymentSubmission');

        $form->setData($data);

        if ($data['status']['id'] == ApplicationEntityService::APPLICATION_STATUS_NOT_SUBMITTED) {

            $action = $this->url()->fromRoute(
                'lva-application/payment',
                [$this->getIdentifierIndex() => $applicationId]
            );
            $form->setAttribute('action', $action);

            if ($fee) {
                // show fee amount
                $feeAmount = number_format($fee['amount'], 2);
                $form->get('amount')->setTokens([0 => $feeAmount]);
            } else {
                // if no fee, change submit button text
                $formHelper->remove($form, 'amount');
                $form->get('submitPay')->setLabel('submit-application.button');
            }

            if (!$this->isApplicationComplete($sections)) {
                $formHelper->disableElement($form, 'submitPay');
            }

        } else {
            // remove submit button and amount
            $formHelper->remove($form, 'amount');
            $formHelper->remove($form, 'submitPay');
        }

        return new ApplicationOverview($data, $sections, $form);
    }

    private function isApplicationComplete($sections)
    {
        foreach ($sections as $section) {
            if ($section['enabled'] && !$section['complete']) {
                return false;
            }
        }
        return true;
    }
}
