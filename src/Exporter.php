<?php

namespace PHPForms;

interface Exporter
{
    /**
     * Add data to export
     *
     * @param string $name  Column/Field name
     * @param string $value Value
     * @return void
     */
    public function addData(string $name, string $value):void;

    /**
     * Set map for fields
     *
     * Map fields to different columns
     *
     * @param array $map An array of 'field_name' => 'column_name'
     * @return void
     */
    public function setMap(array $map):void;


    /**
     * Export/Save the data
     *
     * @return boolean TRUE if exported successfully or FALSE otherwise
     */
    public function export():bool;
}
