<?php

namespace Core\Database;


use Core\Application;

abstract class Migration
{

    protected Database $connection;
    public function __construct()
    {
        $this->connection = Application::$app->db;
    }

    abstract public function up(): void;

    abstract public function down(): void;

}