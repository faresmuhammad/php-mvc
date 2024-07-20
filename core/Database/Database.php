<?php

namespace Core\Database;

use Core\Application;

class Database
{

    private \PDO $pdo;
    public static string $MIGRATION_PATH;

    public function __construct(array $config)
    {
        self::$MIGRATION_PATH = Application::$BASE_DIR . '/database/migrations';
        $dsn = "mysql:host=" . $config['host'] . ";port=" . $config['port'] . ";dbname=" . $config['dbname'];
        $user = $config['user'];
        $password = $config['password'];
        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function applyMigrations()
    {
        //create migrations table if not exist
        $this->createMigrationsTable();
        //get applied migrations
        $appliedMigrations = $this->getAppliedMigrations();

        //list all migration files
        $files = scandir(self::$MIGRATION_PATH);
        //get migrations to apply
        $migrationsToApply = array_diff($files, $appliedMigrations, ['.', '..']);

        if (empty($migrationsToApply)) {
            echo "No migrations were found.\n";
            exit;
        }
        $newMigrations = [];
        //apply other migrations
        foreach ($migrationsToApply as $migration) {
            //No need for this check
            if ($migration === '.' || $migration === '..') {
                continue;
            }
            $newMigrations[] = $migration;
            $this->applyMigration($migration);
        }
        //save the new applied migrations to migrations table
        $this->saveMigrations($newMigrations);
    }

    private function createMigrationsTable()
    {
        $this->pdo->exec(
            "CREATE TABLE IF NOT EXISTS migrations 
        ( 
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=INNODB;"
        );
    }

    private function getAppliedMigrations()
    {
        $statement = $this->prepare("SELECT migration FROM migrations");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function prepare($query): bool|\PDOStatement
    {
        return $this->pdo->prepare($query);
    }

    public function saveMigrations(array $newMigrations)
    {
        //get the migrations with the format it will be inserted with
        $migrations = implode(',', array_map(fn($migration) => "('{$migration}')", $newMigrations));

        //prepare pdo statement
        $statement = $this->prepare("INSERT INTO migrations (migration) VALUES $migrations");

        //execute the statement
        $statement->execute();
    }

    public function applyMigration($migration)
    {
        $migrationClass = include self::$MIGRATION_PATH . '/' . $migration;
        $migrationClass->up();
    }
}