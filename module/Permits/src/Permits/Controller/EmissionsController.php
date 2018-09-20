<?php
namespace Permits\Controller;

use Common\Controller\Interfaces\ToggleAwareInterface;
use Dvsa\Olcs\Transfer\Command\Permits\UpdateEcmtEmissions;
use Olcs\Controller\AbstractSelfserveController;
use Permits\Controller\Config\DataSource\DataSourceConfig;
use Permits\Controller\Config\ConditionalDisplay\ConditionalDisplayConfig;
use Permits\Controller\Config\FeatureToggle\FeatureToggleConfig;
use Permits\Controller\Config\Form\FormConfig;
use Permits\Controller\Config\Params\ParamsConfig;

use Permits\View\Helper\EcmtSection;

class EmissionsController extends AbstractSelfserveController implements ToggleAwareInterface
{
    protected $toggleConfig = [
        'default' => FeatureToggleConfig::SELFSERVE_ECMT_ENABLED,
    ];

    protected $dataSourceConfig = [
        'default' => DataSourceConfig::PERMIT_APP,
    ];

    protected $conditionalDisplayConfig = [
        'default' => ConditionalDisplayConfig::PERMIT_APP_NOT_SUBMITTED,
    ];

    protected $formConfig = [
        'default' => FormConfig::FORM_EMISSIONS,
    ];

    protected $templateConfig = [
        'generic' => 'permits/euro6-emissions'
    ];

    protected $postConfig = [
        'default' => [
            'command' => UpdateEcmtEmissions::class,
            'params' => ParamsConfig::ID_FROM_ROUTE,
            'step' => EcmtSection::ROUTE_ECMT_CABOTAGE,
        ],
    ];
}
