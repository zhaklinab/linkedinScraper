<?php

namespace App\Http\Controllers;

//use League\OAuth2\Client\Provider\GenericProvider;
use App\AccessToken;
use App\Experience;
use App\LinkedinProfile;
use GuzzleHttp\Client;
use Happyr\LinkedIn\Exception\LinkedInException;
use Illuminate\Http\Request;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;

class LinkedinScraperController extends Controller
{
    protected $email;
    protected $password;

    public function __construct()
    {
        $this->email = config('linkedin.email');
        $this->password = config('linkedin.password');
    }

    public function index()
    {
        return view('welcome');
    }

    public function scraper(Request $request)
    {
        $data = [];
        $host = "localhost:4444/wd/hub";
        $driver  = RemoteWebDriver::create($host, DesiredCapabilities::chrome());
        $url = urlencode($request->url);
        $driver->get($url);

        $currUrl = $driver->getCurrentUrl();
        if($currUrl != $url){
            $driver->get('https://www.linkedin.com');
            $email  = $driver->findElement(WebDriverBy::className('login-email'))->click();
            $driver->getKeyboard()->sendKeys($this->email);
            sleep(0.5);
            $email  = $driver->findElement(WebDriverBy::className('login-password'))->click();
            $driver->getKeyboard()->sendKeys($this->password);
            sleep(0.5);
            $submit  = $driver->findElement(WebDriverBy::className('submit-button'))->click();
            sleep(1.0);

            $driver->get($url);
            $driver->navigate()->to($url);
            //$position = $driver->findElement(WebDriverBy::xpath('//section[@class = "pv-profile-section" ]'));
//            $experience = $driver->findElement(WebDriverBy::xpath('//section[@id = "experience-section"]'));
            $education = $driver->findElement(WebDriverBy::id('education-section'));
//            $skills = $driver->findElement(WebDriverBy::className('.pv-skill-categories-section'));
//            print_r($experience->getText());
            print_r($education->getText());
//            print_r($skills->getText());

        } else {
            $name = $driver->findElement( WebDriverBy::id('name'))->getText();

            $description = $driver->findElement(WebDriverBy::id('summary'))->getText();

            $location = $driver->findElement(WebDriverBy::className('locality'))->getText();

            $linkedinProfile = LinkedinProfile::create([
                'name'          => $name,
                'description'   => $description,
                'location'      => $location,
            ]);

            $linkedinProfile->fresh();

            $experiencesTitle = $driver->findElements(WebDriverBy::cssSelector('ul.positions > li.position > header > h4.item-title'));
            foreach ($experiencesTitle as $key => $title ){
                $data[$key]['job_position'] = $title->getText();
            }

            $experiencesCompanyName = $driver->findElements(WebDriverBy::cssSelector('ul.positions > li.position > header > h5.item-subtitle'));
            foreach ($experiencesCompanyName as $key => $companyName){
                $data[$key]['company_name'] = $companyName->getText();
            }

            $experiencesLocation = $driver->findElements(WebDriverBy::cssSelector('ul.positions > li.position > div.meta > span.location'));
            foreach ($experiencesLocation as $key => $location){
                $data[$key]['location'] = $location->getText();
            }

            $experiencesDateRange = $driver->findElements(WebDriverBy::cssSelector('ul.positions > li.position > div.meta > span.date-range'));
            foreach ($experiencesDateRange as $key => $date){
                $data[$key]['dates'] = $date->getText();
            }

            foreach ($data as $toInsert){
                $experiences = $linkedinProfile->experiences()->create([
                    'job_position'      => $toInsert['job_position'],
                    'company_name'      => $toInsert['company_name'],
                    'location'          => $toInsert['location'],
                    'dates'             => $toInsert['dates'],
                ]);

                return $experiences->fresh();
            }



           /* $educationTitle = $driver->findElements(WebDriverBy::cssSelector('ul.schools > li.school > header > h4.item-title'));
            foreach ($educationTitle as $title) {
                $data2[] = $title->getText();
            }

            $educationDegree = $driver->findElements(WebDriverBy::cssSelector('ul.schools > li.school > header > h4.item-subtitle'));
            foreach ($educationDegree as $degree) {
                $data2[] = $degree->getText();
            }

            $educationRange = $driver->findElements(WebDriverBy::cssSelector('ul.schools > li.school > div.meta > span.date-range'));
            foreach ($educationRange as $date) {
                $data2[] = $date->getText();
            } */



//
//            $languages = $driver->findElement(WebDriverBy::id('languages'));
//            print_r($languages->getText());
//
//            $projects = $driver->findElement(WebDriverBy::id('projects'));
//            print_r($projects->getText());

        }

//        $url = 'https://www.linkedin.com/in/rexhin-vorpsi/';
//        $driver->get($url);
//
//        $currUrl = $driver->getCurrentUrl();
//        if($currUrl != $url){
//            $driver->navigate()->to('https://www.linkedin.com');
//            sleep(3);
//            $email  = $driver->findElement(WebDriverBy::className('login-email'))->click();
//            $driver->getKeyboard()->sendKeys('rexhinvorpsi@yahoo.com');
//            sleep(0.5);
//            $password  = $driver->findElement(WebDriverBy::className('login-password'))->click();
//            $driver->getKeyboard()->sendKeys('andromeda');
//            sleep(0.5);
//            $submit  = $driver->findElement(WebDriverBy::className('submit-button'))->click();
//            sleep(1);
//
//            $driver->navigate()->to($url);
//            //$position = $driver->findElement(WebDriverBy::xpath('//section[@class = "pv-profile-section" ]'));
//            $experience = $driver->findElement(WebDriverBy::xpath('//section[@id = "experience-section"]'));
//            $education = $driver->findElement(WebDriverBy::xpath('//section[@id = "education-section"]'));
//            $skills = $driver->findElement(WebDriverBy::cssSelector('.pv-skill-categories-section'));
//            print_r($experience->getText());
//            print_r($education->getText());
//            print_r($skills->getText());
//        }else {
//            $position = $driver->findElement(WebDriverBy::xpath('//section[@id = "experience-section"]/ul/li[@class="v-profile-section__sortable-item"]'));
//            $education = $driver->findElement(WebDriverBy::xpath('//section[@id = "education"]/ul/li[@class="school"'));
//            print_r($position->getText());
//            print_r($education->fullText());
//            //print_r($fullName->getText());
//            print_r($position->getText());
//            print_r($education->fullText());
//        }
    }
}