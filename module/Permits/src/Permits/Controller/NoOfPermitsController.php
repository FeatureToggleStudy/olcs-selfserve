<?php
namespace Permits\Controller;

use Common\Controller\Interfaces\ToggleAwareInterface;
use Dvsa\Olcs\Transfer\Command\IrhpApplication\UpdateMultipleNoOfPermits;
use Olcs\Controller\AbstractSelfserveController;
use Permits\Controller\Config\DataSource\DataSourceConfig;
use Permits\Controller\Config\ConditionalDisplay\ConditionalDisplayConfig;
use Permits\Controller\Config\FeatureToggle\FeatureToggleConfig;
use Permits\Controller\Config\Form\FormConfig;
use Permits\Controller\Config\Params\ParamsConfig;
use Permits\View\Helper\IrhpApplicationSection;

class NoOfPermitsController extends AbstractSelfserveController implements ToggleAwareInterface
{
    protected $toggleConfig = [
        'default' => FeatureToggleConfig::SELFSERVE_PERMITS_ENABLED,
    ];

    protected $dataSourceConfig = [
        'default' => DataSourceConfig::IRHP_APP_WITH_MAX_PERMITS_BY_STOCK
    ];

    protected $conditionalDisplayConfig = [
        'default' => ConditionalDisplayConfig::IRHP_APP_READY_FOR_NO_OF_PERMITS
    ];

    protected $formConfig = [
        'default' => FormConfig::FORM_NO_OF_PERMITS,
    ];

    protected $templateConfig = [
        'default' => 'permits/single-question'
    ];

    protected $templateVarsConfig = [
        'default' => [
            'backUri' => IrhpApplicationSection::ROUTE_APPLICATION_OVERVIEW,
        ]
    ];

    protected $postConfig = [
        'default' => [
            'retrieveData' => true,
            'checkConditionalDisplay' => true,
            'command' => UpdateMultipleNoOfPermits::class,
            'params' => ParamsConfig::ID_FROM_ROUTE,
            'step' => IrhpApplicationSection::ROUTE_CHECK_ANSWERS,
            'saveAndReturnStep' => IrhpApplicationSection::ROUTE_APPLICATION_OVERVIEW,
        ],
    ];

    public function handlePost()
    {
        if (isset($this->postParams['Submit']['SelectOtherCountriesButton'])) {
            return $this->redirect()->toRoute(IrhpApplicationSection::ROUTE_COUNTRIES, [], [], true);
        } elseif (isset($this->postParams['Submit']['CancelButton'])) {
            return $this->redirect()->toRoute(IrhpApplicationSection::ROUTE_APPLICATION_OVERVIEW, [], [], true);
        }

        parent::handlePost();
    }
}
