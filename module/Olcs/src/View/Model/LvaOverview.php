<?php

/**
 * LVA Overview View Model
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 */
namespace Olcs\View\Model;

use Common\View\AbstractViewModel;

/**
 * LVA Overview View Model
 *
 * @author Nick Payne <nick.payne@valtech.co.uk>
 */
abstract class LvaOverview extends AbstractViewModel
{
    /**
     * Set the overview data
     *
     * @param array $data
     * @param array $sections
     */
    public function __construct($data, array $sections = array())
    {
        $sectionModel = __NAMESPACE__ . '\\' . $this->sectionModel;
        $overviewSections = [];

        foreach ($sections as $section) {
            $overviewSections[] = new $sectionModel($section, $data);
        }

        $this->setVariable('sections', $overviewSections);
    }
}
