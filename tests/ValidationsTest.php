<?php

namespace PHPForms\Tests;

class ValidationsTest extends \PHPUnit\Framework\TestCase
{
    public function testRequiredRuleWorks()
    {
        $tests = [
            [
                'data' => 'Hello!',
                'expected_result' => true,
            ],
            [
                'data' => '',
                'expected_result' => false,
            ],
            [
                'data' => '    ',
                'expected_result' => false,
            ],
            [
                'data' => "\t\n",
                'expected_result' => false,
            ],
        ];

        $validations = new \PHPForms\Validations([]);
        foreach ($tests as $test) {
            $this->assertEquals($test['expected_result'], $validations->required($test['data']));
        }
    }

    public function testInListRuleWorks()
    {
        $tests = [
            [
                'data' => 'valid1',
                'expected_result' => true,
            ],
            [
                'data' => 'v@lid2',
                'expected_result' => true,
            ],
            [
                'data' => 'valid-3',
                'expected_result' => true,
            ],
            [
                'data' => 'valid_4',
                'expected_result' => true,
            ],
            [
                'data' => 'và°lid5',
                'expected_result' => true,
            ],
            [
                'data' => '    ',
                'expected_result' => true,
            ],
            [
                'data' => "\t\n",
                'expected_result' => true,
            ],
            [
                'data' => "valid 1",
                'expected_result' => false,
            ],
            [
                'data' => "INVALID OPTION",
                'expected_result' => false,
            ],
        ];
        $options = 'valid1,v@lid2,valid-3,valid_4,và°lid5';

        $validations = new \PHPForms\Validations([]);
        foreach ($tests as $test) {
            $this->assertEquals($test['expected_result'], $validations->inList($test['data'], $options));
        }
    }

    public function testMinLengthRuleWorks()
    {
        $tests = [
            [
                'data' => 'Mehdi',
                'len'  => '2',
                'expected_result' => true,
            ],
            [
                'data' => 'Mehdi',
                'len'  => '5',
                'expected_result' => true,
            ],
            [
                'data' => 'Mehdi',
                'len'  => '10',
                'expected_result' => false,
            ],

            [
                'data' => 'За работой',
                'len'  => '2',
                'expected_result' => true,
            ],
            [
                'data' => 'За работой',
                'len'  => '10',
                'expected_result' => true,
            ],
            [
                'data' => 'За работой',
                'len'  => '11',
                'expected_result' => false,
            ],

            [
                'data' => '加工 加工',
                'len'  => '2',
                'expected_result' => true,
            ],
            [
                'data' => '加工 加工',
                'len'  => '5',
                'expected_result' => true,
            ],
            [
                'data' => '加工 加工',
                'len'  => '10',
                'expected_result' => false,
            ],

            [
                'data' => '     ',
                'len'  => '1000',
                'expected_result' => true,
            ],
            [
                'data' => "\t\n",
                'len'  => '1000',
                'expected_result' => true,
            ],
            [
                'data' => ' ',
                'len'  => '1000',
                'expected_result' => true,
            ],
        ];

        $validations = new \PHPForms\Validations([]);
        foreach ($tests as $test) {
            $this->assertEquals($test['expected_result'], $validations->minLength($test['data'], $test['len']));
        }
    }

    public function testMaxLengthRuleWorks()
    {
        $tests = [
            [
                'data' => 'Mehdi',
                'len'  => '6',
                'expected_result' => true,
            ],
            [
                'data' => 'Mehdi',
                'len'  => '5',
                'expected_result' => true,
            ],
            [
                'data' => 'Mehdi',
                'len'  => '2',
                'expected_result' => false,
            ],

            [
                'data' => 'За работой',
                'len'  => '11',
                'expected_result' => true,
            ],
            [
                'data' => 'За работой',
                'len'  => '10',
                'expected_result' => true,
            ],
            [
                'data' => 'За работой',
                'len'  => '2',
                'expected_result' => false,
            ],

            [
                'data' => '加工 加工',
                'len'  => '6',
                'expected_result' => true,
            ],
            [
                'data' => '加工 加工',
                'len'  => '5',
                'expected_result' => true,
            ],
            [
                'data' => '加工 加工',
                'len'  => '2',
                'expected_result' => false,
            ],

            [
                'data' => '     ',
                'len'  => '1',
                'expected_result' => true,
            ],
            [
                'data' => "\t\n",
                'len'  => '1',
                'expected_result' => true,
            ],
            [
                'data' => ' ',
                'len'  => '0',
                'expected_result' => true,
            ],
        ];

        $validations = new \PHPForms\Validations([]);
        foreach ($tests as $test) {
            $this->assertEquals($test['expected_result'], $validations->maxLength($test['data'], $test['len']));
        }
    }

