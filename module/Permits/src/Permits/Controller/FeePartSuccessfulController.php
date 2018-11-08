<?php
namespace Permits\Controller;

use Zend\Http\Response as HttpResponse;
use Common\Controller\Interfaces\ToggleAwareInterface;
use Olcs\Controller\AbstractSelfserveController;
use Permits\Controller\Config\DataSource\DataSourceConfig;
use Permits\Controller\Config\ConditionalDisplay\ConditionalDisplayConfig;
use Permits\Controller\Config\FeatureToggle\FeatureToggleConfig;
use Permits\Controller\Config\Form\FormConfig;
use Permits\Controller\Config\Params\ParamsConfig;
use Permits\View\Helper\EcmtSection;
use Dvsa\Olcs\Transfer\Command\Permits\DeclineEcmtPermits;
use Dvsa\Olcs\Transfer\Command\Permits\AcceptEcmtPermits;

class FeePartSuccessfulController extends AbstractSelfserveController implements ToggleAwareInterface
{
    protected $toggleConfig = [
        'default' => FeatureToggleConfig::SELFSERVE_ECMT_ENABLED,
    ];

    protected $dataSourceConfig = [
        'default' => DataSourceConfig::PERMIT_APP_FOR_ACCEPT_OR_DECLINE,
    ];

    protected $conditionalDisplayConfig = [
        'generic' => ConditionalDisplayConfig::PERMIT_APP_AWAITING_FEE,
    ];

    protected $formConfig = [
        'generic' => FormConfig::FORM_ACCEPT_AND_PAY,
    ];

    protected $templateConfig = [
        'generic' => 'permits/fee-part-successful',
    ];

    protected $postConfig = [
        'generic' => [
            'params' => ParamsConfig::ID_FROM_ROUTE,
            'step' => EcmtSection::ROUTE_ECMT_PAYMENT_ACTION,
        ]
    ];

    public function handlePost()
    {
        if (isset($this->postParams['Submit']['DeclineButton'])) {
            return $this->nextStep(EcmtSection::ROUTE_ECMT_DECLINE_APPLICATION);
        }

        return parent::handlePost();
    }

    public function retrieveData()
    {
        $dataSourceConfig = $this->configsForAction('dataSourceConfig');

        //retrieve DTO data
        foreach ($dataSourceConfig as $dataSource => $config) {
            /**
             * @var DataSourceInterface $source
             * @var QueryInterface $query
             */
            $source = new $dataSource();
            $query = $source->queryFromParams(array_merge($this->routeParams, $this->queryParams));

            $response = $this->handleQuery($query);
            $data = $this->handleResponse($response);

            if (isset($config['mapper'])) {
                $mapper = isset($config['mapper']) ? $config['mapper'] : DefaultMapper::class;
                if (isset($config['mapperUseTranslations'])){
                    $data = $mapper::mapForDisplay($data, $this->getServiceLocator()->get('Helper\Translation'));
                }else {
                    $data = $mapper::mapForDisplay($data);
                }
            }
            $this->data[$source::DATA_KEY] = $data;
            if (isset($config['append'])) {
                foreach ($config['append'] as $appendTo => $mapper) {
                    $combinedData = [
                        $appendTo => $this->data[$appendTo],
                        $source::DATA_KEY => $data
                    ];
                    $this->data[$appendTo] = $mapper::mapForDisplay($combinedData);
                }
            }
        }
    }
}
