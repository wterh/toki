<?php

namespace app\models;

use app\core\Model;

/**
 * Class Table
 * @package app\models
 */
abstract class Table extends Model
{
    protected $table;
    var $show_errors = true;

    /**
     * Table constructor.
     * @param $table
     */
    public function __construct($table)
    {
        parent::__construct();
        $this->table = $table;
    }

    public function add(array $data)
    {
        $params = [];
        foreach ($data as $key => $value) {
            //$params[$key] = trim(htmlspecialchars($value));
            $params[$key] = $value;
        }

        $keys = implode(', ', array_keys($params));
        $values = ':' . implode(', :', array_keys($params));

        $this->db->query("INSERT INTO {$this->table} ({$keys}) VALUES ({$values})", $params);

        return $this->db->lastInsertId();
    }

    public function edit(array $data, $id)
    {
        $params = [];
        foreach ($data as $key => $value) {
            // $params[$key] = trim(htmlspecialchars($value));
            $params[$key] = $value;
        }

        $values = '';
        foreach ($params as $key => $value) {
            $values .= "{$key} = :{$key},";
        }
        $values = trim($values, ',');

        $params['id'] = $id;

        $this->db->query("UPDATE {$this->table} SET {$values} WHERE id = :id", $params);

        return true;
    }

    public function getById($id)
    {
        $params = ['id' => $id];

        return $this->db->row("SELECT * FROM {$this->table} WHERE id = :id", $params);
    }
    
    public function getCountAll()
    {
        return $this->db->row("SELECT COUNT(id) FROM {$this->table}");
    }

    public function getAll($asc = false)
    {
        if ($asc) {
            return $this->db->rows("SELECT * FROM {$this->table} ORDER BY id ASC ");
        }

        return $this->db->rows("SELECT * FROM {$this->table} ORDER BY id DESC");
    }

    public function delete($id)
    {
        $params = ['id' => $id];

        $this->db->query("DELETE FROM {$this->table} WHERE id = :id", $params);

        return true;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function search(array $data)
    {
        $whereString = '';
        $item = 0;

        foreach ($data as $key => $value) {
            if ($item == 0) {
                $whereString .= "{$key} = '{$value}'";
            } else {
                $whereString .= " AND {$key} = '{$value}'";
            }

            $item++;
        }

        $sql = "SELECT * FROM {$this->table} WHERE {$whereString}";
// print_r($sql);
        return $this->db->rows($sql);
    }

    /**
     * @return bool|\PDOStatement
     */
    public function truncate()
    {
        return $this->db->query("TRUNCATE {$this->table}");
    }
    
    /**
     * @return bool|\PDOStatement
     */
    public function reset()
    {
        return $this->db->query("ALTER TABLE {$this->table} AUTO_INCREMENT=0");
    }
    
    public function show_errors( $show = false ) {
        $errors            = $this->show_errors;
        $this->show_errors = $show;
        return $errors;
    }
    
    public function seeQuery($params = null)
    {
        if(is_array($params)) {
            $keys = '';
            $values = '';
            foreach ($params as $key => $item) {
                $keys .= "`{$key}`, ";
                $values .= "'{$item}', ";
            }
            $values = trim($values, ', ');
            return "INSERT INTO {$this->table} ({$keys}) VALUES ({$values})";
        }
    }
}