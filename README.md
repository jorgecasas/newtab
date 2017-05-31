# New Tab

## What's this?
This project allow you to configure the page which is loaded when a new tab is opened in Chrome, Firefox or your favorite browser.

Using extensions as [New Tab](https://chrome.google.com/webstore/detail/new-tab-redirect/icpgjfneehieebagbmdbhnlpiopdcmna?hl=en) (for Chrome), and having PHP installed, you can edit *config/favorites.json* or *config/projects.json*, adding favicons to images folder, and it will show you a customizable shorcuts for your favorite web pages.

## Installation

Install PHP and Apache. In Linux (Ubuntu / Debian...):

```bash
sudo apt-get update
sudo apt-get install apache2 php
```

Restart Apache:
```bash
sudo service apache2 restart
```

Clone this repository in _/var/www/html_
```bash
cd /var/www/html
git clone git clone git@github.com:jorgecasas/newtab.git .
```

Install one extension in your browser to configure and use New Tab. 

* [New Tab for Chrome](https://chrome.google.com/webstore/detail/new-tab-redirect/icpgjfneehieebagbmdbhnlpiopdcmna?hl=en)
* [New Tab Override for Firefox](https://addons.mozilla.org/en-us/firefox/addon/new-tab-override/)

Configure your extension to open new tab pointing to *http://localhost*

## Configure your own links

You can configure your logo changing **images/header.png** for the logo you want.

There are 2 JSON files:

* *config/favorites.json*: Allow you to add buttons for your most visited links. You need to add a favicon in **images** folder.
* *config/projects.json*: Allow you to add buttons for your own projects. Favicons must be in *https://your.project.url/images/favicon.ico*. Or you can improve this script to add favicons from the **images** folder as well...
