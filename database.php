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

    public static function insert($category, $skills, $offers_count): void
    {
        $pdo = self::connect();
        $sql = "INSERT INTO qualifications (category,skills, offers_count) VALUES (?,?,?)";
        $query = $pdo->prepare($sql);
        if ($query->execute([$category, $skills, $offers_count])) {
            echo "New record created successfully \n";
        }
    }

    public static function getByCategory($category): array
    {
        $pdo = self::connect();
        $sql = "SELECT * FROM qualifications WHERE category = ? ORDER BY id DESC LIMIT 1";
        $query = $pdo->prepare($sql);
        $query->execute([$category]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}