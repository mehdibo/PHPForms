<?php

error_reporting(-1);
ini_set('display_errors', 1);

include "vendor/autoload.php";

$fields = [
    'name' => [
        'label'   => 'Name',
        'type'    => 'text',
        'rules'   => 'required|maxLength[25]',
        'attribs' => [
            'class' => 'a_class',
        ],
    ],
    'email' => [
        'label'   => 'E-mail',
        'type'    => 'email',
        'rules'   => 'validEmail',
    ],
    'age' => [
        'label'   => 'Age',
        'type'    => 'number',
        'rules'   => 'required|greaterThan[17]|lessThan[61]',
    ],
    'message' => [
        'label'   => 'Message',
        'type'    => 'textarea',
        'rules'   => 'required',
    ],
    'submit' => [
        'label'   => 'Submit',
        'type'    => 'submit',
    ],
];

$form = new \PHPForms\Form($fields, ['action' => './example.php']);

// Validate form
if (!empty($_POST)) {
    $errors = include "./Core/errors.php";
    $validations = new \PHPForms\Validations($errors);
    $validator = new \PHPForms\Validator($form, $_POST, $validations);

    $validator->validate();

    var_dump($validator->getErrors(), $validator->getValidData());
}


echo $form->getHTML();
