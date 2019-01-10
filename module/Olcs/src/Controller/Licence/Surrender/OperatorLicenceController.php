<?php

namespace Olcs\Controller\Licence\Surrender;

use Common\RefData;
use Common\Data\Mapper\Licence\Surrender\OperatorLicence as Mapper;
use Olcs\Controller\Config\DataSource\DataSourceConfig;
use Olcs\Form\Model\Form\Surrender\OperatorLicence;

class OperatorLicenceController extends AbstractSurrenderController
{
    protected $formConfig = [
        'default' => [
            'operator-licence' => [
                'formClass' => OperatorLicence::class,
                'mapper' => [
                    'class' => Mapper::class
                ],
                'dataSource' => 'surrender'
            ]
        ]
    ];

    protected $templateConfig = [
        'default' => 'licence/surrender-licence-documents'
    ];


    protected $dataSourceConfig = [
        'default' => DataSourceConfig::SURRENDER
    ];

    public function indexAction()
    {
        return $this->createView();
    }

    public function submitAction()
    {
        $formData = (array)$this->getRequest()->getPost();
        $this->form->setData($formData);
        $validForm = $this->form->isValid();
        if ($validForm) {
            $data = Mapper::mapFromForm($formData);
            if ($this->updateSurrender(RefData::SURRENDER_STATUS_LIC_DOCS_COMPLETE, $data)) {
                $routeName = 'licence/surrender/review';
                if ($this->data['licence']['isInternationalLicence']) {
                    $routeName = 'licence/surrender/community-licence/GET';
                }
                $this->nextStep($routeName);
            }
        }
        return $this->createView();
    }

    public function alterForm($form)
    {
        $form->get('form-actions')->get('submit')->setLabel('Save and Continue');
        return $form;
    }


    /**
     * @return array
     */
    protected function getViewVariables(): array
    {
        return [
            'pageTitle' => 'licence.surrender.operator_licence.title',
            'licNo' => $this->data['surrender']['licence']['licNo'],
            'backUrl' => $this->getBackLink('licence/surrender/operator-licence/GET'),
            'returnLinkText' => 'licence.surrender.operator_licence.return_to_current_discs.link',
            'returnLink' => $this->getBackLink('licence/surrender/operator-licence/GET'),
        ];
    }
}
