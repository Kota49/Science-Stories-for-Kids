#### About 
The main objective of this project is to design and develop the multilingual Science Stories for Kids Mobile app for both Android and iOS platforms, providing an immersive educational experience for children through illustrated science-themed stories. The platform will incorporate static JPEG illustrations, narration, audio effects, and interactive elements. Customer can search, like, download it in offline mode, purchase and gift them to others.

The primary goal is to offer a user-friendly interface for customers while enabling efficient content management for administrators.

### INSTALLATION

- The minimum required PHP version of Yii is PHP 5.4.
- It works best with PHP 8.
- [Follow the Installation Guide](http://192.168.10.42/yii2/science-stories-yii2-1980/-/tree/master/docs/installation.md)
in order to get step by step instructions.

 ### REUSEABILITY

Authentication Module, Rating Module, Chat Module, Page Module for static pages integration, FAQ module, Notification Module for sending notification to users.



 
### Documentation

- A [Definitive Guide](https://www.yiiframework.com/doc/guide/2.0). 

### Directory Structure

```
config/              all modules paths and application configuration 
docs/                documentation
protected/           core framework code
tests/               tests of the core framework code
```

### Language Translation CMD

```
php console.php message/extract @app/config/i18n.php {on root path}
```

### RUN PROJECT

Goto url: http://localhost/science-stories-yii2-1980

Create an admin account. I recommend you to use email as admin@ozvid.in and password as Admin@123


**In projects**

If you are using Yii 2 base as part of your project there are some important points that you need to takecare throught out of your whole development phase . 


Existing Modules:
-----------------


-installer

-logger 

-rating

-setting

-shadow

-sitemap

-backup

-notification

-Page

-seo

-contact

-feature

-favorite

-comment

-faq

New Module:
-----------------

-api

-book Mangement

-banners

-Promocode



### CheckList

> NOTE: Refer the [CheckList](http://192.168.10.42/yii2/science-stories-yii2-1980/-/tree/master/docs/checklist.md) for details on all the security concerns and other important parameters of the project before its actual releasing.

### Coding Guidelines

> NOTE: Refer the [Coding Guidelines](http://192.168.10.42/yii2/science-stories-yii2-1980/-/tree/master/docs/coding-guidelines.md) for details on all the security concerns and other important parameters of the project before its actual releasing.

### installation command

> To install submodules
	bash ../scripts/clone-submodules.sh 

> If you have composer.json
	composer install --prefer-dist 

> If you need to update vendor again you can use followig command
	composer update --prefer-dist --prefer-stable

> To install database run this command
	php console.php installer/install -du=admin -dp=admin@123

> Install default data using : 
	php console.php clear/default

## Git Sync Commands

> first:  sync for all bash ../scripts/git-sync-all.sh .

> second: sync for  master  bash ../scripts/git-sync-all.sh master

> Third: sync for  main  bash ../scripts/git-sync-all.sh main

> At Last Composer Update :composer update



