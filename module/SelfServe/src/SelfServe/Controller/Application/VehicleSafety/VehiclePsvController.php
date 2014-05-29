<?php

/**
 * Vehicle PSV Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */

namespace SelfServe\Controller\Application\VehicleSafety;

use Common\Form\Elements\Validators\TableRequiredValidator;

/**
 * Vehicle PSV Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class VehiclePsvController extends VehicleSafetyController
{
    /**
     * Holds the action data bundle
     *
     * @var array
     */
    protected $actionDataBundle = array(
        'properties' => array(
            'id',
            'version',
            'vrm',
            'makeModel',
            'psvType',
            'isNovelty'
        )
    );

    /**
     * Holds the data bundle
     *
     * @var array
     */
    protected $dataBundle = array(
        'properties' => array(
            'id',
            'version',
            'totAuthSmallVehicles',
            'totAuthMediumVehicles',
            'totAuthLargeVehicles',
            'enterReg'
        ),
        'children' => array(
            'licence' => array(
                'properties' => null,
                'children' => array(
                    'licenceVehicles' => array(
                        'properties' => null,
                        'children' => array(
                            'vehicle' => array(
                                'properties' => array(
                                    'id',
                                    'vrm',
                                    'makeModel',
                                    'psvType',
                                    'isNovelty'
                                )
                            )
                        )
                    )
                )
            )
        )
    );

    /**
     * Action service
     *
     * @var string
     */
    protected $actionService = 'Vehicle';

    /**
     * Action data map
     *
     * @var array
     */
    protected $actionDataMap = array(
        'main' => array(
            'mapFrom' => array(
                'data'
            )
        )
    );

    /**
     * Holds the form tables
     *
     * @var array
     */
    protected $formTables = array(
        'large' => 'application_vehicle-safety_vehicle-psv-large',
        'medium' => 'application_vehicle-safety_vehicle-psv-medium',
        'small' => 'application_vehicle-safety_vehicle-psv-small'
    );

    /**
     * Redirect to the first section
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->renderSection();
    }

    /**
     * Add large vehicles
     *
     * @return Response
     */
    public function largeAddAction()
    {
        return $this->renderSection();
    }

    /**
     * Edit large vehicles
     *
     * @return Response
     */
    public function largeEditAction()
    {
        return $this->renderSection();
    }

    /**
     * Delete large vehicle
     *
     * @return Response
     */
    public function largeDeleteAction()
    {
        return $this->delete();
    }

    /**
     * Add medium vehicles
     *
     * @return Response
     */
    public function mediumAddAction()
    {
        return $this->renderSection();
    }

    /**
     * Edit medium vehicles
     *
     * @return Response
     */
    public function mediumEditAction()
    {
        return $this->renderSection();
    }

    /**
     * Delete medium vehicle
     *
     * @return Response
     */
    public function mediumDeleteAction()
    {
        return $this->delete();
    }

    /**
     * Add small vehicles
     *
     * @return Response
     */
    public function smallAddAction()
    {
        return $this->renderSection();
    }

    /**
     * Edit small vehicles
     *
     * @return Response
     */
    public function smallEditAction()
    {
        return $this->renderSection();
    }

    /**
     * Delete small vehicle
     *
     * @return Response
     */
    public function smallDeleteAction()
    {
        return $this->delete();
    }

    /**
     * Alter action form
     *
     * @param Form $form
     * @return Form
     */
    public function alterActionForm($form)
    {
        $actionName = $this->getActionName();

        if (!in_array($actionName, array('small-add', 'small-edit'))) {
            $form->get('data')->remove('isNovelty');
            $form->get('data')->remove('makeModel');
        }

        return $form;
    }

    /**
     * Remove vehicle size tables based on OC data
     *
     * @param Form $form
     * @return Form
     */
    public function alterForm($form)
    {
        $data = $this->load($this->getIdentifier());

        $isPost = $this->getRequest()->isPost();
        $post = $this->getRequest()->getPost();

        foreach (array_keys($this->formTables) as $table) {

            $ucTable = ucwords($table);

            if (isset($data['totAuth' . $ucTable . 'Vehicles']) && $data['totAuth' . $ucTable . 'Vehicles'] < 1) {

                $form->remove($table);

            } elseif ($isPost && isset($post['data']['enterReg']) && $post['data']['enterReg'] == 'Y') {

                $input = $form->getInputFilter()->get($table)->get('table');
                $input->setRequired(true)->setAllowEmpty(false)->setContinueIfEmpty(true);

                $validatorChain = $input->getValidatorChain();
                $validatorChain->attach(new TableRequiredValidator(array('label' => $table . ' vehicle')));
            }
        }

        return $form;
    }

    /**
     * Return the form table data
     *
     * @return array
     */
    protected function getFormTableData($id, $table)
    {
        $data = $this->load($this->getIdentifier());

        $rows = array();

        foreach ($data['licence']['licenceVehicles'] as $vehicle) {
            if ($vehicle['vehicle']['psvType'] != $table) {
                continue;
            }

            $rows[] = $vehicle['vehicle'];
        }

        return $rows;
    }

    /**
     * Save the vehicle
     *
     * @param array $data
     * @param string $service
     */
    protected function actionSave($data, $service = null)
    {
        list($type, $action) = explode('-', $this->getActionName());

        $this->saveVehicle($data, $action);
    }

    /**
     * Process load
     *
     * @param array $data
     * @return array
     */
    protected function processLoad($data)
    {
        return array('data' => $data);
    }

    /**
     * Process action load
     *
     * @param array $data
     * @return array
     */
    protected function processActionLoad($data)
    {
        list($type, $action) = explode('-', $this->getActionName());

        $data['psvType'] = $type;

        return array('data' => $data);
    }
}
