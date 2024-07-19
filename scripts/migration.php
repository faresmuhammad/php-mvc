<?php


require_once dirname(__DIR__) . '/vendor/autoload.php';

define("BASE_PATH", dirname(__DIR__));
const MIGRATION_PATH = BASE_PATH . '/database/migrations';

$resetIndex = null;

//[-n {filename}] n option to specify the migration filename that timestamp will append to it
$options = getopt("n:", [], $resetIndex);

//get the argument [add or migrate(not implemented yet)]
$argument = array_slice($argv, (int)$resetIndex);
if (isset($argument[0]) && $argument[0] === 'add') {
    //get full file path
    $filePath = MIGRATION_PATH . '/' . generateFilePath($options['n']);
    //get template content
    $stubContent = file_get_contents(BASE_PATH . '/stubs/migration.stub');

    //create migration file with template content
    file_put_contents($filePath, $stubContent);
    echo "$filePath Created Successfully\n";
    exit(0);
}

//apply migrations [migrate]
//all [no option]
//specific migration [--filename {name with timestamp}]

function generateFilePath($filename): string
{
    return time() . '_' . $filename . '.php';
}
