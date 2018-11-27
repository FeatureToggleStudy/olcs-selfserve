<?php

/**
 * Form View Model
 *
 * @author Craig Reasbeck <craig.reasbeck@valtech.co.uk>
 */
namespace Olcs\View\Model;

use Common\View\AbstractViewModel;
use Common\Form\Form as FormModel;
use Zend\Form\Form as ZendForm;

/**
 * Form View Model
 *
 * @author Craig Reasbeck <craig.reasbeck@valtech.co.uk>
 */
class Form extends AbstractViewModel
{
    /**
     * Set the template for the dashboard
     *
     * @var string
     */
    protected $template = 'form';

    /**
     * Set the form into the view.
     *
     * @param FormModel $form
     */
    public function setForm(ZendForm $form)
    {
        $this->setVariable('form', $form);
    }
}
