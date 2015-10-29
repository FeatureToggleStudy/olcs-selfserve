<?php

/**
 * Lva Operating Centre
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
namespace Olcs\FormService\Form\Lva\OperatingCentre;

use Common\FormService\Form\Lva\OperatingCentre\CommonOperatingCentre;
use Dvsa\Olcs\Utils\Helper\ValueHelper;
use Olcs\View\Helper\ReturnToAddress;
use Zend\Form\Form;

/**
 * Lva Operating Centre
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class LvaOperatingCentre extends CommonOperatingCentre
{
    /**
     * @var \Common\Service\Helper\TranslationHelperService
     */
    protected $translator;

    /**
     * @var \Common\Service\Helper\UrlHelperService
     */
    protected $url;

    public function alterForm(Form $form, array $params)
    {
        $isNi = $this->isNi($params);

        $this->setSendByPostContent($form, $isNi, $params);

        $this->setAdPlacedLabels($form, $isNi);

        parent::alterForm($form, $params);
    }

    protected function setAdPlacedLabels(Form $form, $isNi)
    {
        $adPlaced = $form->get('advertisements')->get('adPlaced');

        $label = $this->getTranslator()->translateReplace(
            'markup-lva-oc-ad-placed-label-selfserve',
            [
                $this->getUrl()->fromRoute(
                    'guides/guide',
                    ['guide' => 'advertising-your-operating-centre-' . ($isNi ? 'ni' : 'gb')]
                )
            ]
        );

        $adPlaced->setLabel($label);

        $valuesOptions = $adPlaced->getValueOptions();

        $valuesOptions['Y'] = 'lva-oc-adplaced-y-selfserve';
        $valuesOptions['N'] = 'lva-oc-adplaced-n-selfserve';

        $adPlaced->setValueOptions($valuesOptions);
    }

    protected function setSendByPostContent(Form $form, $isNi, $params)
    {
        $adSendByPost = $form->get('advertisements')->get('adSendByPost');

        $value = $this->getTranslator()->translateReplace(
            'markup-lva-oc-ad-send-by-post-text',
            [
                ReturnToAddress::getAddress($isNi, '<br />'),
                $params['licence']['licNo'] . '/' . $params['id']
            ]
        );

        $adSendByPost->setValue($value);
    }

    /**
     * @return \Common\Service\Helper\TranslationHelperService
     */
    protected function getTranslator()
    {
        if ($this->translator === null) {
            $this->translator = $this->getServiceLocator()->get('Helper\Translation');
        }

        return $this->translator;
    }

    /**
     * @return \Common\Service\Helper\UrlHelperService
     */
    protected function getUrl()
    {
        if ($this->url === null) {
            $this->url = $this->getServiceLocator()->get('Helper\Url');
        }

        return $this->url;
    }

    /**
     * @param array $params
     * @return bool
     */
    protected function isNi(array $params)
    {
        if (isset($params['niFlag'])) {
            return ValueHelper::isOn($params['niFlag']);
        }

        if (isset($params['trafficArea']['isNi'])) {
            return (bool)$params['trafficArea']['isNi'];
        }

        if (isset($params['licence']['trafficArea']['isNi'])) {
            return (bool)$params['licence']['trafficArea']['isNi'];
        }

        return false;
    }
}