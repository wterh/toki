<?php

namespace app\core;

use app\libs\Database;

/**
 * Class Model
 * @package app\core
 */
abstract class Model
{
    protected $db;

    public function __construct()
    {
        $this->db = new Database();
    }
}