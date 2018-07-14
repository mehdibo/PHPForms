# PHPForms [![Build status](https://api.travis-ci.org/mehdibo/PHPForms.svg)](https://travis-ci.org/mehdibo/PHPForms) [![Total Downloads](https://poser.pugx.org/mehdibo/phpforms/downloads)](https://packagist.org/packages/mehdibo/phpforms) [![Latest Stable Version](https://poser.pugx.org/mehdibo/phpforms/v/stable)](https://packagist.org/packages/mehdibo/phpforms) [![License](https://poser.pugx.org/mehdibo/phpforms/license)](https://packagist.org/packages/mehdibo/phpforms)
This library or set of tools is for people who want to setup a form, validate it and get the data as quick as possible, currently it needs some testing so all feedback is welcome.
## Contents

 - [Usage](#usage)
    * [Creating the form](#creating-the-form)
    * [Validating data](#validating-data)
    * [Exporting/Saving data](#exporting-data)
 - [Wiki](https://github.com/mehdibo/PHPForms/wiki)

### Creating the form

The first step is to create a `\PHPForms\Form()` object, this object represents your form and its fields.
```php
// These are the form's fields
$fields = [
    // This is the name's attribute value
    'field_name' => [ 
        // This label is used as a placeholder when creating the HTML code
        'label' => 'Label goes here', 
        // Input's type, you can also create a textarea by setting it to textarea
        'type'  => 'text',
        // List of validation rules for this field
        'rules' => 'rule1|rule2[arg1, arg2]',
        // An array of attributes to use when creating the HTML code
        'attribs' => [
            'attrib_name' => 'attrib-value',
        ],
    ],
];

// Form attributes are used when creating the HTML code (optional)
$attributes = [
    'attrib_name' => 'attrib-calue',
];

$form = new \PHPForms\Form($fields, $attributes);
```

Now that you created your form, you can (optionally) generate its HTML code using.

```php
$form->getHTML();
```

### Validating data

After creating the form, you can validate the submitted data using `\PHPForms\Validator()`.

```php
$validator = new \PHPForms\Validator($form, $data, $validations);
```

The `$form` is the `\PHPForms\Form()` object you created earlier. `$data` is the submitted data, usually, you will want to pass `$_POST` to the Validator.

`$validations` is an object that extends the `\PHPForms\Validation()` abstract class and contains the validation rules, PHPForms comes already with a set of rules in the `\PHPForms\Validations()` class.

```php
$validations = new \PHPForms\Validations($errors);
```

The `$errors` variable should contain an array of error messages, there is already a set of errors in English, you can use it like this:

```php
$errors = include "src/errors.php";
```

After that you can call the `isValid()` method to validate the data:

```php
$validator->isValid(); // Returns TRUE if all data is valid
```

If there were any errors (validation errors) you can get them using `getErrors()`:

```php
$validator->getErrors(); // Array of errors or NULL
```

You can get the valid data using `getValidData()`:

```php
$validator->getValidData(); // Array of 'field_name' => 'value' or NULL
```

This method will only return data which passed all the validation rules.
`getErrors()` and `getValidData()` should only be called after calling `isValid()`

### Exporting data

You can export/save the data using an exporter.

Currently, PHPForms comes with two exporters, `ExportCSV` and `ExportDB`.
They both extend the `\PHPForms\Exporter()` abstract class.

In this example we'll use `ExportCSV`:

```php
// ...After creating the form and the validator from the earlier sections
// We check if the data is valid
if ($validator->isValid()) {
	// Takes two arguments
	// CSV file path (will be created if not found)
	$file = './data.csv';
	// Array of data to export ('field_name' => 'Value')
	$data = $validator->getValidData();
	$csv = new \PHPForms\ExportCSV($file, $data);
	$csv->export(); // Returns TRUE if exported or FALSE otherwise
}
```

This will result in a CSV file containing the data submitted in the form. If the file already contains data it'll be appended to it.
If you want to map field names to different column names use the `setMap()` method:

```php
$map = [
	'field_name' => 'column_name'
];
$csv->setMap($map);
```

For more usage details check the [wiki](https://github.com/mehdibo/PHPForms/wiki/Usage)

## Contributing
All feedback and contributions are welcome, just make sure you read the [Contributing guidelines](CONTRIBUTING.md)
