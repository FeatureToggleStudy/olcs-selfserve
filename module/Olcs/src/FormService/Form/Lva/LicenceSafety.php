<?php

namespace Olcs\FormService\Form\Lva;

use Common\FormService\Form\Lva\Safety as CommonSafety;

/**
 * Licence safety
 *
 * @author Alex Peshkov <alex.peshkov@valtech.co.uk>
 */
class LicenceSafety extends CommonSafety
{
    /**
     * Returns form
     *
     * @return \Zend\Form\FormInterface
     */
    public function getForm()
    {
        $form = parent::getForm();

        $form->get('form-actions')->get('save')->setAttribute('class', 'action--primary large');
        $this->getFormHelper()->remove($form, 'form-actions->cancel');

        return $form;
    }
}
