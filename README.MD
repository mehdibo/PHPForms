# PHPForms
This library or set of tools is for people who want to setup a form, validate it and get the data as quick as possible, currently it needs some testing so all feedback is welcome.
## Contents

 - [Usage](#usage)

## Usage
### Creating the form
The first step is to create a `\PHPForms\Form()` object, this object represents your form and it's fields.
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
Now that you created your form, you can (optionally) generate it's HTML code using.

    $form->getHTML();
### Validating data
After creating the form, you can validate the submitted data using the `\PHPForms\Validator()` .
```php
$validator = new \PHPForms\Validator($form, $data, $validations);
```
The `$form` is the `\PHPForms\Form()` object you created earlier, `$data` is the submitted data and it is probably the `$_POST` data submitted by the user.

`$validations` is any object that extends the `\PHPForms\Validation()` abstract class and contains the validation rules, PHPForms comes already with a set of rules in the `\PHPForms\Validations()` class.
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
You can export/save the data using an exportercurrently PHPForms comes with two exporters, `ExportCSV` and `ExportDB`.
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
This will result in a CSV file containing the data submitted in the form, if the file already contains data it'll be appended to it.
If you want to map field names to different column names use the `setMap()` method:
```php
$map = [
	'field_name' => 'column_name'
];
$csv->setMap($map);
```
