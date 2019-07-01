<?php

namespace PermitsTest\Data\Mapper;

use Common\RefData;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Permits\Data\Mapper\CheckAnswers;

class CheckAnswersTest extends MockeryTestCase
{
    public function testMapForDisplay()
    {
        $inputData = [
            'application' => [
                'cabotage' => 1,
                'checkedAnswers' => false,
                'countrys' => [],
                'declaration' => false,
                'emissions' => 1,
                'hasRestrictedCountries' => false,
                'internationalJourneys' => [
                    'description' => 'More than 90%',
                ],
                'licence' => [
                    'licNo' => 'OG4563323',
                    'trafficArea' => [
                        'name' => 'North East of England',
                    ],
                ],
                'permitType' => [
                    'description' => 'Annual ECMT',
                    'id' => 'permit_ecmt',
                ],
                'permitsRequired' => 5,
                'sectors' => [
                    'name' => 'Mail and parcels',
                ],
                'trips' => 43,
                'applicationRef' => 'OG4563323 / 4',
                'canCheckAnswers' => true,
                'hasCheckedAnswers' => false,
                'isNotYetSubmitted' => true,
                'irhpPermitApplications' => [
                    0 => [
                        'irhpPermitWindow' => [
                            'irhpPermitStock' => [
                                'validTo' => '2029-12-25'
                            ]
                        ]
                    ]
                ]
            ],
        ];

        $expected = [
            'canCheckAnswers' => true,
            'answers' => [
                [
                    'question' => 'permits.page.fee.permit.type',
                    'route' => null,
                    'answer' => 'Annual ECMT',
                    'questionType' => null,
                    'params' => [],
                    'options' => [],
                    'escape' => true,
                ],
                [
                    'question' => 'permits.check-answers.page.question.licence',
                    'route' => 'permits/licence',
                    'answer' => [
                        0 => 'OG4563323',
                        1 => 'North East of England',
                    ],
                    'questionType' => RefData::QUESTION_TYPE_STRING,
                    'params' => [],
                    'options' => [],
                    'escape' => true,
                ],
                [
                    'question' => 'permits.form.euro-emissions.label',
                    'route' => 'permits/ecmt-emissions',
                    'answer' => 1,
                    'questionType' => RefData::QUESTION_TYPE_BOOLEAN,
                    'params' => [],
                    'options' => [],
                    'escape' => true,
                ],
                [
                    'question' => 'permits.form.cabotage.label',
                    'route' => 'permits/ecmt-cabotage',
                    'answer' => 1,
                    'questionType' => RefData::QUESTION_TYPE_BOOLEAN,
                    'params' => [],
                    'options' => [],
                    'escape' => true,
                ],
                [
                    'question' => 'permits.page.restricted-countries.question',
                    'route' => 'permits/ecmt-countries',
                    'answer' => 'No',
                    'questionType' => RefData::QUESTION_TYPE_STRING,
                    'params' => [],
                    'options' => [],
                    'escape' => true,
                ],
                [
                    'question' => 'permits.page.permits.required.question',
                    'route' => 'permits/ecmt-no-of-permits',
                    'answer' => 5,
                    'questionType' => RefData::QUESTION_TYPE_STRING,
                    'params' => [],
                    'options' => [],
                    'escape' => false,
                ],
                [
                    'question' => 'permits.page.number-of-trips.question',
                    'route' => 'permits/ecmt-trips',
                    'answer' => 43,
                    'questionType' => RefData::QUESTION_TYPE_INTEGER,
                    'params' => [],
                    'options' => [],
                    'escape' => true,
                ],
                [
                    'question' => 'permits.page.international.journey.question',
                    'route' => 'permits/ecmt-international-journey',
                    'answer' => 'More than 90%',
                    'questionType' => RefData::QUESTION_TYPE_STRING,
                    'params' => [],
                    'options' => [],
                    'escape' => true,
                ],
                [
                    'question' => 'permits.page.sectors.question',
                    'route' => 'permits/ecmt-sectors',
                    'answer' => 'Mail and parcels',
                    'questionType' => RefData::QUESTION_TYPE_STRING,
                    'params' => [],
                    'options' => [],
                    'escape' => true,
                ],
            ],
            'applicationRef' => 'OG4563323 / 4'
        ];

        self::assertEquals($expected, CheckAnswers::mapForDisplay($inputData));
    }

