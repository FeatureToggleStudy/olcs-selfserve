<?php

namespace Permits\Data\Mapper;

use Common\Form\Elements\Types\HtmlTranslated;
use Common\RefData;
use Common\Service\Helper\TranslationHelperService;
use Permits\Controller\Config\DataSource\LicencesAvailable as LicencesAvailableDataSource;

/**
 *
 * Available Licences mapper
 */
class LicencesAvailable
{
    const ECMT_RESTRICTED_HINT = 'permits.form.ecmt-licence.restricted-licence.hint';
    const ECMT_QUESTION_ONE_LICENCE = 'permits.page.licence.question.one.licence';

    /**
     * @param array $data
     * @param       $form
     *
     * @param TranslationHelperService $translator
     * @return array
     */
    public static function mapForFormOptions(array $data, $form)
    {
        $mapData = $data[LicencesAvailableDataSource::DATA_KEY];
        $isNew = !isset($data['application']['licence']);
        $irhpPermitTypeID = RefData::ECMT_PERMIT_TYPE_ID;

        if (isset($data['application']['irhpPermitType']['id'])) {
            $irhpPermitTypeID = $data['application']['irhpPermitType']['id'];
        } elseif (isset($data['irhpPermitType']['id'])) {
            $irhpPermitTypeID = $data['irhpPermitType']['id'];
        }

        // A variable to add future types if required
        $displayAlreadyApplied = in_array(
            $irhpPermitTypeID,
            [
                RefData::IRHP_BILATERAL_PERMIT_TYPE_ID,
                RefData::IRHP_MULTILATERAL_PERMIT_TYPE_ID,
                RefData::ECMT_REMOVAL_PERMIT_TYPE_ID,
            ]
        );

        $valueOptions = [];

        foreach ($mapData['eligibleLicences']['result'] as $option) {
            $selected = !$isNew ? $option['id'] === $data['application']['licence']['id'] && empty($data['active']) : false;

            if ($irhpPermitTypeID === RefData::ECMT_PERMIT_TYPE_ID) {
                if (!$option['canMakeEcmtApplication']
                    && ($isNew || $option['id'] !== $data['application']['licence']['id'])) {
                    continue;
                }

                if ($option['licenceType']['id'] === RefData::LICENCE_TYPE_RESTRICTED) {
                    $valueOptions[$option['id']][$option['id'].'Content'] = 'en_GB/markup-ecmt-restricted-licence-conditional';
                    $content = new HtmlTranslated($option['id'] . 'Content');
                    $content->setValue(self::ECMT_RESTRICTED_HINT);
                    $form->get('fields')->add($content);
                }
            } elseif ($displayAlreadyApplied && isset($data['active']) && $option['id'] == $data['active']) {
                $data['warning'] = 'permits.irhp.bilateral.already-applied';
                $selected = true;
            }

            $valueOptions[$option['id']] = [
                'value' => $option['id'],
                'label' => $option['licNo'],
                'label_attributes' => ['class' => 'govuk-label govuk-radios__label govuk-label--s'],
                'hint' => $option['licenceType']['description'] . ' (' . $option['trafficArea'] . ')',
                'selected' => $selected,
            ];
        }

        if ($irhpPermitTypeID === RefData::ECMT_PERMIT_TYPE_ID && count($valueOptions) === 1) {
            $key = array_keys($valueOptions)[0];
            $data['question'] = self::ECMT_QUESTION_ONE_LICENCE;
            $data['questionArgs'] = [$valueOptions[$key]['label'] . ' ' . $valueOptions[$key]['hint']];
            $valueOptions[$key]['selected'] = true;
            $form->get('fields')->get('licence')->setAttribute(
                'radios_wrapper_attributes',
                ['class' => 'visually-hidden']
            );
        }

        $form->get('fields')->get('licence')->setValueOptions($valueOptions);

        return $data;
    }
}
