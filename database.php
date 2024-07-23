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

    public static function getByCategory($category): array | bool
    {
        $pdo = self::connect();
        $sql = "SELECT id,category, skills, offers_count, added_at
        FROM qualifications
        WHERE category = ?
        ORDER BY added_at DESC";
        $query = $pdo->prepare($sql);
        $query->execute([$category]);
        $data = $query->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $data = self::sortDataArray($data);
        }

        return $data;
    }

    public static function sortDataArray($data)
    {
        $skills = json_decode($data['skills'], true);
        if (is_array($skills)){
            $sortedSkills = [];
            foreach ($skills as $key => $value) {
                $sortedSkills[] = ['technology' => $key, 'data' => $value];
            }

            usort($sortedSkills, function ($a, $b) {
                return $b['data']['count'] <=> $a['data']['count'];
            });

            $sortedSkillsAssoc = [];
            foreach ($sortedSkills as $item) {
                $sortedSkillsAssoc[$item['technology']] = $item['data'];
            }

            $data['skills'] = json_encode($sortedSkillsAssoc);
        }

        return $data;
    }
}