<?php

namespace Core\Database;


abstract class Migration
{
    protected \PDO $connection;


    abstract public function up(): void;

    abstract public function down(): void;

}