<?php

namespace PHPForms;

class ExportCSV extends Exporter
{
    /**
     * CSV file path
     *
     * @var string
     */
    private $file_path;

    /**
     *
     * @param string $file_path CSV File path
     * @param array $data Data to write
     * @param array $map Fields map, an array of 'field_name' => 'column name'
     */
    public function __construct(string $file_path, array $data = [], array $map = [])
    {
        $this->file_path = $file_path;
        $this->data = $data;
        $this->map = $map;
    }

    /**
     * Set file path
     *
     * @param string $file_path File path
     * @return void
     */
    public function setFilePath(string $file_path):void
    {
        $this->file_path = $file_path;
    }

    /**
     * Export data
     *
     * @return boolean TRUE if exported successfully or FALSE otherwise
     */
    public function export():bool
    {
        // If the file doesn't exist create a new file
        if (!file_exists($this->file_path)) {
            return $this->newFile();
        }

        /**
         * Append the data to the file
         * We need to get the columns order from the file in case the data passed
         * Is not in the same order as the file
         */
        $file = fopen($this->file_path, 'a+');

        if (!$file) {
            return false;
        }

        $line = trim(fgets($file));
        $columns = str_getcsv($line);

        // Get the original field name if any
        if (!empty($this->map)) {
            $columns = array_map(function ($column) {
                $key = array_search($column, $this->map);
                return ($key === false) ? $column : $key;
            }, $columns);
        }

        // Loop through the columns
        $data = [];
        foreach ($columns as $column) {
            $data[] = (empty($this->data[$column])) ? '' : $this->data[$column];
        }

        return fputcsv($file, $data);
    }

    /**
     * Create a new CSV file and put data into it
     *
     * @return boolean TRUE if created successfully FALSE otherwise
     */
    private function newFile():bool
    {
        $file = fopen($this->file_path, 'w');

        if ($file === false) {
            return false;
        }

        // Get the fields and data
        $fields = [];
        $data = [];
        foreach ($this->data as $field => $value) {
            $fields[] = (empty($this->map[$field])) ? $field : $this->map[$field];
            $data[] = $value;
        }

        // Put them into the file
        return fputcsv($file, $fields) && fputcsv($file, $data);
    }
}
