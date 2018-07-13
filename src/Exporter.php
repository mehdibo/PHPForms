<?php

namespace PHPForms;

abstract class Exporter
{
    /**
     * Data to export
     *
     * @var array
     */
    protected $data;

    /**
     * Fields map
     *
     * @var array
     */
    protected $map;

    /**
     * Add data to export
     *
     * @param string $name  Column/Field name
     * @param string $value Value
     * @return void
     */
    public function addData(string $name, string $value):void
    {
        $this->data[$name] = $value;
    }

    /**
     * Set map for fields
     *
     * Map fields to different columns
     *
     * @param array $map An array of 'field_name' => 'column_name'
     * @return void
     */
    public function setMap(array $map):void
    {
        $this->map = $map;
    }


    /**
     * Export/Save the data
     *
     * @return boolean TRUE if exported successfully or FALSE otherwise
     */
    abstract public function export():bool;
}
