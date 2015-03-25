<?php

/**
 * External Licence Vehicles Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Olcs\Controller\Lva\Licence;

use Olcs\Controller\Lva\AbstractGenericVehiclesGoodsController;
use Olcs\Controller\Lva\Traits\LicenceControllerTrait;
use Common\Controller\Lva\Traits;
use Zend\Form\Form;

/**
 * External Licence Vehicles Controller
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class VehiclesController extends AbstractGenericVehiclesGoodsController
{
    use LicenceControllerTrait,
        Traits\LicenceGoodsVehiclesControllerTrait,
        Traits\PsvGoodsLicenceVariationControllerTrait {
            Traits\PsvGoodsLicenceVariationControllerTrait::alterFormForLva as traitAlterFormForLva;
        }

    protected $lva = 'licence';
    protected $location = 'external';

    /**
     * This method handles calling both the trait's alterFormForLva method, and it's parents
     *
     * @param Zend\Form\Form $form
     * @return $form
     */
    protected function alterFormForLva(Form $form)
    {
        return parent::alterFormForLva($this->traitAlterFormForLva($form));
    }

    /**
     * Pre save vehicle
     *
     * @param array $data
     * @param string $mode
     * @return mixed
     */
    protected function preSaveVehicle($data, $mode)
    {
        if ($mode === 'add') {
            $data['licence-vehicle']['specifiedDate'] = date('Y-m-d');
        }

        return $data;
    }
}
