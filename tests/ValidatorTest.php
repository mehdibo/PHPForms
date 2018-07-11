<?php

namespace \PHPForms\Tests;

class ValidatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Form object
     *
     * @var \PHPForms\Form
     */
    private $form;

    /**
     * Validations object
     *
     * @var \PHPForms\Validations
     */
    private $validations;

    /**
     * Create the Form and Validations objects
     *
     * @return void
     */
    public function setUp()
    {
        $fields = [
            'first_name' => [
                'label' => 'First name',
                'type'  => 'text',
                'rules' => 'required|inList[option1,option2]'
            ],
            'last_name' => [
                'label' => 'Last name',
                'type'  => 'text',
                'rules' => 'required'
            ],
            'street' => [
                'label' => 'Street',
                'type'  => 'test',
                'rules' => 'required|inList[valid1,valid2]'
            ]
        ];

        $this->form = new \PHPForms\Form($fields);
        $errors = ['required' => 'Required error msg.', 'inList' => 'inList error msg.'];
        $this->validations = new \PHPForms\Validations($errors);
    }

    public function testValidationIsWorking()
    {
        // Validate should return TRUE because the rules were respected
        $data = [
            'first_name' => 'option1',
            'last_name'  => 'Bounya',
            'street'  => 'valid1',
        ];

        $validator = new \PHPForms\Validator($this->form, $data, $this->validations);

        $this->assertEquals($validator->validate(), true);

        // Validate should return FALSE because the rules were not respected
        $data = [
            'first_name' => 'option3',
            'last_name'  => 'Bounya',
            'street'  => 'valid1',
        ];

        $validator = new \PHPForms\Validator($this->form, $data, $this->validations);

        $this->assertEquals($validator->validate(), false);
    }

    public function testWeCanGetValidData()
    {
        // We should get a part of the data because it passed the rules
        $data = [
            'first_name' => 'option1',
            'last_name'  => 'Bou',
            'street' => 'invalid option',
        ];

        $validator = new \PHPForms\Validator($this->form, $data, $this->validations);

        $validator->validate();

        $expected_data = [
            'first_name' => 'option1',
            'last_name' => 'Bou',
        ];

        $this->assertEquals($validator->getValidData(), $expected_data);

        // No data should be returned because we didn't respect any rules
        $data = [
            'first_name' => 'invalid option',
            'last_name'  => '',
            'street' => 'invalid option',
        ];

        $validator = new \PHPForms\Validator($this->form, $data, $this->validations);

        $validator->validate();

        $expected_data = [];

        $this->assertEquals($validator->getValidData(), $expected_data);
    }

    public function testWeCanGetErrorMessages()
    {
        // We should get two error messages because some data didn't pass the rules
        $data = [
            'first_name' => 'option1',
            'last_name'  => '',
            'street' => 'invalid option',
        ];

        $validator = new \PHPForms\Validator($this->form, $data, $this->validations);

        $validator->validate();

        $expected_data = ['Required error msg.', 'inList error msg.'];

        $this->assertEquals($validator->getErrors(), $expected_data);

        // No error message because the rules were respected
        $data = [
            'first_name' => 'option1',
            'last_name'  => 'X',
            'street' => 'valid1',
        ];

        $validator = new \PHPForms\Validator($this->form, $data, $this->validations);

        $validator->validate();

        $expected_data = [];

        $this->assertEquals($validator->getErrors(), $expected_data);
    }
}