    public function testMapForDisplayWithCountries()
    {
        $inputData = [
            'application' => [
                'cabotage' => 1,
                'checkedAnswers' => false,
                'countrys' => [['id' => 'AT', 'countryDesc' => 'Austria']],
                'declaration' => false,
                'emissions' => 1,
                'hasRestrictedCountries' => true,
                'internationalJourneys' => [
                    'description' => 'More than 90%',
                ],
                'licence' => [
                    'licNo' => 'OG4563323',
                    'trafficArea' => [
                        'name' => 'North East of England',
                    ],
                ],
                'permitType' => [
                    'description' => 'Annual ECMT',
                    'id' => 'permit_ecmt',
                ],
                'permitsRequired' => 5,
                'sectors' => [
                    'name' => 'Mail and parcels',
                ],
                'trips' => 43,
                'applicationRef' => 'OG4563323 / 4',
                'canCheckAnswers' => true,
                'hasCheckedAnswers' => false,
                'isNotYetSubmitted' => true,
                'irhpPermitApplications' => [
                    0 => [
                        'irhpPermitWindow' => [
                            'irhpPermitStock' => [
                                'validTo' => '2029-12-25'
                            ]
                        ]
                    ]
                ]
            ],
        ];

        $expected = [
            'canCheckAnswers' => true,
            'answers' => [
                [
                    'question' => 'permits.page.fee.permit.type',
                    'route' => null,
                    'answer' => 'Annual ECMT',
                    'questionType' => null,
                    'params' => [],
                    'options' => [],
                    'escape' => true,
                ],
                [
                    'question' => 'permits.check-answers.page.question.licence',
                    'route' => 'permits/licence',
                    'answer' => [
                        0 => 'OG4563323',
                        1 => 'North East of England',
                    ],
                    'questionType' => RefData::QUESTION_TYPE_STRING,
                    'params' => [],
                    'options' => [],
                    'escape' => true,
                ],
                [
                    'question' => 'permits.form.euro-emissions.label',
                    'route' => 'permits/ecmt-emissions',
                    'answer' => 1,
                    'questionType' => RefData::QUESTION_TYPE_BOOLEAN,
                    'params' => [],
                    'options' => [],
                    'escape' => true,
                ],
                [
                    'question' => 'permits.form.cabotage.label',
                    'route' => 'permits/ecmt-cabotage',
                    'answer' => 1,
                    'questionType' => RefData::QUESTION_TYPE_BOOLEAN,
                    'params' => [],
                    'options' => [],
                    'escape' => true,
                ],
                [
                    'question' => 'permits.page.restricted-countries.question',
                    'route' => 'permits/ecmt-countries',
                    'answer' => ['Yes', 'Austria'],
                    'questionType' => RefData::QUESTION_TYPE_STRING,
                    'params' => [],
                    'options' => [],
                    'escape' => true,
                ],
                [
                    'question' => 'permits.page.permits.required.question',
                    'route' => 'permits/ecmt-no-of-permits',
                    'answer' => 5,
                    'questionType' => RefData::QUESTION_TYPE_STRING,
                    'params' => [],
                    'options' => [],
                    'escape' => false,
                ],
                [
                    'question' => 'permits.page.number-of-trips.question',
                    'route' => 'permits/ecmt-trips',
                    'answer' => 43,
                    'questionType' => RefData::QUESTION_TYPE_INTEGER,
                    'params' => [],
                    'options' => [],
                    'escape' => true,
                ],
                [
                    'question' => 'permits.page.international.journey.question',
                    'route' => 'permits/ecmt-international-journey',
                    'answer' => 'More than 90%',
                    'questionType' => RefData::QUESTION_TYPE_STRING,
                    'params' => [],
                    'options' => [],
                    'escape' => true,
                ],
                [
                    'question' => 'permits.page.sectors.question',
                    'route' => 'permits/ecmt-sectors',
                    'answer' => 'Mail and parcels',
                    'questionType' => RefData::QUESTION_TYPE_STRING,
                    'params' => [],
                    'options' => [],
                    'escape' => true,
                ],
            ],
            'applicationRef' => 'OG4563323 / 4'
        ];

        self::assertEquals($expected, CheckAnswers::mapForDisplay($inputData));
    }
}
