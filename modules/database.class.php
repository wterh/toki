<?php

namespace app\libs;

use PDO;

/**
 * Class Database
 * @package app\libs
 */
class Database
{
    protected $db;
    protected static $instance;

    public function __construct()
    {
        $config = require BASE_DIR . '/modules/env.php';

        if (empty(self::$instance)) {
            $this->db = new PDO("mysql:host={$config['host']};dbname={$config['name']};", $config['user'], $config['password']);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            $this->db->exec('SET NAMES utf8mb4');
            self::$instance = $this->db;
        } else {
            $this->db = self::$instance;
        }
    }

    /**
     * @param $sql
     * @param array $params
     * @return bool|\PDOStatement
     */
    public function query($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);

        if (!empty($params)) {
            foreach ($params as $key => $value) {
                if (is_int($value)) {
                    $type = \PDO::PARAM_INT;
                } else {
                    $type = \PDO::PARAM_STR;
                }

                $stmt->bindValue(":$key", $value ,$type);
            }
        }
        $stmt->execute();

        return $stmt;
    }

    /**
     * @param $sql
     * @param array $params
     * @return mixed
     */
    public function row($sql, array $params = [])
    {
        $result = $this->query($sql, $params);
        return $result->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @param $sql
     * @param array $params
     * @return array
     */
    public function rows($sql, array $params = [])
    {
        $result = $this->query($sql, $params);
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param $sql
     * @param array $params
     * @return mixed
     */
    public function column($sql, array $params = [])
    {
        $result = $this->query($sql, $params);
        return $result->fetchColumn();
    }

    /**
     * @return string
     */
    public function lastInsertId()
    {
        return $this->db->lastInsertId();
    }

    /**
     * @param $str
     * @return string
     */
    public function quote($str)
    {
        return $this->db->quote($str);
    }

    /**
     * @param $sql
     * @return int
     */
    public function exec($sql)
    {
        return $this->db->exec($sql);
    }
}