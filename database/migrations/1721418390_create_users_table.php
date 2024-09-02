<?php

use Core\Database\Migration;

return new class extends Migration {


    public function up(): void
    {
        $this->connection->execute("
        CREATE TABLE IF NOT EXISTS `users` (
           id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            name varchar(255) NOT NULL
        )ENGINE=InnoDB;");
    }

    public function down(): void
    {
        $this->connection->execute("DROP TABLE IF EXISTS `users`");
    }
};