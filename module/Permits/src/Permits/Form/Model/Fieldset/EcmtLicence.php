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
     *      "label": "Choose one licence below",
     *      "fieldset-attributes": {"id": "ecmt-licence"},
     *      "fieldset-data-group": "licence-type",
     *      "label_attributes": {"class": "form-control form-control--radio"},
     *      "value_options": {
     *          "OB2013691 (North East)",
     *          "OC010019897 (North West)",
     *          "PB5553691 (South East)",
     *          "PC010119896 (South West)",
     *      },
     * })
     * @Form\Type("Radio")
     */
    public $ecmtLicence = null;

}

?>
