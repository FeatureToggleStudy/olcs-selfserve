<?php
namespace Permits\Controller;

use Common\Controller\Interfaces\ToggleAwareInterface;
use Common\RefData;
use Olcs\Controller\AbstractSelfserveController;
use Permits\Controller\Config\DataSource\DataSourceConfig;
use Permits\Controller\Config\ConditionalDisplay\ConditionalDisplayConfig;
use Permits\Controller\Config\FeatureToggle\FeatureToggleConfig;
use Permits\Controller\Config\Form\FormConfig;
use Permits\View\Helper\IrhpApplicationSection;

class TypeController extends AbstractSelfserveController implements ToggleAwareInterface
{
    protected $toggleConfig = [
        'default' => FeatureToggleConfig::SELFSERVE_PERMITS_ENABLED,
    ];

    protected $dataSourceConfig = [
        'question' => DataSourceConfig::PERMIT_APP_TYPE,
    ];

    protected $conditionalDisplayConfig = [
        'question' => ConditionalDisplayConfig::PERMIT_APP_CAN_APPLY,
    ];

    protected $formConfig = [
        'question' => FormConfig::FORM_TYPE,
    ];

    protected $templateConfig = [
        'default' => 'permits/single-question'
    ];

    protected $templateVarsConfig = [
        'question' => [
            'browserTitle' => 'permits.page.type.title',
            'question' => 'permits.page.type.question',
            'backUri' => IrhpApplicationSection::ROUTE_PERMITS,
            'cancelUri' => IrhpApplicationSection::ROUTE_PERMITS,
        ],
    ];

    protected $postConfig = [
        'question' => [
            'step' => IrhpApplicationSection::ROUTE_ADD_LICENCE,
        ],
    ];

    /**
     * @param array $config
     * @param array $params
     */
    public function handlePostCommand(array &$config, array $params)
    {
        if ($params['type'] == RefData::ECMT_SHORT_TERM_PERMIT_TYPE_ID) {
            $config['step'] = IrhpApplicationSection::ROUTE_YEAR;
        }

        $this->redirectParams = [
            'type' => $params['type']
        ];
    }
}
