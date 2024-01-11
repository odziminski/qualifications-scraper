<?php

require_once('vendor/autoload.php');

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use \Symfony\Component\Dotenv\Dotenv;

class WebScraper {
    private RemoteWebDriver $driver;
    private DB $db;

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

    public function scrapeOffers(): void
    {
        $this->driver->get('https://justjoin.it/all-locations/php');

        $bodyHeight = $this->driver->executeScript("return document.body.scrollHeight;");
        $hrefsArray = [];

        $scrollStep = $bodyHeight / 10;

        for ($i = 0; $i <= 20; $i++) {
            $this->driver->executeScript("window.scrollTo(0, $scrollStep * $i);");

            sleep(1);


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
        $levelsArray = [];

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
                    if (!array_key_exists($skill, $skillsCountArray)) {
                        $skillsCountArray[$skill] = ['count' => 0, 'levels' => []];
                    }
                    $skillsCountArray[$skill]['count']++;
                    $skillsCountArray[$skill]['levels'][] = $level;
                }

                $i++;
            }
        }

        foreach ($skillsCountArray as $skill => $data) {
            $averageLevel = $this->calculateAverageLevel($data['levels']);
            $skillsCountArray[$skill]['average_level'] = $averageLevel;
        }

        $this->db->insert(json_encode($skillsCountArray), count($uniqueHrefsArray));

    }

    function calculateAverageLevel($levels): float|int
    {
        $levelValues = [
            "Nice to have" => 1,
            "Junior" => 2,
            "Regular" => 3,
            "Advanced" => 4,
            "Master" => 5
        ];

        $total = 0;
        $count = 0;

        foreach ($levels as $level) {
            if (isset($levelValues[$level])) {
                $total += $levelValues[$level];
                $count++;
            }
        }

        return $count > 0 ? $total / $count : 0;
    }
}

$scraper = new WebScraper();
$scraper->scrapeOffers();
