<?php

namespace PHPForms\Tests;

class ValidationsTest extends \PHPUnit\Framework\TestCase
{
    public function testRequiredRuleWorks()
    {
        $validations = new \PHPForms\Validations([]);

        $this->assertEquals(true, $validations->required('Valid'));

        $this->assertEquals(false, $validations->required(''));
        $this->assertEquals(false, $validations->required('   '));
    }

    public function testInListRuleWorks()
    {
        $validations = new \PHPForms\Validations([]);

        $options = 'valid1,v@lid2,valid-3,valid_4,và°lid5';

        $this->assertEquals(true, $validations->inList('valid1', $options));
        $this->assertEquals(true, $validations->inList('v@lid2', $options));
        $this->assertEquals(true, $validations->inList('valid-3', $options));
        $this->assertEquals(true, $validations->inList('valid_4', $options));
        $this->assertEquals(true, $validations->inList('và°lid5', $options));
        $this->assertEquals(false, $validations->inList('Invalid', $options));
    }

    public function testMinLengthRuleWorks()
    {
        $validations = new \PHPForms\Validations([]);

        $this->assertEquals(true, $validations->minLength('За работой', '10'));
        $this->assertEquals(true, $validations->minLength('يعمل', '4'));
        $this->assertEquals(true, $validations->minLength('Wor king', '8'));


        $this->assertEquals(false, $validations->minLength('За работой', '12'));
        $this->assertEquals(false, $validations->minLength('يعمل', '5'));
        $this->assertEquals(false, $validations->minLength('Wor king', '10'));
        $this->assertEquals(false, $validations->minLength('', '1'));
    }

    public function testMaxLengthRuleWorks()
    {
        $validations = new \PHPForms\Validations([]);

        $this->assertEquals(true, $validations->maxLength('За работой', '10'));
        $this->assertEquals(true, $validations->maxLength('يعمل', '4'));
        $this->assertEquals(true, $validations->maxLength('Wor king', '9'));


        $this->assertEquals(false, $validations->maxLength('За работой', '9'));
        $this->assertEquals(false, $validations->maxLength('يعمل', '3'));
        $this->assertEquals(false, $validations->maxLength('Wor king', '7'));
    }

    public function testExactLengthRuleWorks()
    {
        $validations = new \PHPForms\Validations([]);

        $this->assertEquals(true, $validations->exactLength('За работой', '10'));
        $this->assertEquals(true, $validations->exactLength('يعمل', '4'));
        $this->assertEquals(true, $validations->exactLength('Wor king', '8'));


        $this->assertEquals(false, $validations->exactLength('За работой', '12'));
        $this->assertEquals(false, $validations->exactLength('يعمل', '5'));
        $this->assertEquals(false, $validations->exactLength('Wor king', '10'));
        $this->assertEquals(false, $validations->exactLength('', '1'));
    }

    public function testGreaterThanRuleWorks()
    {
        $validations = new \PHPForms\Validations([]);

        $this->assertEquals(true, $validations->greaterThan('5', '1'), true);
        $this->assertEquals(true, $validations->greaterThan('12.5', '12'), true);
        $this->assertEquals(true, $validations->greaterThan('100.02', '100'), true);


        $this->assertEquals(false, $validations->greaterThan('1', '10'));
        $this->assertEquals(false, $validations->greaterThan('63.9', '64'));
        $this->assertEquals(false, $validations->greaterThan('30.05', '30.1'));
    }


    public function testLessThanRuleWorks()
    {
        $validations = new \PHPForms\Validations([]);

        $this->assertEquals(true, $validations->lessThan('1', '10'));
        $this->assertEquals(true, $validations->lessThan('63.9', '64'));
        $this->assertEquals(true, $validations->lessThan('30.05', '30.1'));


        $this->assertEquals(false, $validations->lessThan('5', '1'));
        $this->assertEquals(false, $validations->lessThan('12.5', '12'));
        $this->assertEquals(false, $validations->lessThan('100.02', '100'));
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

        $this->assertEquals(false, $validations->validEmail(''));
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
