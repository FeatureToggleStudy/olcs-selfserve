<?php

namespace Permits\Data\Mapper;

/**
 * @todo clearly this will need to be a lot better later - but will wait to see first if it's staying
 *
 * Application fee mapper
 */
class ApplicationFees
{
    /**
     * Maps fee data to easy to reference indexes
     *
     * @param array $data an array of data retrieved from the backend
     * @return array the same array as passed in except with additional indexes for fee data
     * @throws \RuntimeException
     */
    public static function mapForDisplay(array $data)
    {
        foreach ($data['fees'] as $fee) {
            if ($fee['isEcmtIssuingFee']) {
                //return first occurence as there should only be one
                $data['issueFee'] = $fee['feeType']['displayValue'];
                $data['totalFee'] = $fee['grossAmount'];
                // TODO - OLCS-21979 - move to the backend
                $data['dueDate'] = self::calculateDueDate($fee['invoicedDate']);

                return $data;
            }
        }

        throw new \RuntimeException(
            'No outstanding issuing fees were found.'
        );
    }

    /**
     * add 9 weekdays to the date
     * @param string $date
     * @return string
     */
    private static function calculateDueDate($date)
    {
        $dueDate = date(\DATE_FORMAT, strtotime('+9 weekdays', strtotime($date)));
        return $dueDate;
    }
}
