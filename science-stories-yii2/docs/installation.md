<h1 align="center">
    <a href="http://ozvid.com" title="ozvid" target="_blank">
        <img width = "20%" height = "20%" src="https://ozvid.com/themes/ozvid/img/ozvid_logo.png" alt="OZVID Logo"/>
    </a>
    <br>
    <hr>
</h1>

This is the Yii2-base-admin-panel-rest that will help you with pre-installed modules and many more features on just one go .Few steps are going to be performed by the user to setup the project on their workspace.
This setup already has bunch of inbuild modules that are going to help you with your project and some of those are :-

Yii2-base-admin-panel-rest
Blog Module
Comment Module
Favourite Module
Feature Module
Logger Module
Api Module
Contact Module
Installer Module
Sitemap Module
Settings Module
SMTP Module
Storage Module
Seo Module
Shadow Module
Backup Module
Chat Module


> NOTE: This git respository will provide you enough modules that are going to help you with your on going projects.
        Make sure you follow the steps to make them working for your projects.

## Installation

The preferred way to install this BASE is through [script](http://192.168.10.21/common/scripts.git).
Make sure you place it in root of your htdocs .

To install script module

```
git clone http://192.168.10.21/common/scripts.git
```

To install yii2-admin-panel-rest

```
git clone http://192.168.10.21/yii2/yii2-admin-bootstrap5-1848.git
```

To install submodules

```
bash ../scripts/clone-submodules.sh
```

If you have composer.json

```
composer install --prefer-dist
```

If you need to update vendor again you can use followig command

> If your composer.json have following extensions.

"minimum-stability" : "stable",
"prefer-stable": true,
"config": {
		"optimize-autoloader": true,
        "preferred-install": "dist",
 		"sort-packages": true,
	},

then you have to run only 

```
composer update 

```
Otherwise

```
composer update --prefer-dist --prefer-stable

```


To install database run this command

```
php console.php installer/install -du=admin -dp=admin@123
```

Remember if you run above command, your previous data will format completely. I recommend you to never run this command on live server.

If you face database credentials permission error, then run command php console.php installer/install -du admin -dp admin@123 in my case admin is my database username and admin@123 is my database password.

To install database for single module

```
php console.php installer/install/module -m="modulename"
```

Install default data using : 

``` 
php console.php clear/default
```

## Usage
Once setup is done you need to follow the final setup with the installer .

```
make sure you give READ/WRITE permission to your folder.
```
## License

**www.toxsl.com** 


## To check project flow diagram

1) Open the below link
   https://draw.jiweb.in/

2) Select "open with existing diagram"
   docs/Websiteflow.drawio.xml

3) When you select and open this, then complete project flow diagram will visible in your screen.
   

