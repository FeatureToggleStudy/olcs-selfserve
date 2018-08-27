<?php
namespace Permits\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @codeCoverageIgnore Auto-generated file with no methods
 * @Form\Name("SubmitCancel")
 */
class SubmitCancel
{
    /**
     * @Form\Name("SubmitButton")
     * @Form\Attributes({
     *     "class":"action--primary large top",
     *     "id":"submitbutton",
     *     "value":"Save and continue",
     * })
     * @Form\Type("Zend\Form\Element\Submit")
     */
    public $submit = null;

    /**
     * @Form\Name("Cancel")
     * @Form\Attributes({
     *     "class":"action--primary large return-overview",
     *     "id":"save-return-button",
     *     "value":"Cancel",
     * })
     * @Form\Type("Zend\Form\Element\Submit")
     */
    public $save = null;
}
