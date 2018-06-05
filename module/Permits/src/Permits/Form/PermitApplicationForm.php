<?php
namespace Permits\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;


class PermitApplicationForm extends Form
{
    private $inputFilter;

    public function __construct($name = null)
    {
        parent::__construct();

        $this->inputFilter = null;

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'name' => 'numberOfTrips',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'type' => 'Hidden',
            'name' => 'sectors',
            ));

        $this->add(array(
            'type' => 'Hidden',
            'name' => 'restrictedCountries',
            'options' => array(
                'value_options' => array(
                    '1' => 'Yes',
                    '0' => 'No',
                ),
            ),
        ));

        $this->add(array(
            'type' => 'Hidden',
            'name' => 'restrictedCountriesList',
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Submit and Pay',
                'id' => 'submitbutton',
                'class' => 'action--primary large',
            ),
        ));
    }

    public function getInputFilter()
    {
        return $this->inputFilter;
    }
}
