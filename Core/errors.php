<?php

/**
 * Error messages for validation rules
 *
 * {field} will be replaced with the field's name
 * {params} will be replaced with any passed arguments (inList[arg1,arg2...])
 */
return [
    'required'    => 'The {field} field is required.',
    'inList'      => 'The {field} field must be one of these options: {params}.',
    'minLength'   => 'The {field} field must be at least {params} characters long.',
    'maxLength'   => 'The {field} field must not be more than {params} characters long.',
    'exactLength' => 'The {field} field must be exactly {params} characters long.',
    'greaterThan' => 'The {field} field must be greater than {params}.',
    'lessThan'    => 'The {field} field must be less than {params}.',
    'validEmail'  => 'The {field} field must be a valid e-mail.',
];
