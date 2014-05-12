<?php
return [
    'review' => [
        'name' => 'review',

        // this key means whatever happens we'll always
        // disable every element within the form
        'disabled' => true,

        'attributes' => [
            'class' => 'read-only',
        ],
        'fieldsets' => [
            [
                'name' => 'title',
                'elements' => [
                    'title' => [
                        'type' => 'hidden',
                        'label' => '<h2>1. Type of licence</h2>',
                    ]
                ],
            ],
            [
                'type' => 'operator-location'
            ],
            [
                'type' => 'operator-type',
                'options' => [
                    // we want to suppress the default label within
                    // this particular form. A blank label won't render
                    // an empty <legend> element, don't worry
                    'label' => '',
                ],
            ],
            [
                'type' => 'licence-type',
            ],
        ],
    ],
];
