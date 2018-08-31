<?php

return array(
    'variables' => array(
        'title' => '',
        'empty_message' => '',
        'hide_column_headers' => true,
    ),
    'settings' => array(),
    'attributes' => array('class' => 'fee-part-successful'),
    'columns' => array(
        array(
            'title' => 'applicationDetailsTitle',
            'name' => 'applicationDetailsTitle',
            'formatter' => function ($row, $column, $sm) {
                return '<b>' . $sm->get('translator')
                        ->translate($row['applicationDetailsTitle']) . '</b>';
            },
        ),
        array(
            'title' => 'applicationDetailsAnswer',
            'name' => 'applicationDetailsAnswer',
            'formatter' => 'Translate'
        )
    )
);
