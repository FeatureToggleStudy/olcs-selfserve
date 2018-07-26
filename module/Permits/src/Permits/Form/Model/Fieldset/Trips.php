<?php
namespace Permits\Form\Model\Fieldset;

use Zend\Form\Annotation as Form;

/**
 * @codeCoverageIgnore Auto-generated file with no methods
 * @Form\Name("Trips")
 */
class Trips
{
    /**
     * @Form\Name("TripsAbroad")
     * @Form\Required(true)
     * @Form\Attributes({
     *   "class" : "input--trips",
     *   "id" : "TripsAbroad",
     *   "step" : "any"
     * })
     * @Form\Options({
     *     "label": "permits.form.trips.label",
     *     "hint": "For licence OB2013691 (North East of England)",
     *     "short-label": "",
     *     "allow_empty" : true,
     *     "allow_empty" : true,
     * })
     * @Form\Input("Common\InputFilter\ContinueIfEmptyInput")
     * @Form\Validator({
     *     "name": "Permits\Form\Validator\CustomBetween",
     *     "options": {
     *          "min":0,
     *          "max":999999,
     *          "too_small_message" : "error.messages.trips.too-small",
     *          "too_large_message" : "error.messages.trips.too-large",
     *          "not_digit_message" : "error.messages.permits.required.not-digit"
     *     }})
     * @Form\Type("Zend\Form\Element\Number")
     */

    public $tripsAbroad = null;
}
