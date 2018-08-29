<?php

namespace App\Http\Controllers;

use App\LinkedinProfile;
use App\Skill;
use Facebook\WebDriver\Exception\UnknownServerException;
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
        $url = $request->get('url');
        $validator = \Validator::make($request->all(), ['url' => 'required']);

        if ($validator->fails()) {
            return "Url is required";
        }

        $host = "localhost:4444/wd/hub";
        $driver = RemoteWebDriver::create($host, DesiredCapabilities::chrome());
        $driver->get('https://www.linkedin.com');

        $this->authorizeLogin($url, $driver);
        $this->authorizedScraper($driver);

        return "Done";
    }

    private function authorizeLogin($url, $driver)
    {
        $driver->get('https://www.linkedin.com');
        $email  = $driver->findElement(WebDriverBy::className('login-email'))->click();
        $driver->getKeyboard()->sendKeys($this->email);
        sleep(0.5);
        $password  = $driver->findElement(WebDriverBy::className('login-password'))->click();
        $driver->getKeyboard()->sendKeys($this->password);
        sleep(0.5);
        $submit  = $driver->findElement(WebDriverBy::className('submit-button'))->click();
        sleep(1.0);
        try{
            $driver->get($url);
            $driver->navigate()->to($url);
        }catch (UnknownServerException $e){
            return $e->getMessage();
        }

        sleep(1.0);
    }


    private function authorizedScraper($driver)
    {
        $experienceArray = [];
        $educationArray = [];
        $accomplishmentArray = [];
        $topSkillArray = [];
        $otherSkillArray = [];

        $name = $driver->findElement( WebDriverBy::className('pv-top-card-section__name'))->getText();
        if(!$name){
            return "Please enter a valid linkedin profile url!";
        }

        $currentPosition = $driver->findElement(WebDriverBy::className('pv-top-card-section__headline'))->getText();

        $location = $driver->findElement(WebDriverBy::className('pv-top-card-section__location'))->getText();

        $description = $driver->findElement(WebDriverBy::className('lt-line-clamp__line'))->getText();

        $linkedinProfile = LinkedinProfile::create([
            'name'          => $name,
            'description'   => $description,
            'location'      => $location,
            'current_position' => $currentPosition,
        ]);

        $linkedinProfile->fresh();

        $experiencesTitle = $driver->findElements(WebDriverBy::xpath("//section[@id='experience-section']//ul//div//li//a//div//h3"));
        foreach ($experiencesTitle as $key => $title ){
            $experienceArray[$key]['job_position'] = $title->getText();
        }

        $experiencesCompanyName = $driver->findElements(WebDriverBy::xpath("//section[@id='experience-section']//ul//div//li//a//div/child::h4[1]/child::span[2]"));
        foreach ($experiencesCompanyName as $key => $companyName){
            $experienceArray[$key]['company_name'] = $companyName->getText();
        }

        $experiencesLocation = $driver->findElements(WebDriverBy::xpath("//section[@id='experience-section']//ul//div//li//a//div/child::h4[2]/child::span[2]"));
        foreach ($experiencesLocation as $key => $location){
            $experienceArray[$key]['location'] = $location->getText();
        }

        $experiencesDateRange = $driver->findElements(WebDriverBy::xpath("//section[@id='experience-section']//ul//div//li//a//div/child::h4[4]/child::span[2]"));
        foreach ($experiencesDateRange as $key => $date){
            $experienceArray[$key]['dates'] = $date->getText();
        }

        foreach ($experienceArray as $toInsert){
            $experiences = $linkedinProfile->experiences()->create([
                'job_position'      => $toInsert['job_position'] ?? null,
                'company_name'      => $toInsert['company_name'] ?? null,
                'location'          => $toInsert['location'] ?? null,
                'dates'             => $toInsert['dates'] ?? null,
            ]);

            $experiences->fresh();
        }

        $educationTitle = $driver->findElements(WebDriverBy::xpath("//section[@id='education-section']//ul//li//div//a/child::div[2]//div//h3"));
        foreach ($educationTitle as $key => $title) {
            $educationArray[$key]['institution_name'] = $title->getText();
        }

        $educationDegree = $driver->findElements(WebDriverBy::xpath("//section[@id='education-section']//ul//li//div//a/child::div[2]//div/child::p[1]/child::span[2]"));
        foreach ($educationDegree as $key => $degree) {
            $educationArray[$key]['degree'] = $degree->getText();
        }

        $educationRange = $driver->findElements(WebDriverBy::xpath("//section[@id='education-section']//ul//li//div//a/child::div[2]//p[@class='pv-entity__dates Sans-15px-black-70%']/child::span[2]"));
        foreach ($educationRange as $key => $date) {
            $educationArray[$key]['dates'] = $date->getText();
        }

        foreach ($educationArray as $toInsert){
            $educations = $linkedinProfile->educations()->create([
                'institution_name'      => $toInsert['institution_name'] ?? null,
                'degree'                => $toInsert['degree'] ?? null,
                'dates'                 => $toInsert['dates'] ?? null,
            ]);

            $educations->fresh();
        }

        $driver->executeScript('window.scrollTo(0,document.body.scrollHeight);');
        sleep(1.00);

        $driver->findElement(WebDriverBy::xpath("//button/span[contains(text(),'Show more')]"))->click();
        sleep(1.00);

        $topSkills = $driver->findElements(WebDriverBy::xpath("//ol[@class='pv-skill-categories-section__top-skills pv-profile-section__section-info section-info pb4']//li//div//p//a//span[@class='Sans-17px-black-100%-semibold']"));
        foreach ($topSkills as $key => $topSkill){
            $topSkillArray[$key]['name'] = $topSkill->getText();
        }

        foreach ($topSkillArray as $toInsert){
            $topSkills = $linkedinProfile->skills()->create([
                'name'              => $toInsert['name'] ?? null,
                'main_skill'        => Skill::MAIN_SKILL,
            ]);

            $topSkills->fresh();
        }

        $otherSkills = $driver->findElements(WebDriverBy::xpath("//ol[@class='pv-skill-category-list__skills_list list-style-none']//li//div//p//a//span[@class='Sans-17px-black-100%-semibold']"));
        foreach ($otherSkills as $key => $otherSkill){
            $otherSkillArray[$key]['name'] = $otherSkill->getText();
        }

        foreach ($otherSkillArray as $toInsert){
            $otherSkills = $linkedinProfile->skills()->create([
                'name'              => $toInsert['name'] ?? null,
                'main_skill'        => Skill::DEFAULT_SKILL,
            ]);

            $otherSkills->fresh();
        }

        $accomplishments = $driver->findElements(WebDriverBy::xpath("//div[@class='pv-accomplishments-block__list-container']//ul//li"));
        foreach ($accomplishments as $key => $accomplishment){
            $accomplishmentArray[$key]['accomplishment_name'] = $accomplishment->getText();
        }

        foreach ($accomplishmentArray as $toInsert){
            $educations = $linkedinProfile->accomplishments()->create([
                'accomplishment_type'      => 'language',
                'accomplishment_name'      => $toInsert['accomplishment_name'] ?? null,
            ]);

            $educations->fresh();
        }
    }

    /*
        This function can be used if we want to get the public profile from a linkedin page.
        For the moment we are not applying this use case, but just the case where the user
        needs to be authorized. See function authorizedScraper
    */

    private function publicScraper( $driver)
    {
        $experienceArray = [];
        $educationArray = [];
        $languageArray = [];
        $languageProficiencyArray = [];
        $name = $driver->findElement( WebDriverBy::id('name'))->getText();

        $description = $driver->findElement(WebDriverBy::id('summary'))->getText();

        $location = $driver->findElement(WebDriverBy::className('locality'))->getText();

        $currentPosition = $driver->findElement(WebDriverBy::cssSelector('p.headline.title'))->getText();

        $linkedinProfile = LinkedinProfile::create([
            'name'          => $name ?? null,
            'description'   => $description ?? null,
            'location'      => $location ?? null,
            'current_position' => $currentPosition ?? null,
        ]);

        $linkedinProfile->fresh();

        $experiencesTitle = $driver->findElements(WebDriverBy::cssSelector('ul.positions > li.position > header > h4.item-title'));
        foreach ($experiencesTitle as $key => $title ){
            $experienceArray[$key]['job_position'] = $title->getText();
        }

        $experiencesCompanyName = $driver->findElements(WebDriverBy::cssSelector('ul.positions > li.position > header > h5.item-subtitle'));
        foreach ($experiencesCompanyName as $key => $companyName){
            $experienceArray[$key]['company_name'] = $companyName->getText();
        }

        $experiencesLocation = $driver->findElements(WebDriverBy::cssSelector('ul.positions > li.position > div.meta > span.location'));
        foreach ($experiencesLocation as $key => $location){
            $experienceArray[$key]['location'] = $location->getText();
        }

        $experiencesDateRange = $driver->findElements(WebDriverBy::cssSelector('ul.positions > li.position > div.meta > span.date-range'));
        foreach ($experiencesDateRange as $key => $date){
            $experienceArray[$key]['dates'] = $date->getText();
        }

        foreach ($experienceArray as $toInsert){
            $experiences = $linkedinProfile->experiences()->create([
                'job_position'      => $toInsert['job_position'] ?? null,
                'company_name'      => $toInsert['company_name'] ?? null,
                'location'          => $toInsert['location'] ?? null,
                'dates'             => $toInsert['dates'] ?? null,
            ]);

            $experiences->fresh();
        }

        $educationTitle = $driver->findElements(WebDriverBy::cssSelector('ul.schools > li.school > header > h4.item-title'));
        foreach ($educationTitle as $key => $title) {
            $educationArray[$key]['institution_name'] = $title->getText();
        }

        $educationDegree = $driver->findElements(WebDriverBy::cssSelector('ul.schools > li.school > header > h5.item-subtitle'));
        foreach ($educationDegree as $key => $degree) {
            $educationArray[$key]['degree'] = $degree->getText();
        }

        $educationRange = $driver->findElements(WebDriverBy::cssSelector('ul.schools > li.school > div.meta > span.date-range'));
        foreach ($educationRange as $key => $date) {
            $educationArray[$key]['dates'] = $date->getText();
        }

        foreach ($educationArray as $toInsert){
            $educations = $linkedinProfile->educations()->create([
                'institution_name'      => $toInsert['institution_name'] ?? null,
                'degree'                => $toInsert['degree'] ?? null,
                'dates'                 => $toInsert['dates'] ?? null,
            ]);

            $educations->fresh();
        }


        $languages = $driver->findElements(WebDriverBy::cssSelector('ul > li.language > div.wrap > h4.name'));

        foreach ($languages as $key => $language){
            $languageArray[$key]['name'] = $language->getText();
        }

        $languageProficiency = $driver->findElements(WebDriverBy::cssSelector('ul > li.language > div.wrap > p.proficiency'));

        foreach ($languageProficiency as $key => $proficiency){
            $languageProficiencyArray[$key]['proficiency'] = $proficiency->getText();
        }

        foreach ($languageArray as $toInsert ) {
            $languages = $linkedinProfile->accomplishments()->create([
                'accomplishment_type'           => 'language',
                'accomplishment_name'           => $toInsert['name'] ?? null,
                'accomplishment_proficiency'    => $toInsert['proficiency'] ?? null,
            ]);

            $languages->fresh();
        }
    }
}