<?php
namespace Permits\Controller;

use Common\Controller\Interfaces\ToggleAwareInterface;
use Olcs\Controller\AbstractSelfserveController;
use Dvsa\Olcs\Transfer\Command\Permits\UpdateEcmtCountries;
use Permits\Controller\Config\DataSource\DataSourceConfig;
use Permits\Controller\Config\ConditionalDisplay\ConditionalDisplayConfig;
use Permits\Controller\Config\FeatureToggle\FeatureToggleConfig;
use Permits\Controller\Config\Form\FormConfig;
use Permits\Controller\Config\Params\ParamsConfig;

use Permits\View\Helper\EcmtSection;
use Zend\View\Model\ViewModel;

class ListController extends AbstractSelfserveController implements ToggleAwareInterface
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
        'restrictedcountries' => FormConfig::FORM_RESTRICTED_COUNTRIES,
    ];

    protected $templateConfig = [
        'restrictedcountries' => 'permits/restricted-countries'
    ];

    protected $postConfig = [
        'default' => [
            'command' => UpdateEcmtCountries::class,
            'params' => ParamsConfig::ID_FROM_ROUTE,
            'step' => EcmtSection::ROUTE_ECMT_NO_OF_PERMITS,
        ],
    ];

    public function restrictedCountriesAction()
    {
        $id = $this->params()->fromRoute('id', -1);

        // Read data
        $application = $this->data['application'];

        $data = $this->params()->fromPost();

        if (is_array($data) && array_key_exists('Submit', $data)) {
            //Validate
            $this->form->setData($data);
            if ($this->form->isValid()) {
                //EXTRA VALIDATION
                if ((
                    $data['Fields']['hasRestrictedCountries'] == 1
                    && isset($data['Fields']['restrictedCountriesList']['restrictedCountriesList']))
                    || ($data['Fields']['hasRestrictedCountries'] == 0)
                ) {
                    if ($data['Fields']['hasRestrictedCountries'] == 0) {
                        $countryIds = [];
                    } else {
                        $countryIds = $data['Fields']['restrictedCountriesList']['restrictedCountriesList'];
                    }

                    $this-> handleSaveAndRedirect(['id' => $id, 'countryIds' => $countryIds]);
                } else {
                    //conditional validation failed, restricted countries list should not be empty
                    $this->form->get('Fields')
                        ->get('restrictedCountriesList')
                        ->get('restrictedCountriesList')
                        ->setMessages(['error.messages.restricted.countries.list']);
                }
            } else {
                //Custom Error Message
                $this->form->get('Fields')
                    ->get('hasRestrictedCountries')
                    ->setMessages(['error.messages.restricted.countries']);
            }
        }

        $view = new ViewModel();

        $view->setVariable('id', $id);
        $view->setVariable('form', $this->form);
        $view->setVariable('ref', $application['applicationRef']);
        $view->setTemplate($this->templateConfig[$this->action]);

        return $view;
    }
}
