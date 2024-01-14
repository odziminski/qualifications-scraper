<?php

class DB
{
    private static $instance = NULL;
    
    public static function connect(): ?PDO
    {
        $host = $_ENV['DB_HOST'];
        $db = $_ENV['DB_DATABASE'];
        $user = $_ENV['DB_USER'];


            $dbh = new PDO("mysql:host=$host;dbname=$db", $user);
            self::$instance = $dbh;

            return self::$instance;
    }

    public static function insert($skills, $offers_count): void
    {
        $pdo = self::connect();
        $sql = "INSERT INTO qualifications (skills, offers_count) VALUES (?,?)";
        $query = $pdo->prepare($sql);
        if ($query->execute([$skills, $offers_count])){
            echo "New record created successfully \n";
        }
    }
}
