<?php

use Core\Application;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$config = [
    'db' => [
        'host' => $_ENV['DB_HOST'],
        'dbname' => $_ENV['DB_NAME'],
        'port' => $_ENV['DB_PORT'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ]
];
$app = new Application(dirname(__DIR__), $config);

//$app->db->applyMigrations();

$resetIndex = null;

//[-n {filename}] n option to specify the migration filename that timestamp will append to it
$options = getopt("n:", ['migration:'], $resetIndex);

//get the argument [add or migrate(not implemented yet)]
$argument = array_slice($argv, (int)$resetIndex)[0];
if (isset($argument) && $argument === 'add') {
    //get full file path
    $filePath = Application::$app->db::$MIGRATION_PATH . '/' . generateFilePath($options['n']);
    //get template content
    $stubContent = file_get_contents(Application::$BASE_DIR . '/stubs/migration.stub');

    //create migration file with template content
    file_put_contents($filePath, $stubContent);
    $filePathParts = explode('/', $filePath);
    $filename = end($filePathParts);
    echo "{$filename} Created Successfully\n";
    exit(0);
} elseif (isset($argument) && $argument === 'migrate') {
    print_r($options);
    if (isset($options['migration'])) {
        //handle rollback the migrations after the selected migration
        $migrationFile = $options['migration'];
        echo $migrationFile;
        $app->db->applyMigration($migrationFile);
        $app->db->saveMigrations([$migrationFile]);
        echo "{$migrationFile} Migrated Successfully\n";
        exit(0);
    } else {
        $app->db->applyMigrations();
        exit(0);
    }
}


function generateFilePath($filename): string
{
    return time() . '_' . $filename . '.php';
}