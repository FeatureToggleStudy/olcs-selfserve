<?php

namespace Permits\Data\Mapper;

use Common\View\Helper\CurrencyFormatter;
use Common\Service\Helper\TranslationHelperService;

/**
 * Mapper for the FeePartSuccessful / Accept or Decline page
 */
class AcceptOrDeclinePermits
{
    /**
     * Maps data appropriately for the Definition List on the FeePartSuccessful page
     *
     * @param array $data an array of data retrieved from the backend
     * @param TranslationHelperService translation helper
     * @return array
     */
    public static function mapForDisplay(array $data, TranslationHelperService $translatorSrv)
    {
        $currencyFormatter = new CurrencyFormatter;
        $data = ApplicationFees::mapForDisplay($data);

        $data['items'] = [
            0 => [
                'value' =>  $data['applicationRef'],
                'label' => 'permits.page.ecmt.consideration.reference.number'
            ],
            1 => [
                'value' =>  $data['permitType']['description'],
                'label' => 'permits.page.ecmt.consideration.permit.type'
            ],
            2 => [
                'value' =>  self::formatValidityPeriod($data),
                'label' => 'permits.page.ecmt.fee-part-successful.permit.validity'
            ],
            3 => [
                'value' =>  $data['irhpPermitApplications'][0]['permitsAwarded'] . ' x ' . $currencyFormatter($data['issueFee']),
                'label' => 'permits.page.ecmt.fee-part-successful.issuing.fee'
            ],
            3 => [
                'value' =>  $currencyFormatter($data['totalFee']) . ' ' . $translatorSrv->translate('permits.page.non.refundable'),
                'label' => 'permits.page.ecmt.fee-part-successful.issuing.fee.total'
            ],
            4 => [
                'date' =>  $data['dueDate'],
                'label' => 'permits.page.ecmt.fee-part-successful.payment.due'
            ]
        ];

        return $data;
    }

    /**
     * formats the validity period for display
     *
     * @param array a collection of application data as returned from backend
     * @return string the validity period formatted for display
     */
    private static function formatValidityPeriod($data)
    {
        $fromDate = date(\DATE_FORMAT, strtotime($data['irhpPermitApplications'][0]['irhpPermitWindow']['irhpPermitStock']['validFrom']));
        $toDate = date(\DATE_FORMAT, strtotime($data['irhpPermitApplications'][0]['irhpPermitWindow']['irhpPermitStock']['validTo']));

        return $fromDate . ' to ' . $toDate; //@todo: need to translate the word 'to'
    }
}
