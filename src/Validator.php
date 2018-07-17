<?php

namespace PHPForms;

class Validator
{
    /**
     * Validations object
     *
     * @var \PHPForms\Validations
     */
    private $validations;

    /**
     * Form object
     *
     * @var \PHPForms\Form
     */
    private $form;

    /**
     * Data to validate
     *
     * @var array
     */
    private $data;

    /**
     * Validated data
     *
     * @var array
     */
    private $valid_data = [];

    /**
     * Validation errors
     *
     * @var array
     */
    private $errors = [];

    /**
     * Create Validatior object
     *
     * @param Form $form  Form object
     * @param array $data Data to validate
     * @param Validations $validations Validation rules
     */
    public function __construct(Form $form, array $data, Validation $validations)
    {
        $this->form = $form;
        $this->data = $data;
        $this->validations = $validations;
    }

    /**
     * Check if the passed data is valid
     *
     * @return boolean TRUE/FALSE if valid/not valid
     */
    public function isValid():bool
    {
        // Validation status
        $status = true;

        // Loop through form fields and validate passed data
        foreach ($this->form->getFields() as $field_name => $options) {
            // Data is always valid if there are no rules
            if (empty($options['rules'])) {
                if (isset($this->data[$field_name])) {
                    $this->valid_data[$field_name] = $this->data[$field_name];
                }
                continue;
            }

            // Get the field's rules and execute them all
            $rules = explode('|', $options['rules']);

            // Should we ignore this field?
            if ($rules[0] === 'ignore') {
                continue;
            }

            // This will change to false as soon as one rule fails
            $rules_passed = true;
            foreach ($rules as $rule) {
                if (!$this->executeRule($rule, $field_name)) {
                    $rules_passed = false;
                    $status = false;
                }
            }

            // If all the rules passed add data to $this->valid_data
            if ($rules_passed) {
                $this->valid_data[$field_name] = $this->data[$field_name];
            }
        }

        return $status;
    }

    /**
     * Execute a rule
     *
     * @param string $rule Rule to execute
     * @param string $field_name Field to validate
     * @throws \PHPForms\RuleNotFound When validation rule doesn't exist
     * @return boolean TRUE/FALSE if the data passed/didn't pass the rule
     */
    private function executeRule(string $rule, string $field_name):bool
    {
        // If the data for this field was not passed set it to an empty string
        $this->data[$field_name] = $this->data[$field_name] ?? '';

        // Extract arguments (the [...] part)
        preg_match('/\[(.+)\]/m', $rule, $matches);

        // If there are arguments seperate the rule from the args
        if (!empty($matches)) {
            $rule = str_replace($matches[0], '', $rule);
        }

        // Get the args if any or set it to null
        $args = $matches[1] ?? null;

        // Check if the validation rule exists
        if (!method_exists($this->validations, $rule)) {
            throw new RuleNotFound("Rule '$rule' does not exist.");
        }

        // Do the validation return true if succeeded
        if (call_user_func_array([$this->validations, $rule], [$this->data[$field_name], $args])) {
            return true;
        }

        // Validation failed get the error message

        $field = $this->form->getField($field_name);
        // If no label was passed use the name
        $name = (empty($field['label'])) ? $field_name : $field['label'];

        $this->errors[] = $this->validations->getError($rule, $name, $args);

        return false;
    }

    /**
     * Get validated data
     *
     * This will only return the data listed in the form's fields
     * And that passed the rules.
     * Must be called after isValid()
     *
     * @return array
     */
    public function getValidData():array
    {
        return $this->valid_data;
    }

    /**
     * Get validation errors
     *
     * Must be called after isValid()
     *
     * @return array If no errors were found it will return an empty array
     */
    public function getErrors():array
    {
        return $this->errors;
    }
}
