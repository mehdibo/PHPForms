<?php

namespace PHPForms;

class ExportDB extends Exporter
{
    /**
     * PDO object
     *
     * @var \PDO
     */
    private $pdo;

    /**
     * Table where to save data
     *
     * @var string
     */
    private $table;

    /**
     * Last insert ID
     *
     * @var int
     */
    private $insert_id;

    public function __construct(\PDO $pdo, string $table = null, array $data = [], array $map = [])
    {
        $this->pdo = $pdo;
        $this->data = $data;
        $this->map = $map;
        $this->table = $table;
    }

    /**
     * Set table where to save data
     *
     * @param string $table Table name
     * @return void
     */
    public function setTable(string $table):void
    {
        $this->table = $table;
    }

    /**
     * Save data to database
     *
     * @param string $table Table name
     * @return boolean TRUE if saved or FALSE if failed
     */
    public function export():bool
    {
        // Loop through the fields to map them to columns and generate placeholders
        $columns = $values =[];
        foreach ($this->data as $field => $value) {
            $column = (empty($this->map[$field])) ? $field : $this->map[$field];
            $columns[] = "`".str_replace("`", "``", $column)."`";
            $values[] = ':'.$field;
        }

        // Prepare data to be concatenated to the query
        $table = '`'.str_replace("\`", "``", $this->table).'`';
        $columns = implode(', ', $columns);
        $values = implode(', ', $values);

        $query = 'INSERT INTO ' . $table . ' (' . $columns .') VALUE (' . $values . ')';

        // We prepare and execute the query
        return $this->pdo->prepare($query)->execute($this->data);
    }
}
