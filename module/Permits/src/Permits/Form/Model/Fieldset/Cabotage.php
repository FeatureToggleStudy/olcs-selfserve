<?php
namespace Permits\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @codeCoverageIgnore Auto-generated file with no methods
 * @Form\Name("Cabotage")
 */
class Cabotage
{
    /**
     * @Form\Name("WillCabotage")
     * @Form\Required(true)
     * @Form\Attributes({
     *   "class" : "input--cabotage",
     *   "id" : "WillCabotage",
     * })
     * @Form\Options({
     *   "checked_value": "Y",
     *     "unchecked_value": "N",
     *     "label": "permits.form.cabotage.label",
     *     "label_attributes": {"class": "form-control form-control--checkbox form-control--advanced"},
     *     "must_be_value": "Y"
     * })
     * @Form\Type("\Common\Form\Elements\InputFilters\SingleCheckbox")
     */

    public $willCabotage = null;
}