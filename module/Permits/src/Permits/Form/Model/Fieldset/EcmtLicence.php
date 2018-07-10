<?php
namespace Permits\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @codeCoverageIgnore Auto-generated file with no methods
 * @Form\Name("EcmtLicence")
 */
class EcmtLicence
{
    /**
     * @Form\Name("EcmtLicence")
     * @Form\Required(true)
     * @Form\Attributes({
     *   "class" : "input--trips",
     *    "id" : "EcmtLicence",
     * })
     * @Form\Options({
     *   "checked_value": "Y",
     *     "unchecked_value": "N",
     *     "label": "permits.form.euro6.label",
     *     "label_attributes": {"class": "form-control form-control--checkbox form-control--advanced"},
     *     "must_be_value": "Y"
     * })
     * @Form\Type("\Common\Form\Elements\InputFilters\SingleCheckbox")
     */
    public $ecmtLicence = null;

}

?>
