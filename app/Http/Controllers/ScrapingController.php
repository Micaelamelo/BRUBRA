<?php
namespace App\Http\Controllers;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Process\Process;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Chrome\ChromeProcess;
use Laravel\Dusk\ElementResolver;
use Tests\DuskTestCase;
use Exception;
use Ping;

class ScrapingController extends Controller {

  public function example($baseurl) { //aca busco la info de todo y paso por prpops a cada clase lo correspondiente

    $process = (new ChromeProcess)->toProcess();
    $process->start(null, [
        'SystemRoot' => 'C:\\WINDOWS',
        'TEMP' => 'C:\Users\miksm\AppData\Local\Temp',
    ]);

    $options = (new ChromeOptions)->addArguments(['--disable-gpu', '--headless']);
    $capabilities = DesiredCapabilities::chrome()->setCapability(ChromeOptions::CAPABILITY, $options);
    $driver = retry(5, function () use($capabilities) {
        return RemoteWebDriver::create('http://localhost:9515', $capabilities);
    }, 50);

    $browser = new Browser($driver);
    $base_url = "https://www.amazon.es/sp?$baseurl";
    $browser->visit($base_url);
    $comments="";

    $contacts=array();
    $acumulativo=1;
    $incrementar=0;
    $cambio=false;

    $positivos=0;
    $negativos=0;
    $neutros=0;
    $puntajes=array();

    $All_data= array();

    $palabras_colores= array();



    while($browser->elements('[id="feedback-next-link"]')){ //todas las reseñas
      foreach ($browser->elements('[class="feedback-row"]') as $element) {

          $comment = $element->findElement(WebDriverBy::cssSelector('[class="a-section a-spacing-small"]'))->getText(); //los comentarios
          $author_date = $element->findElement(WebDriverBy::cssSelector('span.a-size-base.a-color-secondary.feedback-rater'))->getText(); //los autores y fecha
          $ratings = $element->findElement(WebDriverBy::cssSelector('[class="a-icon-alt"]'))->getAttribute("textContent"); //el puntaje

          $comment = str_replace('á', 'a', $comment);
          $comment = str_replace('é', 'e', $comment);
          $comment = str_replace('í', 'i', $comment);
          $comment = str_replace('ó', 'o', $comment);
          $comment = str_replace('ú', 'u', $comment);

          $comments= $comments . " " . $comment;


          $reverso=array_reverse($contacts);
          $ultimo= array_pop($reverso);

          $fecha=$this->fecha($author_date);
          $ratings=$this->rating($ratings);


          $palabra_color = [];
          $palabra_color['rating']= $ratings;
          $palabra_color['comment']= $comment;
          array_unshift($palabras_colores, $palabra_color);

          if($ratings>3)
            $positivos ++; //estos ya los podria tener desde gral rating
          if($ratings==3)
            $neutros ++;
          if($ratings<3)
            $negativos++;

          if($ultimo!=null && strcmp($ultimo['fecha'],$fecha)==0){
            $acumulativo=$acumulativo+1;
            $incrementar=$incrementar+$ratings;
            $cambio=true;
          }
          else{
            if($ultimo!=null && $cambio==true){
              array_shift($contacts);
              $contact=[];
              $contact['fecha']= $ultimo['fecha'];
              $contact['rating']=($ultimo['rating']+$incrementar)/$acumulativo;
              array_unshift($contacts, $contact);
              $acumulativo=1;
              $incrementar=0;
              $cambio=false;

            }

          $contact=[];
          $contact['fecha']= $this->fecha($author_date);
          $contact['rating']=$ratings;

           array_unshift($contacts, $contact);
        }


      }

      try{
         if($browser->assertVisible('#feedback-next-link.a-link-normal')){ //click next
             $browser ->click('#feedback-next-link.a-link-normal')
                      ->pause(1000);
       }
     }catch(\Exception $e){ //para la ultima pagina
        $frequent= $this->extract_common_words($comments, $palabras_colores);

        $puntaje=[];
        $puntaje['positivos']= $positivos;
        $puntaje['neutros']= $neutros;
        $puntaje['negativos']= $negativos;
        array_push($puntajes, $puntaje);

        $data=[];
        $data['frequent']= $frequent;
        $data['puntajes']= $puntajes;
        $data['contacts']= $contacts;
        array_push($All_data, $data);

        return  response()->json($All_data,201);

        }

    }

  }


