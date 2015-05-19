# Easiest way to manage your finances. Online
* LAMP
* Apache(mode_rewrite should be enabled), PHP(has to be >= 5.3), MySQL, Smarty as template engine. 
* jQuery, Bootstrap, Backbone.js with JSmart template engine (same syntax for PHP and JS templates - same templates for PHP and JS).

> - [check out the demo](http://wallet.jeka911.com/)


Installation:
----

1. Create empty folder and navigate to it in CLI:
```bash
mkdir wallet
cd wallet
```
2. Clone repository:
```bash
git clone https://github.com/jeka-kiselyov/wallet.git .
```
3. Create empty MySQL database and edit settings/db.php file. Localhost is determined by SetEnv apache2 directive(check out getenv('localhost') in settings/environment.php), so by default ENVIRONMENT_SERVER != 'localhost', but ENVIRONMENT_SERVER == 'live'.
```bash
	nano settings/db.php
	#edit. Press Ctrl+O and Enter to save and Ctrl+X to exit
```
4. [Install npm](https://docs.npmjs.com/getting-started/installing-node) if you don't have it. 
5. [Install Composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx) if you don't have it.
6. Install dependencies:
```bash
npm install
```
7. Change permissions of every subdirectory in cache folder to be writable by apache:
```bash
	chmod -R 777 cache/*
```
8. Run grunt installation task:
```bash
	grunt install
```
9. Add your domain to apache settings(and /etc/hosts if you are going to test wallet locally). And set home URL in settings/settings.php
10. Open it in browser.
11. Enjoy :)

License
----
GNU Affero GPL

**Free Software, Hell Yeah!**

From Slavyansk, Ukraine. With Love