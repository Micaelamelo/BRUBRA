<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Symfony\Component\DomCrawler\Crawler;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
use App\Http\Controllers\ScrapingController;

class ScrapeTheWebTest extends DuskTestCase
{

    protected $base_url = "https://www.amazon.com/sp?seller=A36B1BUM0PHEMV";

    /** @test */
    public function clickButton()
    {
        $url = $this->base_url;
        $this->assertTrue(true); //hardcodeo para que no sea risky

        $this->browse(function (Browser $browser) use ($url) {

            $browser->visit($url)
            ->assertPresent('.a-icon-alt');

            foreach ($browser->elements('#seller-feedback-summary') as $element) {
                $generalRating = $element->findElement(WebDriverBy::cssSelector('[class="a-link-normal feedback-detail-description"]'))->getAttribute("innerHTML"); //los autores y fecha
                $gralRating= $this->gralRating($generalRating);
                $commentSize= $this->commentSize($generalRating);
              }
          /*
           while($browser->elements('[id="feedback-next-link"]') ){

              foreach ($browser->elements('[class="feedback-row"]') as $element) {

                  $author_date = $element->findElement(WebDriverBy::cssSelector('[class="a-section a-spacing-medium"]'))->getText();
                  $ratings = $element->findElement(WebDriverBy::cssSelector('[class="a-icon-alt"]'))->getAttribute("textContent");
                  $comment = $element->findElement(WebDriverBy::cssSelector('[class="a-section a-spacing-small"]'))->getText();

                  echo PHP_EOL;
                  echo $author_date . $ratings . $comment;
                  echo PHP_EOL;
            }

            try{
             if($browser->assertVisible('#feedback-next-link.a-link-normal')){
                 $browser ->click('#feedback-next-link.a-link-normal')
                          ->pause(5000);
            }
          }catch(\Exception $e){
             return false;
          }

        } */
      });

    }

    public function commentSize($generalRating){
      $parts= explode('(', $generalRating);
      $guardar= $parts[1];
      $parts= explode('total', $guardar);
      $guardar= $parts[0];
      return $guardar;
    }


    public function gralRating($generalRating){
        $parts= explode('>', $generalRating);
        $guardar= $parts[1];
        $parts= explode('%', $guardar);
        return $parts[0];
    }
}
