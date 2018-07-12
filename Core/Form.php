<?php

namespace PHPForms;

class Form
{
    /**
     * Fields
     *
     * Array of inputs and their options
     *
     * @var array
     */
    private $fields;

    /**
     * Attributes
     *
     * Attributes to add to the form tag when creating HTML
     *
     * @var array
     */
    private $attribs;

    /**
     * Create form object
     *
     * @param array $fields  Array of fields and its options
     * @param array $attribs Array of form attributes
     */
    public function __construct(array $fields = [], array $attribs = [])
    {
        $this->fields = $fields;
        $this->attribs = $attribs;

        // Make it a POST form by default
        $this->attribs['method'] = $this->attribs['method'] ?? 'POST';
    }

    /**
     * Add field
     *
     * If there is a field with the same name the options will be overwritten
     *
     * @param string $name    Field's name
     * @param array  $options Options
     * @return void
     */
    public function addField(string $name, array $options):void
    {
        $this->fields[$name] = $options;
    }

    /**
     * Get fields
     *
     * @return array|null Array of fields or NULL if no fields are found
     */
    public function getFields():?array
    {
        return (empty($this->fields)) ? null : $this->fields;
    }

    /**
     * Get HTML code for form
     *
     * @param bool $beautify TRUE/FALSE to return Formatted/Minified HTML
     * @return string HTML code for the form
     */
    public function getHTML(bool $beautify = true):string
    {
        $html = new \DOMDocument();
        $html->formatOutput = $beautify;
        // Add a Form element to the Document
        $form = $html->appendChild($html->createElement('form'));

        // Add attributes
        foreach ($this->attribs as $attrib => $value) {
            $form->setAttribute($attrib, $value);
        }

        // Create inputs
        foreach ($this->fields as $name => $options) {
            // Create Input element
            $input = $html->createElement(($options['type'] === 'textarea') ? 'textarea' : 'input');
            $input->setAttribute('name', $name);
            // If no type was set use 'text'
            $input->setAttribute('type', $options['type'] ?? 'text');
            // Add Label to placeholder if available
            if (!empty($options['label'])) {
                $input->setAttribute('placeholder', $options['label']);
            }

            // Add attributes if any
            if (!empty($options['attribs'])) {
                foreach ($options['attribs'] as $attrib => $value) {
                    $input->setAttribute($attrib, $value);
                }
            }

            // Append input to form
            $form->appendChild($input);
        }

        return $html->saveHTML();
    }
}
