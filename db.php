<?php
    function connect($host, $dbuser, $dbpassword, $namedb)
    {
        try {
            $db = new PDO("mysql:host=$host;dbname=$namedb", $dbuser,$dbpassword);

            $db->query('CREATE TABLE IF NOT EXISTS review (`id` INT NOT NULL AUTO_INCREMENT , `Name` varchar(255) NOT NULL , `Text` varchar(255) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB');

            return $db;
        }
        catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
        
    }
?>