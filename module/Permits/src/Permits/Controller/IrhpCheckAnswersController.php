<?php
namespace Permits\Controller;

use Common\Controller\Interfaces\ToggleAwareInterface;
use Dvsa\Olcs\Transfer\Command\IrhpApplication\UpdateCheckAnswers;
use Olcs\Controller\AbstractSelfserveController;
use Permits\Controller\Config\DataSource\DataSourceConfig;
use Permits\Controller\Config\ConditionalDisplay\ConditionalDisplayConfig;
use Permits\Controller\Config\FeatureToggle\FeatureToggleConfig;
use Permits\Controller\Config\Form\FormConfig;
use Permits\Controller\Config\Params\ParamsConfig;

use Permits\View\Helper\IrhpApplicationSection;

class IrhpCheckAnswersController extends AbstractSelfserveController implements ToggleAwareInterface
{
    protected $toggleConfig = [
        'default' => FeatureToggleConfig::SELFSERVE_PERMITS_ENABLED,
    ];

    protected $dataSourceConfig = [
        'default' => DataSourceConfig::IRHP_APP_CHECK_ANSWERS,
    ];

    protected $conditionalDisplayConfig = [
        'default' => ConditionalDisplayConfig::IRHP_APP_CAN_CHECK_ANSWERS,
    ];

    protected $formConfig = [
        'default' => FormConfig::FORM_IRHP_CHECK_ANSWERS,
    ];

    protected $templateConfig = [
        'generic' => 'permits/check-answers'
    ];

    protected $templateVarsConfig = [
        'generic' => [
            'browserTitle' => 'permits.page.check-answers.browser.title',
            'title' => 'permits.page.check-answers.title',
            'backUri' => IrhpApplicationSection::ROUTE_APPLICATION_OVERVIEW,
        ]
    ];

    protected $postConfig = [
        'default' => [
            'retrieveData' => true,
            'checkConditionalDisplay' => true,
            'command' => UpdateCheckAnswers::class,
            'params' => ParamsConfig::ID_FROM_ROUTE,
            'step' => IrhpApplicationSection::ROUTE_DECLARATION,
            'saveAndReturnStep' => IrhpApplicationSection::ROUTE_APPLICATION_OVERVIEW,
        ],
    ];
}
