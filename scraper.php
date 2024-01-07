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

$driver->get('https://justjoin.it/all-locations/php');

$bodyHeight = $driver->executeScript("return document.body.scrollHeight;");
$hrefsArray = [];
$previousNumberOfLinks = 0;
$currentNumberOfLinks = 0;

$scrollStep = $bodyHeight / 20;

for ($i = 0; $i <= 20; $i++) {
    $previousNumberOfLinks = count($driver->findElements(WebDriverBy::tagName('a')));

    $driver->executeScript("window.scrollTo(0, $scrollStep * $i);");

    sleep(1);

    $currentNumberOfLinks = count($driver->findElements(WebDriverBy::tagName('a')));

    $links = $driver->findElements(WebDriverBy::tagName('a'));

    foreach ($links as $link) {
        $href = $link->getAttribute('href');

        if (str_starts_with(strval($href), '/offers/')) {
            $hrefsArray[] = $href;
        }
    }
}

$uniqueHrefsArray = array_unique($hrefsArray);

var_dump($uniqueHrefsArray);
var_dump(count($uniqueHrefsArray));