    public function testExactLengthRuleWorks()
    {
        $tests = [
            [
                'data' => 'Mehdi',
                'len'  => '5',
                'expected_result' => true,
            ],
            [
                'data' => 'Mehdi',
                'len'  => '6',
                'expected_result' => false,
            ],
            [
                'data' => 'Mehdi',
                'len'  => '4',
                'expected_result' => false,
            ],

            [
                'data' => 'За работой',
                'len'  => '10',
                'expected_result' => true,
            ],
            [
                'data' => 'За работой',
                'len'  => '11',
                'expected_result' => false,
            ],
            [
                'data' => 'За работой',
                'len'  => '9',
                'expected_result' => false,
            ],

            [
                'data' => '加工 加工',
                'len'  => '5',
                'expected_result' => true,
            ],
            [
                'data' => '加工 加工',
                'len'  => '6',
                'expected_result' => false,
            ],
            [
                'data' => '加工 加工',
                'len'  => '4',
                'expected_result' => false,
            ],

            [
                'data' => '     ',
                'len'  => '1',
                'expected_result' => true,
            ],
            [
                'data' => "\t\n",
                'len'  => '1',
                'expected_result' => true,
            ],
            [
                'data' => ' ',
                'len'  => '8',
                'expected_result' => true,
            ],
        ];

        $validations = new \PHPForms\Validations([]);
        foreach ($tests as $test) {
            $this->assertEquals($test['expected_result'], $validations->exactLength($test['data'], $test['len']));
        }
    }

    public function testGreaterThanRuleWorks()
    {
        $tests = [
            [
                'data' => '5',
                'len'  => '1',
                'expected_result' => true,
            ],
            [
                'data' => '12.5',
                'len'  => '12',
                'expected_result' => true,
            ],
            [
                'data' => '100.02',
                'len'  => '100',
                'expected_result' => true,
            ],
            [
                'data' => '100.09',
                'len'  => '100.08',
                'expected_result' => true,
            ],

            [
                'data' => '1',
                'len'  => '5',
                'expected_result' => false,
            ],
            [
                'data' => '12',
                'len'  => '12.5',
                'expected_result' => false,
            ],
            [
                'data' => '100',
                'len'  => '100.02',
                'expected_result' => false,
            ],
            [
                'data' => '100.08',
                'len'  => '100.09',
                'expected_result' => false,
            ],

            [
                'data' => '     ',
                'len'  => '1',
                'expected_result' => true,
            ],
            [
                'data' => "\t\n",
                'len'  => '1',
                'expected_result' => true,
            ],
            [
                'data' => ' ',
                'len'  => '0',
                'expected_result' => true,
            ],
        ];

        $validations = new \PHPForms\Validations([]);
        foreach ($tests as $test) {
            $this->assertEquals($test['expected_result'], $validations->greaterThan($test['data'], $test['len']));
        }
    }

    public function testGreaterThanOrEqualRuleWorks()
    {
        $tests = [
            [
                'data' => '5',
                'len'  => '1',
                'expected_result' => true,
            ],
            [
                'data' => '12.5',
                'len'  => '12',
                'expected_result' => true,
            ],
            [
                'data' => '100',
                'len'  => '100',
                'expected_result' => true,
            ],
            [
                'data' => '100.09',
                'len'  => '100.09',
                'expected_result' => true,
            ],

            [
                'data' => '1',
                'len'  => '5',
                'expected_result' => false,
            ],
            [
                'data' => '12',
                'len'  => '12.5',
                'expected_result' => false,
            ],
            [
                'data' => '100',
                'len'  => '100.02',
                'expected_result' => false,
            ],
            [
                'data' => '100.08',
                'len'  => '100.09',
                'expected_result' => false,
            ],

            [
                'data' => '     ',
                'len'  => '1',
                'expected_result' => true,
            ],
            [
                'data' => "\t\n",
                'len'  => '1',
                'expected_result' => true,
            ],
            [
                'data' => ' ',
                'len'  => '0',
                'expected_result' => true,
            ],
        ];

        $validations = new \PHPForms\Validations([]);
        foreach ($tests as $test) {
            $this->assertEquals($test['expected_result'], $validations->greaterThanEq($test['data'], $test['len']));
        }
    }


