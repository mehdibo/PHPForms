<?php

namespace PHPForms;

abstract class Validation
{

    /**
     * Error messages
     *
     * @var array
     */
    private $errors;

    /**
     * Create object
     *
     * @param array $errors Array of errors for validation rules. 'rule' => 'error'
     */
    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }

    /**
     * Get a rule's HTML validation
     *
     * For example, maxLength[5] can return an array of ['maxlength' => '5']
     *
     * @param string $rule  Rule's name
     * @param string $value Value passed to parameter if any.
     * @return array|null An array of 'attribute_name' => 'optional value' or NULL if no HTML validation is available
     */
    abstract public function getHTMLValidation(string $rule, string $value = null):?array;

    /**
     * Get error for a rule
     *
     * @param string $rule       Rule name
     * @param string $field_name Field name
     * @param string $params     Parameters passed to the rule if any
     * @return string Error message or NULL if none found
     */
    public function getError(string $rule, string $field_name, string $params = null):?string
    {
        // If no error message is defined return NULL
        if (empty($this->errors[$rule])) {
            return null;
        }

        return str_replace(['{field}', '{params}'], [$field_name, $params], $this->errors[$rule]);
    }
}
