<?php

namespace OLCS\Controller\Lva\TransportManager;

use Olcs\Controller\Lva\Traits\ExternalControllerTrait;
use Dvsa\Olcs\Transfer\Command;

class TmDeclarationController extends AbstractDeclarationController
{

    protected $declarationMarkup = 'markup-tma-tm_declaration';

    protected function getSignAsRole(): string
    {
        return $this->tma['isOwner'] === "Y" ? self::SIGN_AS_TM_OP : self::SIGN_AS_TM;
    }

    /**
     * Get the URL/link to go back
     *
     * @param array $tma
     *
     * @return string
     */
    protected function getBackLink(): string
    {
        return $this->url()->fromRoute(
            'lva-' . $this->lva . '/transport_manager_check_answer',
            [
                'action' => 'index',
                'child_id' => $this->tma["id"],
                'application' => $this->tma["application"]["id"]
            ]
        );
    }

    /**
     * @param $tma
     * @return \Common\Service\Cqrs\Response
     */
    protected function handlePhysicalSignatureCommand(): \Common\Service\Cqrs\Response
    {
        $response = $this->handleCommand(
            Command\TransportManagerApplication\Submit::create(['id' => $this->tma['id']])
        );
        return $response;
    }

    /**
     * @return string
     */
    protected function getSubmitActionLabel(): string
    {
        $submitText = $this->tma['isOwner'] === "Y" ? 'submit' : 'submit-for-operator-approval';

        $label = $this->tma['disableSignatures'] === false
            ? 'application.review-declarations.sign-button'
            : $submitText;
        return $label;
    }
}