    public function testLessThanRuleWorks()
    {
        $tests = [
            [
                'data' => '5',
                'len'  => '1',
                'expected_result' => false,
            ],
            [
                'data' => '12.5',
                'len'  => '12',
                'expected_result' => false,
            ],
            [
                'data' => '100.02',
                'len'  => '100',
                'expected_result' => false,
            ],
            [
                'data' => '100.09',
                'len'  => '100.08',
                'expected_result' => false,
            ],

            [
                'data' => '1',
                'len'  => '5',
                'expected_result' => true,
            ],
            [
                'data' => '12',
                'len'  => '12.5',
                'expected_result' => true,
            ],
            [
                'data' => '100',
                'len'  => '100.02',
                'expected_result' => true,
            ],
            [
                'data' => '100.08',
                'len'  => '100.09',
                'expected_result' => true,
            ],

            [
                'data' => '     ',
                'len'  => '1',
                'expected_result' => true,
            ],
            [
                'data' => "\t\n",
                'len'  => '1',
                'expected_result' => true,
            ],
            [
                'data' => ' ',
                'len'  => '0',
                'expected_result' => true,
            ],
        ];

        $validations = new \PHPForms\Validations([]);
        foreach ($tests as $test) {
            $this->assertEquals($test['expected_result'], $validations->lessThan($test['data'], $test['len']));
        }
    }

    public function testLessThanOrEqualRuleWorks()
    {
        $tests = [
            [
                'data' => '5',
                'len'  => '1',
                'expected_result' => false,
            ],
            [
                'data' => '12.5',
                'len'  => '12',
                'expected_result' => false,
            ],
            [
                'data' => '100.02',
                'len'  => '100',
                'expected_result' => false,
            ],
            [
                'data' => '100.09',
                'len'  => '100.08',
                'expected_result' => false,
            ],

            [
                'data' => '1',
                'len'  => '5',
                'expected_result' => true,
            ],
            [
                'data' => '1',
                'len'  => '1',
                'expected_result' => true,
            ],
            [
                'data' => '12',
                'len'  => '12.5',
                'expected_result' => true,
            ],
            [
                'data' => '100',
                'len'  => '100.02',
                'expected_result' => true,
            ],
            [
                'data' => '100.08',
                'len'  => '100.09',
                'expected_result' => true,
            ],
            [
                'data' => '100.09',
                'len'  => '100.09',
                'expected_result' => true,
            ],

            [
                'data' => '     ',
                'len'  => '1',
                'expected_result' => true,
            ],
            [
                'data' => "\t\n",
                'len'  => '1',
                'expected_result' => true,
            ],
            [
                'data' => ' ',
                'len'  => '0',
                'expected_result' => true,
            ],
        ];

        $validations = new \PHPForms\Validations([]);
        foreach ($tests as $test) {
            $this->assertEquals($test['expected_result'], $validations->lessThanEq($test['data'], $test['len']));
        }
    }

    public function testValidEmailRuleWorks()
    {
        $validations = new \PHPForms\Validations([]);

        $emails = [
            'contact.mehdi@pm.me'     => true,
            'someone+alias@gmail.com' => true,
            'someone.somewhere.at.550.x@example-test.com' => true,
            'invalid' => false,
        ];

        foreach ($emails as $email => $expected_result) {
            $this->assertEquals($expected_result, $validations->validEmail($email));
        }

        $this->assertEquals(true, $validations->validEmail(''));
    }

    public function testWeCanGetErrorMessages()
    {
        $errors = [
            'inList' => 'The {field} must have these options: {params}',
            'required' => 'The {field} is required.',
        ];

        $validations = new \PHPForms\Validations($errors);

        $this->assertEquals(
            'The field_name must have these options: option1,option2',
            $validations->getError('inList', 'field_name', 'option1,option2')
        );

        $this->assertEquals('The field_name is required.', $validations->getError('required', 'field_name'));
    }
}
