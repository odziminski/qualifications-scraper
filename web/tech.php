<?php
use Symfony\Component\Dotenv\Dotenv;

$uri = $_SERVER['REQUEST_URI'];
$technology = substr($uri, strrpos($uri, '/') + 1);

require_once('../vendor/autoload.php');
require_once('../database.php');

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env');

$db = new DB();

$job = $db->getTechByCategory($technology);

if ($job) {
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?php echo strtoupper($technology); ?> Jobs</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 20px;
                background-color: #f5f5f5;
            }
            .container {
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
            }
            .skill-card {
                flex: 1 1 calc(33.333% - 20px);
                box-sizing: border-box;
                padding: 10px;
                border-radius: 5px;
                box-shadow: 0 10px 4px rgba(0, 0, 0, 0.1);
                background: linear-gradient(to bottom right, var(--start-color), var(--end-color));
            }
            h2 {
                margin-top: 0;
            }
            ul {
                list-style: none;
                padding: 0;
            }
            ul li {
                margin-bottom: 5px;
            }
            .center {
                text-align: center;
            }
            .js {
                --start-color: #F0F4C3;
                --end-color: #E8F5E9;
            }
            .html {
                --start-color: #FCE4EC;
                --end-color: #F8BBD0;
            }
            .php {
                --start-color: #E3F2FD;
                --end-color: #BBDEFB;
            }
            .java {
                --start-color: #E1F5FE;
                --end-color: #B3E5FC;
            }
            .python {
                --start-color: #E3F2FD;
                --end-color: #BBDEFB;
            }
            .ruby {
                --start-color: #F8BBD0;
                --end-color: #FCE4EC;
            }
            .net {
                --start-color: #E8F5E9;
                --end-color: #C8E6C9;
            }
            .c {
                --start-color: #E3F2FD;
                --end-color: #BBDEFB;
            }
            .testing {
                --start-color: #F5F5F5;
                --end-color: #CFD8DC;
            }
            .devops {
                --start-color: #E1F5FE;
                --end-color: #B3E5FC;
            }
            .go {
                --start-color: #E0F2F1;
                --end-color: #B2DFDB;
            }
            .scala {
                --start-color: #FCE4EC;
                --end-color: #F8BBD0;
            }
            .mobile {
                --start-color: #E8F5E9;
                --end-color: #C8E6C9;
            }
            .security {
                --start-color: #F3E5F5;
                --end-color: #E1BEE7;
            }
            .other {
                --start-color: #F5F5F5;
                --end-color: #CFD8DC;
            }
        </style>
    </head>
    <body>
    <h1 class="center"><?php echo strtoupper($technology); ?> Jobs</h1>
    <div class="container">
        <?php
        $uniqueSkills = [];

        $skills = json_decode($job['skills'], true);
        foreach ($skills as $skillName => $skillDetails) {
            if (!in_array($skillName, $uniqueSkills)) {
                $uniqueSkills[] = $skillName;
                ?>
                <div class="skill-card <?php echo strtolower($technology); ?>">
                    <h2><?php echo $skillName; ?></h2>
                    <ul>
                        <li>Count: <?php echo $skills[$skillName]['count']; ?></li>
                        <li>Average Level: <?php echo $skills[$skillName]['average_level'] ?? 'N/A'; ?></li>
                        <li>Percentage: <?php echo isset($skills[$skillName]['percentage']) ? number_format($skills[$skillName]['percentage'], 1) . '%' : 'N/A'; ?></li>
                    </ul>
                </div>
                <?php
            }
        }
        ?>
    </div>
    <p>Last updated: <?php echo $job['added_at']; ?></p>
    </body>
    </html>

    <?php
} else {
    echo "No job offers for technology " . $technology;
}
?>
