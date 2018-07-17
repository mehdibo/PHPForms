<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Include composer's autoloader
include "vendor/autoload.php";

// Create form's fields
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
        'rules'    => 'ignore',
    ],
];

// Form's attributes, the method by default is POST
$attributes = ['action' => './example.php'];

$form = new \PHPForms\Form($fields, $attributes);

// If the form is submitted
if (!empty($_POST)) {
    // We get the error messages and create the validations object
    $errors = include "./src/errors.php";
    $validations = new \PHPForms\Validations($errors);

    // We create the Validator object and pass the form, post data and Validations
    $validator = new \PHPForms\Validator($form, $_POST, $validations);

    if (!$validator->isValid()) {
        // If the data did not pass the validation rules
        echo '<h4>Errors:</h4>';
        // We print the errors
        foreach ($validator->getErrors() as $error) {
            echo htmlspecialchars($error).'<br />';
        }
    } else {
        // Data passed the validation rules
        // We get the valid data
        $data = $validator->getValidData();
        // Create an ExportCSV object and we pass to it the file name and the data
        $exportCSV = new \PHPForms\ExportCSV('./data.csv', $data);
        // We want to map the fields to different column names
        $exportCSV->setMap([
            'name' => 'First name',
            'email' => 'E-mail',
            'age'   => 'Age',
            'message' => 'Comment',
        ]);

        // And then we export the data
        if ($exportCSV->export()) {
            echo 'Data exported successfully!<br />';
        } else {
            echo 'Something went wrong while exporting the data.<br />';
        }
    }
}

// We create the form
// Remember that this is not mandatory, you can create a form manually
// Just remember to use the same field names as defined in the Form object
echo $form->getHTML();
