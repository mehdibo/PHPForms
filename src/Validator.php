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
                // If data submitted add it as valid
                if (isset($this->data[$field_name])) {
                    $this->valid_data[$field_name] = $this->data[$field_name];
                }
                continue;
            }

            $data = $this->data[$field_name] ?? '';

            // Get the field's rules and execute them all
            $rules = $this->parseRules($options['rules']);

            // Should we ignore this field?
            if (array_key_exists('ignore', $rules)) {
                continue;
            }

            // Execute all rules
            $valid = true;
            foreach ($rules as $rule => $args) {
                if (!$this->executeRule($rule, $args, $data)) {
                    // Get validation error
                    $name = (empty($options['label'])) ? $field_name : $options['label'];
                    $this->errors[] = $this->validations->getError($rule, $name, $args);

                    $valid = false;
                }
            }

            // If all the rules passed add data to $this->valid_data
            if ($valid) {
                $this->valid_data[$field_name] = $data;
            } else {
                $status = false;
            }
        }

        return $status;
    }

    /**
     * Parse rules seperated by a pipe |
     *
     * @param string $rules Rules to parse seperated by a pipe |
     * @return array Array of rules 'rule'=>'args'
     */
    private function parseRules(string $rules):array
    {
        // Parse rule and get args if any
        foreach (explode('|', $rules) as $rule) {
            // Extract arguments (the [...] part)
            preg_match('/\[(.+)\]/m', $rule, $matches);

            // If there are arguments seperate the rule from the args
            if (!empty($matches)) {
                $rule = str_replace($matches[0], '', $rule);
            }

            $parsed[$rule] = $matches[1] ?? null;
        }

        return $parsed;
    }

    /**
     * Execute a rule
     *
     * @param string $rule Rule to execute
     * @param string $field_name Field to validate
     * @throws \PHPForms\RuleNotFound When validation rule doesn't exist
     * @return boolean TRUE/FALSE if the data passed/didn't pass the rule
     */
    private function executeRule(string $rule, ?string $args, string $data):bool
    {
        // Check if the validation rule exists
        if (!method_exists($this->validations, $rule)) {
            throw new RuleNotFound("Rule '$rule' does not exist.");
        }

        return call_user_func_array([$this->validations, $rule], [$data, $args]);
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
