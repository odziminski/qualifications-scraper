<?php

require_once('vendor/autoload.php');

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use \Symfony\Component\Dotenv\Dotenv;

class WebScraper {
    private $driver;
    private $db;

    public function __construct() {
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__ . '/.env');

        require_once ('database.php');

        $chromeDriverPath = '/resources/chromedriver.exe';
        $chromeBinaryPath = $_ENV['CHROME_BINARY_PATH'];
        $host = $_ENV['WEBDRIVER_HOST'];

        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability('chrome.binary', $chromeBinaryPath);

        $this->driver = RemoteWebDriver::create($host, $capabilities);
        $this->db = new DB();
    }

    public function scrapeSite() {
        $this->driver->get('https://justjoin.it/all-locations/php');

        $bodyHeight = $this->driver->executeScript("return document.body.scrollHeight;");
        $hrefsArray = [];
        $previousNumberOfLinks = 0;
        $currentNumberOfLinks = 0;

        $scrollStep = $bodyHeight / 10;

        for ($i = 0; $i <= 20; $i++) {
            $previousNumberOfLinks = count($this->driver->findElements(WebDriverBy::tagName('a')));

            $this->driver->executeScript("window.scrollTo(0, $scrollStep * $i);");

            sleep(1);

            $currentNumberOfLinks = count($this->driver->findElements(WebDriverBy::tagName('a')));

            $links = $this->driver->findElements(WebDriverBy::tagName('a'));

            foreach ($links as $link) {
                $href = $link->getAttribute('href');

                if (str_starts_with(strval($href), '/offers/')) {
                    $hrefsArray[] = $href;
                }
            }
        }

        $uniqueHrefsArray = array_unique($hrefsArray);
        $skillsCountArray = [];

        foreach ($uniqueHrefsArray as $href) {
            $this->driver->get('https://justjoin.it' . $href);
            sleep(1);
            $i = 1;
            while (true) {
                $xpathSkill = "/html/body/div[1]/div[2]/div[2]/div/div[2]/div[2]/div[3]/div/ul/div[$i]/div/h6";
                $xpathLevel = "/html/body/div[1]/div[2]/div[2]/div/div[2]/div[2]/div[3]/div/ul/div[$i]/div/span";

                $elementsSkill = $this->driver->findElements(WebDriverBy::xpath($xpathSkill));
                $elementsLevel = $this->driver->findElements(WebDriverBy::xpath($xpathLevel));

                if (count($elementsSkill) == 0) {
                    break;
                }
                foreach ($elementsSkill as $elementSkill) {
                    $skill = $elementSkill->getText();
                }
                foreach ($elementsLevel as $elementLevel) {
                    $level = $elementLevel->getText();
                }

                if (isset($skill) && isset($level)) {
                    if (array_key_exists($skill, $skillsCountArray)) {
                        $skillsCountArray[$skill]++;
                    } else {
                        $skillsCountArray[$skill] = 1;
                    }
                }

                $i++;
            }
        }
        arsort($skillsCountArray);
        $this->db->insert(json_encode($skillsCountArray), count($uniqueHrefsArray));

    }
}

$scraper = new WebScraper();
$scraper->scrapeSite();
