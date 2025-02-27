<?php

namespace Olcs\Controller\Lva\Traits;

use Common\Controller\Lva\Traits\CommonApplicationControllerTrait;
use Common\View\Model\Section;
use Dvsa\Olcs\Transfer\Query\Application\Application as ApplicationQry;
use Olcs\Logging\Log\Logger;
use Zend\Form\Form;
use Zend\View\Model\ViewModel;
use Common\RefData;

/**
 * EXTERNAL Abstract Application Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
trait ApplicationControllerTrait
{
    use ExternalControllerTrait,
        CommonApplicationControllerTrait;

    /**
     * Hook into the dispatch before the controller action is executed
     *
     * @return mixed
     */
    protected function preDispatch()
    {
        $appData = $this->getApplicationData($this->getApplicationId());

        if (empty($appData)) {
            return $this->notFoundAction();
        }

        if ($this->isApplicationVariation()) {
            return $this->notFoundAction();
        }

        return $this->checkForRedirect($this->getApplicationId());
    }

    /**
     * Returns true if application has not been submitted. False otherwise.
     *
     * @param integer $applicationId Application Id
     *
     * @return bool
     */
    protected function checkAppStatus($applicationId)
    {
        $data = $this->getApplicationData($applicationId);
        return ($data['status']['id'] === RefData::APPLICATION_STATUS_NOT_SUBMITTED);
    }

    /**
     * Render the section
     *
     * @param string    $titleSuffix Title suffix
     * @param Form|null $form        Form to render
     * @param array     $variables   View variables to set
     *
     * @return ViewModel
     */
    protected function render($titleSuffix, Form $form = null, $variables = array())
    {
        $this->attachCurrentMessages();

        if ($titleSuffix instanceof ViewModel) {
            return $titleSuffix;
        }

        $sectionName = $titleSuffix;
        // overrides for any instance where the section name differs from the view template name
        $sectionOverrides = [
            'person' => 'people'
        ];
        if (array_key_exists($titleSuffix, $sectionOverrides)) {
            $sectionName = $sectionOverrides[$titleSuffix];
        }

        $progress = $this->getSectionStepProgress($sectionName);

        $data = $this->getApplicationData($this->getApplicationId());

        $lvaTitleSuffix = ($titleSuffix === 'people') ?
            ($titleSuffix . '.' . $data['licence']['organisation']['type']['id']) : $titleSuffix;
        $params = array_merge(
            [
                'title' => 'lva.section.title.' . $lvaTitleSuffix,
                'form' => $form,
                'reference' => $data['licence']['licNo']  . ' / ' . $this->getApplicationId(),
                'status' => $data['status']['id'],
                'lva' => $this->lva
            ],
            $progress,
            $variables
        );

        $section = new Section($params);

        $template = $this->getRequest()->isXmlHttpRequest() ? 'ajax' : 'layout';

        $base = new ViewModel();
        $base->setTemplate('layout/' . $template)
            ->setTerminal(true)
            ->addChild($section, 'content');

        return $base;
    }

    /**
     * Get array of step progress data.
     *
     * @param string $currentSection Current section
     *
     * @return array
     */
    protected function getSectionStepProgress($currentSection)
    {
        $data = $this->getApplicationData($this->getApplicationId());

        // Don't show steps on variations
        if ($data['isVariation'] == true) {
            return [];
        }

        $sectionStatus = $this->setEnabledAndCompleteFlagOnSections(
            $data['sections'],
            $data['applicationCompletion']
        );

        $sections = array_keys($sectionStatus);

        $index = array_search($currentSection, $sections);

        if ($index === false) {
            return [];
        }

        // we can pass this array straight to the view
        return ['stepX' => $index+1, 'stepY' => count($sections)];
    }

    /**
     * Method called post saving
     *
     * @param string $section Section being saved
     *
     * @return void
     */
    protected function postSave($section)
    {
        $applicationId = $this->getApplicationId();

        if ($section !== 'undertakings') {
            $this->resetUndertakings($applicationId);
        }

        $this->updateCompletionStatuses($applicationId, $section);
    }

    /**
     * Reset undertakings
     *
     * @param integer $applicationId Application Id
     *
     * @return void
     */
    protected function resetUndertakings($applicationId)
    {
        $this->getServiceLocator()->get('Entity\Application')
            ->forceUpdate($applicationId, ['declarationConfirmation' => 'N']);
    }

    /**
     * Get application data from database
     *
     * @param integer $applicationId Application ID
     *
     * @return mixed
     */
    protected function getApplicationData($applicationId)
    {
        // query is already cached
        $dto = ApplicationQry::create(['id' => $applicationId]);
        $response = $this->handleQuery($dto);
        if ($response->isForbidden()) {
            return null;
        }

        return $response->getResult();
    }
}
