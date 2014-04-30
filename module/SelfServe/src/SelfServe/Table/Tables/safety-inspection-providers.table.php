<?php

return array(
    'variables' => array(
        'title' => 'Safety inspection providers',
        'empty_message' => 'Please tell us about who will carry out the safety inspections on the vehicles and trailers
            you intend to operate under your licence. <a href="#">Add your first safety inspector</a>',
        'within_form' => true
    ),
    'settings' => array(
        'crud' => array(
            'actions' => array(
                'add' => array('class' => 'primary'),
                'edit' => array('requireRows' => true),
                'delete' => array('class' => 'warning', 'requireRows' => true)
            )
        )
    ),
    'attributes' => array(
    ),
    'columns' => array(
        array(
            'title' => '',
            'width' => 'checkbox',
            'format' => '{{[elements/radio]}}'
        ),
        array(
            'title' => 'Provider\'s name',
            'name' => 'organisation'
        ),
        array(
            'title' => 'External?',
            'name' => 'isExternal',
            'formatter' => 'YesNo'
        ),
        array(
            'title' => 'Workshop address',
            'formatter' => 'Address'
        )
    )
);
