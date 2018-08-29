## Linkedin Scraper
 
> Note: To use this project you should have installed in your machine, <a href="https://www.seleniumhq.org/download/">a version of Selenium </a>, a driver of your favorite browser (mine <a href="http://chromedriver.chromium.org/downloads">Chrome </a>), composer to run `composer install` for building the `Laravel Project`. 

> Note 2: For authorized linkedin Scraper you need to put in your enviroment file: 

* LINKEDIN_EMAIL
* LINKEDIN_PASSWORD

- Once you do all of the above, you can use the rest of the project.

1. Run selenium 

```
    java -Dwebdriver.chrome.driver="chromedriver.exe" -jar selenium-server-standalone-x.x.x.jar
```

2. Run `php artisan serve` in the directory of your project

### Go Scraper 

- REQUEST : `GET`
- URL : `/go-scraper`

### Scraping

- REQUEST: `POST`
- URL: `/api/scraper`
- Example Request: 

```json 
{
   "url": "https://www.linkedin.com/in/zhaklina-basha/"
}
```
