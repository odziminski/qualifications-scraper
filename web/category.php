<?php
require_once('../vendor/autoload.php');

use Symfony\Component\Dotenv\Dotenv;

require_once('../database.php');
$dotenv = new Dotenv();

$dotenv->load(__DIR__ . '/../.env');

$db = new DB();

$technology = $_GET['technology'];

header('Content-Type: application/json');
$result = $db->getByCategory($technology);

$jobs = array(
    $technology => $result
);


if (isset($jobs[$technology])) {
    echo json_encode($jobs[$technology]);
} else {
    echo json_encode(array("message" => "No jobs found for this technology."));
}