    public function fecha($author_date){ //me quedo con año
      $parts= explode(' el ', $author_date);
      $parts= explode(' de ', $parts[1]);
      $mes= $parts[1];
      str_replace(" ","",$mes);

      $año = strrchr($author_date,' ');
      str_replace(" ","",$año);
      str_replace(".","",$año);

      return $mes . $año;
    }

    public function rating($ratings){
      $parts= explode('de', $ratings);
      str_replace(" ","",$parts[0]);
      return (int) $parts[0];
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

      function extract_common_words($string, $palabras_colores, $max_count = 8) {
        $stop_words = file(__DIR__."/stop_words.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $string = preg_replace('/ss+/i', '', $string);
        $string = trim($string); // trim the string
        $string = preg_replace('/[^a-zA-Z -]/', '', $string); // only take alphabet characters, but keep the spaces and dashes too…
        $string = strtolower($string); // make it lowercase

        preg_match_all('/\b.*?\b/i', $string, $match_words);
        $match_words = $match_words[0];

        foreach ( $match_words as $key => $item ) {
            if ( $item == '' || in_array(strtolower($item), $stop_words) || strlen($item) <= 3 ) {
                unset($match_words[$key]);
            }
        }

        $word_count = str_word_count(implode(" ", $match_words) , 1);
        $frequency = array_count_values($word_count);
        arsort($frequency);

        //arsort($word_count_arr);
        $keywords = array_slice($frequency, 0, $max_count);

        $keywords_array=[];
          $suma=0;
        foreach ($keywords as $key => $value){
            $element=[];
            $element['word']=$key;
            $element['frequency']=$value;

            $edges= array();
              $suma=0;
            foreach ($palabras_colores as $palabra){
                if(stripos($palabra['comment'], $key) !== false){ //esto para los nodos
                  $suma= $suma + $palabra['rating'];

                  foreach($keywords as $key2 => $value2){ //arcos
                    if($key!=$key2){
                      if(stripos($palabra['comment'], $key2) !== false){
                        array_push($edges, $key2);
                      }
                    }
                  }
                }
            }

          $element['rating']=round($suma/$value,1);
          $element['edges']=$edges;

          array_push($keywords_array, $element);
      }
        return $keywords_array;

    }

/*
      public function PARA_WORD_CLOUD($baseurl){

            $process = (new ChromeProcess)->toProcess();
            $process->start(null, [
                'SystemRoot' => 'C:\\WINDOWS',
                'TEMP' => 'C:\Users\miksm\AppData\Local\Temp',
            ]);

            $options = (new ChromeOptions)->addArguments(['--disable-gpu', '--headless']);
            $capabilities = DesiredCapabilities::chrome()->setCapability(ChromeOptions::CAPABILITY, $options);
            $driver = retry(5, function () use($capabilities) {
                return RemoteWebDriver::create('http://localhost:9515', $capabilities);
            }, 50);

            $browser = new Browser($driver);
            $base_url = "https://www.amazon.es/sp?$baseurl";
            $browser->visit($base_url);

            $comments="";

            while($browser->elements('[id="feedback-next-link"]')){ //todas las reseñas
              foreach ($browser->elements('[class="feedback-row"]') as $element) {
                  $comment = $element->findElement(WebDriverBy::cssSelector('[class="a-section a-spacing-small"]'))->getText(); //los comentarios

                  $comments= $comments . " " . $comment;
                }

              try{
                 if($browser->assertVisible('#feedback-next-link.a-link-normal')){ //click next
                     $browser ->click('#feedback-next-link.a-link-normal')
                              ->pause(1000);
               }
             }catch(\Exception $e){ //para la ultima pagina
                $frequent= $this->extract_common_words( $comments);
                return $frequent;
                }
            }
      }

      public function PARA_TIMELINE_CHART($baseurl) { //Para el grafico de linea de tiempo del vendedor

        $process = (new ChromeProcess)->toProcess();
        $process->start(null, [
            'SystemRoot' => 'C:\\WINDOWS',
            'TEMP' => 'C:\Users\miksm\AppData\Local\Temp',
        ]);

        $options = (new ChromeOptions)->addArguments(['--disable-gpu', '--headless']);
        $capabilities = DesiredCapabilities::chrome()->setCapability(ChromeOptions::CAPABILITY, $options);
        $driver = retry(5, function () use($capabilities) {
            return RemoteWebDriver::create('http://localhost:9515', $capabilities);
        }, 50);

        $browser = new Browser($driver);
        $base_url = "https://www.amazon.es/sp?$baseurl";
        $browser->visit($base_url);

        $contacts=array();
        $acumulativo=1;
        $incrementar=0;

        while($browser->elements('[id="feedback-next-link"]')){ //todas las reseñas
          foreach ($browser->elements('[class="feedback-row"]') as $element) {
              $author_date = $element->findElement(WebDriverBy::cssSelector('span.a-size-base.a-color-secondary.feedback-rater'))->getText(); //los autores y fecha
              $ratings = $element->findElement(WebDriverBy::cssSelector('[class="a-icon-alt"]'))->getAttribute("textContent"); //el puntaje

              $reverso=array_reverse($contacts);
              $ultimo= array_pop($reverso);

              $fecha=$this->fecha($author_date);
              $ratings=$this->rating($ratings);

              if($ultimo!=null && strcmp($ultimo['fecha'],$fecha)==0){
                $acumulativo=$acumulativo+1;
                $incrementar=$incrementar+$ratings;
              }
              else{
                if($ultimo!=null){  //esto se tendria q optimizar
                  array_shift($contacts);
                  $contact=[];
                  $contact['fecha']= $ultimo['fecha'];
                  $contact['rating']=($ultimo['rating']+$incrementar)/$acumulativo;
                  array_unshift($contacts, $contact);
                  $acumulativo=1;
                  $incrementar=0;
                }

              $contact=[];
              $contact['fecha']= $this->fecha($author_date);
              $contact['rating']=$ratings;

               array_unshift($contacts, $contact);
            }

        }
          try{
             if($browser->assertVisible('#feedback-next-link.a-link-normal')){ //click next
                 $browser ->click('#feedback-next-link.a-link-normal')
                          ->pause(1000);
            }
          }catch(\Exception $e){ //para la ultima pagina
             return $contacts;
          }
        }
      }



      public function PARA_BAR_CHART($baseurl) {  //ESTO ES LO NECESARIO PARA EL BARCHART Y EL DOUGHNUT CHART

        $process = (new ChromeProcess)->toProcess();
        $process->start(null, [
            'SystemRoot' => 'C:\\WINDOWS',
            'TEMP' => 'C:\Users\miksm\AppData\Local\Temp',
        ]);

        $options = (new ChromeOptions)->addArguments(['--disable-gpu', '--headless']);
        $capabilities = DesiredCapabilities::chrome()->setCapability(ChromeOptions::CAPABILITY, $options);
        $driver = retry(5, function () use($capabilities) {
            return RemoteWebDriver::create('http://localhost:9515', $capabilities);
        }, 50);

        $browser = new Browser($driver);
        $base_url = "https://www.amazon.es/sp?$baseurl";
        $browser->visit($base_url);

        $positivos=0;
        $negativos=0;
        $neutros=0;
        $puntajes=array();

        while($browser->elements('[id="feedback-next-link"]')){ //todas las reseñas
          foreach ($browser->elements('[class="feedback-row"]') as $element) {
              $ratings = $element->findElement(WebDriverBy::cssSelector('[class="a-icon-alt"]'))->getAttribute("textContent"); //el puntaje

              if($ratings>3)
                $positivos ++; //estos ya los podria tener desde gral rating
              if($ratings==3)
                $neutros ++;
              if($ratings<3)
                $negativos++;

        }
          try{
             if($browser->assertVisible('#feedback-next-link.a-link-normal')){ //click next
                 $browser ->click('#feedback-next-link.a-link-normal')
                          ->pause(1000);
            }

          }catch(\Exception $e){ //para la ultima pagina
             array_push($puntajes, $positivos);
             array_push($puntajes, $neutros);
             array_push($puntajes, $negativos);
             return $puntajes;
          }
        }
      }
      */
}
