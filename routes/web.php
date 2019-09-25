<?php
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\PhpExecutableFinder;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('content');
});

//Route::get('/scraping/{url}', 'ScrapingController@example');
/*

Route::get('/scraping', function () {

    $process = new Process('cd C:\Users\miksm\OneDrive\Documentos\GitHub\Final\laravel-master && C:\xampp\php\php.exe artisan dusk -v');
    $process->setPTY(true);
    $process->run();

  //  if (!$process->isSuccessful()) {
  //      throw new ProcessFailedException($process);
  //  }

    echo '<pre>'.$process->getOutput();
});

*/
