<?php
/**
 * Created by PhpStorm.
 * User: shaunhare
 * Date: 2018-11-27
 * Time: 10:44
 */

namespace Olcs\Controller\Licence\Surrender;

use Common\Service\Helper\TranslationHelperService;


class ConfirmationController extends AbstractSurrenderController
{
    protected $pageTemplate = 'pages/confirmation';

    public function indexAction()
    {
        /** @var $translator TranslationHelperService */
        $translator = $this->getServiceLocator()->get('Helper\Translation');

        $params = [
            'content' => $translator->translateReplace(
                'markup-licence-surrender-confirmation',
                [
                    $this->licence['licNo'],
                    $this->getSignatureFullName(),
                    $this->getSignatureDate(),
                    $this->returnDashboardLink()
                ]
            ),
            'backLink' => $this->returnDashboardLink()
        ];

        return $this->renderView($params);
    }

    private function getSignatureFullName()
    {
        $names = [];
        $attributes = $this->getSurrender()["digitalSignature"]["attributes"];
        $names[] = $attributes['first_name'] ?? '';
        $names[] = $attributes['surname'] ?? '';
        
        return implode(' ', $names);
    }

    private function getSignatureDate()
    {
        $unixTimeStamp = strtotime($this->getSurrender()["digitalSignature"]['createdOn']);
        return date("j M Y", $unixTimeStamp);
    }

    private function returnDashboardLink(): string
    {
        return $this->url()->fromRoute('dashboard');
    }


}
