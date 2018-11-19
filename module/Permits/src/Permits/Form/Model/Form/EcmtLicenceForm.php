<?php
namespace Permits\Form\Model\Form;

use Zend\Form\Annotation as Form;

/**
 * @Form\Name("EcmtLicence")
 * @Form\Attributes({"method":"post"})
 * @Form\Type("Common\Form\Form")
 */
class EcmtLicenceForm
{
    /**
     * @Form\Name("Fields")
     * @Form\Options({
     *     "label": "permits.page.ecmt.licence.question",
     *     "label_attributes": {"class": "visually-hidden"},
     * })
     * @Form\ComposedObject("Permits\Form\Model\Fieldset\EcmtLicence")
     */
    public $fields = null;

    /**
     * @Form\Name("Submit")
     * @Form\ComposedObject("Permits\Form\Model\Fieldset\SubmitLicence")
     */
    public $submit = null;
}
