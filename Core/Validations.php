<?php

namespace PHPForms;

class Validations extends Validation
{
    /**
     * Make sure the value is not empty
     *
     * The passed value will be trimmed
     *
     * @param string $value
     * @return boolean TRUE/FALSE if the value is Not empty/Empty
     */
    public function required(string $value):bool
    {
        return !empty(trim($value));
    }

    /**
     * Check if the value is in the list
     *
     * @param string $value
     * @param string $list  Comma seperated options
     * @return boolean TRUE if value is in list
     */
    public function inList(string $value, string $list):bool
    {
        $list = explode(',', $list);

        return in_array($value, $list);
    }

    /**
     * Value must be at least the passed length
     *
     * Check if the value's length is at least the passed $min
     *
     * @param string $value
     * @param string $min   The minimum length
     * @return boolean
     */
    public function minLength(string $value, string $min):bool
    {
        // Make sure $min is a number
        if (!is_numeric($min)) {
            return false;
        }

        return mb_strlen($value) >= (int) $min;
    }

    /**
     * Value must not be more than the passed length
     *
     * Check if the value's length is not bigger than $max
     *
     * @param string $value
     * @param string $max   The maximum length
     * @return boolean
     */
    public function maxLength(string $value, string $max):bool
    {
        // Make sure $max is a number
        if (!is_numeric($max)) {
            return false;
        }

        return mb_strlen($value) <= (int) $max;
    }

    /**
     * Value's length must equal $len
     *
     * @param string $value
     * @param string $len   The required length
     * @return boolean
     */
    public function exactLength(string $value, string $len):bool
    {
        // Make sure $len is a number
        if (!is_numeric($max)) {
            return false;
        }

        return mb_strlen($value) === (int) $max;
    }

    /**
     * Value must be greater than num
     *
     * @param string $value
     * @param string $num
     * @return boolean
     */
    public function greaterThan(string $value, string $num):bool
    {
        // Make sure $value and $num are numbers
        if (!is_numeric($max) || !is_numberic($num)) {
            return false;
        }

        return $value > $num;
    }

    /**
     * Value must be less than num
     *
     * @param string $value
     * @param string $num
     * @return boolean
     */
    public function lessThan(string $value, string $num):bool
    {
        // Make sure $value and $num are numbers
        if (!is_numeric($max) || !is_numberic($num)) {
            return false;
        }

        return $value < $num;
    }

    /**
     * Value must be a valid e-mail
     *
     * @param string $value
     * @return boolean
     */
    public function validEmail(string $value):bool
    {
        list($user, $domain) = explode('@', $value);

        // Convert domain name to an IDNA ASCII-compatible format
        $domain = idn_to_ascii($domain, 0, INTL_IDNA_VARIANT_UTS46);

        $value = $user . '@' . $domain;

        return (bool) filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}
