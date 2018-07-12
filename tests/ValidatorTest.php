<?php

namespace PHPForms\Tests;

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
            'label_exists' => [
                'label' => 'Label exists',
                'type'  => 'text',
                'rules' => 'required'
            ],
            'label_not_exists' => [
                'type'  => 'text',
                'rules' => 'required'
            ],
            'required_options' => [
                'label'  => 'Required options',
                'type'  => 'text',
                'rules' => 'required|inList[option1,option2]'
            ],
            'optional_options' => [
                'label'  => 'Optional options',
                'type'  => 'text',
                'rules' => 'inList[option1,option2]'
            ],
        ];

        $this->form = new \PHPForms\Form($fields);
        $errors = [
            'required' => '{field} is required',
            'inList' => '{field} must be one of these options: {params}',
        ];
        $this->validations = new \PHPForms\Validations($errors);
    }

    public function testValidationIsWorking()
    {
        $tests = [
            [
                'data' => [
                    'label_exists'      => 'This field is required',
                    'label_not_exists'  => 'Also this',
                    'required_options'  => 'option1',
                    'optional_options'  => '',
                ],
                'expected_result' => true,
            ],
            [
                'data' => [
                    'label_exists'      => 'Required',
                    'label_not_exists'  => 'Required too',
                    'required_options'  => 'option1',
                    'optional_options'  => 'option2',
                ],
                'expected_result' => true,
            ],
            [
                'data' => [
                    'label_exists'      => '',
                    'label_not_exists'  => 'Also this',
                    'required_options'  => 'option1',
                    'optional_options'  => '',
                ],
                'expected_result' => false,
            ],
            [
                'data' => [
                    'label_exists'      => '',
                    'label_not_exists'  => 'Also this',
                    'required_options'  => 'option1',
                    'optional_options'  => 'invalid_option',
                ],
                'expected_result' => false,
            ],
        ];

        foreach ($tests as $test) {
            $validator = new \PHPForms\Validator($this->form, $test['data'], $this->validations);
            $this->assertEquals($test['expected_result'], $validator->isValid());
        }
    }

    public function testWeCanGetValidData()
    {
        $tests = [
            [
                'data' => [
                    'label_exists'      => 'This field is required',
                    'label_not_exists'  => 'Also this',
                    'required_options'  => 'option1',
                    'optional_options'  => '',
                ],
                'expected_result' => [
                    'label_exists'      => 'This field is required',
                    'label_not_exists'  => 'Also this',
                    'required_options'  => 'option1',
                    'optional_options'  => '',
                ],
            ],
            [
                'data' => [
                    'label_exists'      => '',
                    'label_not_exists'  => '',
                    'required_options'  => 'option1',
                    'optional_options'  => '',
                ],
                'expected_result' => [
                    'required_options'  => 'option1',
                    'optional_options'  => '',
                ],
            ],
            [
                'data' => [
                    'label_exists'      => '',
                    'label_not_exists'  => '',
                    'required_options'  => '',
                    'optional_options'  => 'invalid_option',
                ],
                'expected_result' => [],
            ],
        ];

        foreach ($tests as $test) {
            $validator = new \PHPForms\Validator($this->form, $test['data'], $this->validations);
            $validator->isValid();
            $this->assertEquals($test['expected_result'], $validator->getValidData());
        }
    }

    public function testWeCanGetErrorMessages()
    {
        $tests = [
            [
                'data' => [
                    'label_exists'      => '',
                    'label_not_exists'  => '',
                    'required_options'  => '',
                    'optional_options'  => 'invalid_option',
                ],
                'expected_result' => [
                    'Label exists is required',
                    'label_not_exists is required',
                    'Required options is required',
                    'Optional options must be one of these options: option1,option2',
                ],
            ],
            [
                'data' => [
                    'label_exists'      => 'Required field',
                    'label_not_exists'  => 'Required field 2',
                    'required_options'  => 'option1',
                    'optional_options'  => 'option1',
                ],
                'expected_result' => [],
            ],
            [
                'data' => [
                    'label_exists'      => 'Required field',
                    'label_not_exists'  => '',
                    'required_options'  => 'invalid_option',
                    'optional_options'  => '',
                ],
                'expected_result' => [
                    'label_not_exists is required',
                    'Required options must be one of these options: option1,option2',
                ],
            ],
        ];

        foreach ($tests as $test) {
            $validator = new \PHPForms\Validator($this->form, $test['data'], $this->validations);
            $validator->isValid();
            $this->assertEquals($test['expected_result'], $validator->getErrors());
        }
    }
}
