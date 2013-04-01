<?php
return array(
    'elements' => array(
        'username' => array(
            'type' => 'text',
        ),
        'password' => array(
            'type' => 'password',
        ),
        'rememberMe' => array(
            'type' => 'checkbox',
        ),
    ),
    'buttons' => array(
        'submit' => array(
            'type' => 'submit',
            'layoutType' => 'primary',
            'label' => Yii::t('app', 'Submit'),
        ),
    ),
);