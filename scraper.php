<?php

require_once('vendor/autoload.php');

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use \Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');
$chromeDriverPath = '/resources/chromedriver.exe';
$chromeBinaryPath = $_ENV['CHROME_BINARY_PATH'];
$host = $_ENV['WEBDRIVER_HOST'];

$capabilities = DesiredCapabilities::chrome();
$capabilities->setCapability('chrome.binary', $chromeBinaryPath);

$driver = RemoteWebDriver::create($host, $capabilities);
