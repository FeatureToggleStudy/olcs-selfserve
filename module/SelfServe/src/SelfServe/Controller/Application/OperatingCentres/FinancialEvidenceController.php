<?php

/**
 * FinancialEvidence Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace SelfServe\Controller\Application\OperatingCentres;

/**
 * FinancialEvidence Controller
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class FinancialEvidenceController extends OperatingCentresController
{
    /**
     * Render the section form
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->renderSection();
    }

    /**
     * Save data
     *
     * @param array $data
     */
    public function save($data)
    {
    }

    /**
     * Load data from id
     *
     * @param int $id
     */
    public function load($id)
    {
        return array('data' => array());
    }
}
