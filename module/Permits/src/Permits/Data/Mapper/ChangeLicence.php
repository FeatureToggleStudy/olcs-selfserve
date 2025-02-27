<?php

namespace Permits\Data\Mapper;

use Common\Exception\BadRequestException;
use Common\Service\Helper\TranslationHelperService;
use Permits\Controller\Config\DataSource\LicencesAvailable as LicencesAvailableDataSource;

/**
 *
 * Change Licence mapper
 */
class ChangeLicence
{
    /**
     * @param array $allData
     *
     * @return bool
     * @throws BadRequestException
     */
    public static function validateData(array $allData): bool
    {
        $data = $allData[LicencesAvailableDataSource::DATA_KEY];

        // Get type from application data
        $isEcmt = isset($allData['application']['permitType']);
        $isBilateral = $isEcmt ? false : $allData['application']['irhpPermitType']['name']['id'] === 'permit_annual_bilateral';


        if ($isEcmt && !$data['hasAvailableEcmtLicences'] || $isBilateral && !$data['hasAvailableBilateralLicences']) {
            throw new BadRequestException('No available licences.');
        }

        $selectedLicenceEligible = array_search($allData['licence'], array_column($data['eligibleLicences']['result'], 'id'));

        if ($selectedLicenceEligible === false) {
            throw new BadRequestException('User does not own selected licence.');
        } elseif ($isEcmt && !$data['eligibleLicences']['result'][$selectedLicenceEligible]['canMakeEcmtApplication']) {
            throw new BadRequestException('Selected licence already has an active application.');
        } elseif ($isBilateral && !$data['eligibleLicences']['result'][$selectedLicenceEligible]['canMakeBilateralApplication']) {
            throw new BadRequestException('Selected licence already has an active application.');
        }

        return true;
    }

    /**
     * @param array                    $data
     * @param                          $form
     * @param TranslationHelperService $translator
     *
     * @return array
     * @throws BadRequestException
     */
    public static function mapForFormOptions(array $data, $form, TranslationHelperService $translator)
    {
        if (self::validateData($data)) {
            $mapData = $data[LicencesAvailableDataSource::DATA_KEY];

            $selectedLicenceEligible = array_search(
                $data['licence'],
                array_column($mapData['eligibleLicences']['result'], 'id')
            );

            $confirmChangeLabel = $translator->translateReplace(
                'permits.form.change_licence.label',
                [$mapData['eligibleLicences']['result'][$selectedLicenceEligible]['licNo']]
            );

            $form->get('fields')->get('ConfirmChange')->setLabel(
                $confirmChangeLabel
            );

            $form->setData(['fields' => ['licence' => $data['licence']]]);

            return $data;
        }
    }
}
