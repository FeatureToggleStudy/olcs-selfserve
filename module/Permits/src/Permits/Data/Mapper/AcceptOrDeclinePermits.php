<?php

namespace Permits\Data\Mapper;

use Common\Service\Helper\TranslationHelperService;
use Common\View\Helper\CurrencyFormatter;
use Permits\View\Helper\EcmtSection;
use Zend\Mvc\Controller\Plugin\Url;

/**
 * Mapper for the FeePartSuccessful / Accept or Decline page
 */
class AcceptOrDeclinePermits
{
    /**
     * Maps data appropriately for the Definition List on the FeePartSuccessful page
     *
     * @param array $data an array of data retrieved from the backend
     * @param TranslationHelperService $translator
     * @param Url $url
     *
     * @return array
     */
    public static function mapForDisplay(array $data, TranslationHelperService $translator, Url $url)
    {
        //ini_set('xdebug.var_display_max_depth', '10');
        //var_dump($data);
        //exit();

        $data = ApplicationFees::mapForDisplay($data, $translator, $url);
        $summaryData = self::getBaseSummaryData($data, $translator, $url);

        $data['title'] = 'waived-paid-permits.page.fee-part-successful.title';
        $dueDateKey = 'waived.paid.permits.page.ecmt.fee-part-successful.payment.due';

        if ($data['hasOutstandingFees']) {
            $data['title'] = 'permits.page.fee-part-successful.title';
            $dueDateKey = 'permits.page.ecmt.fee-part-successful.payment.due';

            $summaryData[] = [
                'key' => 'permits.page.ecmt.fee-part-successful.issuing.fee',
                'value' => $data['issueFee'],
                'isCurrency' => true
            ];

            $currency = new CurrencyFormatter();
            $summaryData[] = [
                'key' => 'permits.page.ecmt.fee-part-successful.issuing.fee.total',
                'value' => $translator->translateReplace(
                    'permits.page.ecmt.fee-part-successful.fee.total.value',
                    [
                        $currency($data['totalFee'])
                    ]
                )
            ];
        }

        $summaryData[] = [
            'key' => $dueDateKey,
            'value' => date(\DATE_FORMAT, strtotime($data['dueDate']))
        ]; 

        $data['summaryData'] = $summaryData;
        $data['guidance'] = self::getGuidanceData($data, $translator);

        return $data;
    }

    /**
     * Get the base summary list data common to all outcomes
     *
     * @param array $data
     * @param TranslationHelperService $translator
     * @param Url $url
     *
     * @return array
     */
    private static function getBaseSummaryData(array $data, TranslationHelperService $translator, Url $url)
    {
        $firstIrhpPermitApplication = $data['irhpPermitApplications'][0];
        $irhpPermitStock = $firstIrhpPermitApplication['irhpPermitWindow']['irhpPermitStock'];

        return [
            [
                'key' => 'permits.page.ecmt.consideration.application.reference',
                'value' => $data['applicationRef']
            ],
            [
                'key' => 'permits.page.ecmt.consideration.permit.type',
                'value' => $data['permitType']['description']
            ],
            [
                'key' => 'permits.page.ecmt.consideration.permit.year',
                'value' => date('Y', strtotime($irhpPermitStock['validTo']))
            ],
            [
                'key' => 'permits.page.ecmt.consideration.number.of.permits',
                'value' => self::getNoOfPermitsValue($firstIrhpPermitApplication, $translator, $url),
                'disableHtmlEscape' => true
            ]
        ];
    }

    /**
     * Get the html representing the number of permits
     *
     * @param array $firstIrhpPermitApplication
     * @param TranslationHelperService $translator
     * @param Url $url
     *
     * @return array
     */
    private static function getNoOfPermitsValue(
        array $firstIrhpPermitApplication,
        TranslationHelperService $translator,
        Url $url
    ) {
        $ecmtNoOfPermitsData = [
            'requiredEuro5' => $firstIrhpPermitApplication['euro5PermitsAwarded'],
            'requiredEuro6' => $firstIrhpPermitApplication['euro6PermitsAwarded']
        ];

        $permitsRequiredLines = EcmtNoOfPermits::mapForDisplay($ecmtNoOfPermitsData, $translator, $url);

        $permitsRequiredLines[] = sprintf(
            '<a href="%s">%s</a>',
            $url->fromRoute(EcmtSection::ROUTE_ECMT_UNPAID_PERMITS, [], [], true),
            $translator->translate('permits.page.ecmt.fee-part-successful.view.permit.restrictions')
        ); 

        return implode('<br>', $permitsRequiredLines);
    }

    /**
     * Get the html representing the guidance area of the page
     *
     * @param array $data
     * @param TranslationHelperService $translator
     *
     * @return array
     */
    private static function getGuidanceData(array $data, TranslationHelperService $translator)
    {
        $permitsAwarded = $data['irhpPermitApplications'][0]['permitsAwarded'];
        $permitsRequired = $data['permitsRequired'];

        $guidanceKey = 'markup-ecmt-fee-part-successful-hint';
        if ($permitsAwarded == $permitsRequired) {
            $guidanceKey = 'markup-ecmt-fee-successful-hint';
        }

        return [
            'value' => $translator->translateReplace(
                $guidanceKey,
                [$permitsAwarded, $permitsRequired]
            ),
            'disableHtmlEscape' => true
        ];
    }
}
