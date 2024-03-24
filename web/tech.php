<?php

$technology = substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '/') + 1);

require_once('../vendor/autoload.php');
use Symfony\Component\Dotenv\Dotenv;
require_once('../database.php');
$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env');
$db = new DB();

$jobs = $db->getByCategory($technology);

$jobCount = count($jobs);

if ($jobs) {
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?php echo strtoupper($technology); ?> Jobs</title>
    </head>
    <body>
    <h1><?php echo strtoupper($technology); ?> Jobs</h1>

    <?php
    $uniqueSkills = [];

    foreach ($jobs as $jobData) :
        $skills = json_decode($jobData['skills'], true);
        foreach ($skills as $skillName => $skillDetails) {
            if (!in_array($skillName, $uniqueSkills)) {
                $uniqueSkills[] = $skillName;
                ?>
                <h2><?php echo $skillName; ?></h2>
                <ul>
                    <li>Count: <?php echo $skills[$skillName]['count']; ?></li>
                    <li>Average Level: <?php echo $skills[$skillName]['average_level'] ?? 'N/A'; ?></li>
                    <li>Percentage: <?php echo isset($skills[$skillName]['percentage']) ? number_format($skills[$skillName]['percentage'], 2) . '%' : 'N/A'; ?></li>
                </ul>

                <?php
            }
        }
        ?> Last updated: <?php echo $jobData['added_at'];
    endforeach;
    ?>

    </body>
    </html>

    <?php
} else {
    echo "No job offers for technology " . $technology;
}
?>
