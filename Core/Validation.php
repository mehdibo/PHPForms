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
