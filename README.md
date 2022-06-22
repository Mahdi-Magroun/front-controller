# front-controller pattern 

## what's a front controller 

it' a design pattern use to delegate all incoming request for your application to a single file that handle them and serve the right content  .

## .htaccess file 

it's a configuration file that provide a way to make configuration changes per-directory the configuration will  apply to that directory, and all subdirectories thereof.

so we will create that file and make some configuration to redirect all the incoming request to a single file that fetch the request and send the appropriate  file  . 

before we can dive into implementation of this file we are going to  apply some configuration to the main conf file of apache2  elsewhere we can not use this future .

### set AllowOverride 

if you work on apache look for your apache2.conf file and change the default value AllowOverride 'none' to AllowOverride All 

```xml
`<Directory /var/www/>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
</Directory>`
```

you can visit : https://httpd.apache.org/docs/2.4/mod/core.html#allowoverride  for more information 

RQ : don't forget to reload your server when you update the apache2.conf file  

### mod_rewrite 

> The `mod_rewrite` module uses a rule-based rewriting engine, based on a PCRE regular-expression parser, to rewrite requested URLs on      the fly. By default, `mod_rewrite` maps a URL to a filesystem path. However, it can also be used to redirect one URL to another URL, or to invoke an internal proxy fetch.  																																																from apache doc 



we are going yo use this mode to redirect all incoming request to a specific file that will act like a front-controller in this will be the index.php 

#### enable mod_rewrite  

we first need to activate `mod_rewrite`. It’s already installed, but it’s disabled on a default Apache installation.

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

don't forget to restart your server 



=> know we are ready to implement the .htaccess file in our project 



### .htacces content : 

the .htaccess should be placed into your root directory 

```
RewriteEngine On 
RewriteRule . src/index.php [L,QSA,B]
```

RewriteRule will redirect any request to src/index.php file 

for more custom rules visit :https://httpd.apache.org/docs/2.4/mod/mod_rewrite.html#rewriterule

#### troubleshooting  for .htaccess 

https://www.keycdn.com/support/htaccess-not-working



## front -controller 

```php 
<?php
include_once "../vendor/autoload.php";
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request= Request::createFromGlobals();
$response= new Response();

switch ($request->getPathInfo()) {
    case '/front-controller/page1.php':
     
        ob_start();
        include __DIR__.'/page1.php';
        $response->setContent(ob_get_clean());
        break;
    case '/front-controller/page2.php':
        ob_start();
        include __DIR__.'/page2.php';
        $response->setContent(ob_get_clean());
        break;
    default:
      $response->setContent("page not found");
      $response->setStatusCode(404);
        break;
    
}
$response->send();
```

im using the http-foundation component for handling request and response you can use the classic way if you want .

