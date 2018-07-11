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
     * Cache the validation result
     *
     * @var boolean
     */
    private $valid;

    /**
     * Create Validatior object
     *
     * @param Form $form  Form object
     * @param array $data Data to validate
     * @throws \PHPForms\RuleNotFound When validation rule doesn't exist
     * @param Validations $validations Validation rules
     */
    public function __construct(Form $form, array $data, Validation $validations)
    {
        $this->form = $form;
        $this->data = $data;
        $this->validations = $validations;
    }

    public function validate():bool
    {
        // Check for cached validation result
        if ($this->valid !== null) {
            return $this->valid;
        }

        $form_fields = $this->form->getFields();

        // Loop through form fields and validate passed data
        foreach ($form_fields as $field_name => $options) {
            // If there are no rules skip
            if (empty($options['rules'])) {
                continue;
            }

            $rules = explode('|', $options['rules']);
            // This will change to false as soon as one rule fails
            $rules_passed = true;
            // Loop through rules and execute them
            foreach ($rules as $rule) {
                // Extract arguments (the [...] part)
                preg_match('/\[(.+)\]/m', $rule, $matches);

                $args = null;
                // If there are arguments
                if (!empty($matches)) {
                    // Get rule name (remove the arguments part)
                    $rule = str_replace($matches[0], '', $rule);
                    $args = $matches[1];
                }

                // Check if the validation rule exists
                if (!method_exists($this->validations, $rule)) {
                    throw new RuleNotFound("Rule '$rule' does not exist.");
                }

                // Do the validation and get error if failed
                $check = call_user_func_array([$this->validations, $rule], [$this->data[$field_name], $args]);
                
                if ($check === false) {
                    $rules_passed = false;
                    $this->valid = false;
                    // If no label was set use the field's name
                    $name = $options['label'] ?? $field_name;
                    $this->errors[] = $this->validations->getError($rule, $name, $args);
                }
            }

            // If all the rules passed add data to $this->valid_data
            if ($rules_passed) {
                $this->valid_data[$field_name] = $this->data[$field_name];
            }
        }

        // If it's null then the validation rules passed
        $this->valid = $this->valid ?? true;

        return $this->valid;
    }

    /**
     * Get validated data
     *
     * This will only return the data listed in the form's fields
     * And that passed the rules.
     * Must be called after validate()
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
     * Must be called after validate()
     *
     * @return array If no errors were found it will return an empty array
     */
    public function getErrors():array
    {
        return $this->errors;
    }
}
