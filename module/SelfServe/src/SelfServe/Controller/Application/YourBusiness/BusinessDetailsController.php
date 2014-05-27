<?php

/**
 * BusinessDetails Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace SelfServe\Controller\Application\YourBusiness;

/**
 * BusinessDetails Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class BusinessDetailsController extends YourBusinessController
{

    protected $service = 'Application';

    protected $dataBundle = [
        'children' => [
            'licence' => [
                'children' => [
                    'organisation',
                    'tradingNames',
                ]
            ],
        ],
    ];

    /**
     * Render the section form
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->renderSection();
    }

    /**
     * Save data
     *
     * @param array $data
     * @parem string $service
     */
    protected function save($data, $service = null)
    {
        if (isset($data['tradingNames'])) {
            $this->makeRestCall('TradingNames', 'POST', $data['tradingNames']);
        }

        // @TODO we shouldn't really need to do this; it's only
        // because our $service property is set to Application
        // so we can fetch tradingNames as a child value
        return parent::save($data, 'Organisation');
    }

    protected function alterFormBeforeValidation($form)
    {
        $form = parent::alterFormBeforeValidation($form);

        // @TODO alter based on submit button potentially; may
        // not want to validate based on whether we were
        // adding a trading name or submitting a CH lookup

        return $form;
    }

    protected function alterForm($form)
    {
        $organisation = $this->getOrganisationData(['organisationType']);

        $fieldset = $form->get('data');

        // always set the edit link
        $fieldset->get('edit_business_type')->setValue(
            $this->getUrlFromRoute(
                'Application/YourBusiness/BusinessType',
                ['applicationId' => $this->getIdentifier()]
            )
        );

        switch ($organisation['organisationType']) {
            case 'org_type.lc':
            case 'org_type.llp':
                // no-op; the full form is fine
                break;
            case 'org_type.st':
                $fieldset->remove('name')
                    ->remove('companyNumber');
                break;
            case 'org_type.p':
                $fieldset->remove('companyNumber');
                break;
            case 'org_type.o':
                $fieldset->remove('companyNumber')
                    ->remove('tradingNames');
                break;
        }
        return $form;
    }

    public function processDataMapForSave($oldData, $map = array(), $section = 'main')
    {
        $data = parent::processDataMapForSave($oldData, $map, $section);

        // the disabled input will always be null, so ignore it...
        unset($data['organisationType']);

        if (isset($data['companyNumber'])) {
            // unfortunately the company number field is a complex one so can't
            // be mapped directly
            $data['registeredCompanyNumber'] = $data['companyNumber']['company_number'];
        }

        if (isset($data['tradingNames'])) {
            $licence = $this->getLicenceData(['id']);
            $tradingNames = [];
            foreach ($data['tradingNames']['trading_name'] as $tradingName) {
                if (trim($tradingName['text']) === '') {
                    continue;
                }
                $tradingNames[] = [
                    'tradingName' => $tradingName['text'],
                    'licence' => $licence['id'],
                ];
            }
            $data['tradingNames'] = $tradingNames;
        }

        return $data;
    }

    protected function processLoad($data)
    {
        $licence = $data['licence'];
        $organisation = $licence['organisation'];

        $tradingNames = [];
        foreach ($licence['tradingNames'] as $tradingName) {
            $tradingNames[] = ['text' => $tradingName['tradingName']];
        }
        $tradingNames[] = ['text' => ''];

        $map = [
            'tradingNames' => [
                'trading_name' => $tradingNames,
            ],
            'companyNumber' => [
                'company_number' => $organisation['registeredCompanyNumber']
            ]
        ];

        return [
            'data' => array_merge($organisation, $map)
        ];
    }

    protected function getForm($type)
    {
        $form = parent::getForm($type);

        $form = $this->processLookupCompany($form);

        return $form;
    }

    public function generateFormWithData($name, $callback, $data = null, $tables = false)
    {
        $request = $this->getRequest();

        $post = (array)$request->getPost();
        if (isset($post['data']['tradingNames']['submit_add_trading_name'])) {

            $this->setPersist(false);

        }

        $form = parent::generateFormWithData($name, $callback, $data, $tables);

        $form = $this->processAddTradingName($form);

        return $form;
    }

    protected function processAddTradingName($form)
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $form;
        }

        $post = (array)$request->getPost()['data'];
        if (isset($post['tradingNames']['submit_add_trading_name'])) {

            $form->setValidationGroup(array('data' => ['tradingNames']));

            $form->setData($request->getPost());
            if ($form->isValid()) {

                $tradingNames = $form->getData()['data']['tradingNames']['trading_name'];

                //remove existing entries from collection and check for empty entries
                foreach ($tradingNames as $key => $val) {
                    $form->get('data')->get('tradingNames')->get('trading_name')->remove($key);

                    if (strlen(trim($val['text'])) == 0) {
                        unset($tradingNames[$key]);
                    }
                }
                $tradingNames[] = array('text' => '');

                //reset keys
                $tradingNames = array_merge($tradingNames);

                $data = array('data' => array(
                    'tradingNames' => array('trading_name' => $tradingNames)
                ));

                $form->setData($data);
            }

        }

        return $form;

    }

    protected function processLookupCompany($form)
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $form;
        }

        $post = (array)$request->getPost()['data'];

        if (isset($post['companyNumber']['submit_lookup_company'])) {

            $this->setPersist(false);

            $result = $this->makeRestCall(
                'CompaniesHouse',
                'GET',
                [
                    'type' => 'numberSearch',
                    'value' => $post['companyNumber']['company_number']
                ]
            );

            if ($result['Count'] == 1) {
                $companyName = $result['Results'][0]['CompanyName'];
                $post['name'] = $companyName;
                $this->setFieldValue('data', $post);
            } else {
                $form->get('data')->get('companyNumber')->setMessages(
                    array('company_number' => array(
                        'Sorry, we couldn\'t find any matching companies, '
                        . 'please try again or enter your details manually below'))
                );
            }
        }

        return $form;
    }
}
