# Candango Roundcube plugin for Matomo Analytics

Easy setup for Matomo Analytics in Roundcube Webmail. 
This is fork of plugin from [myroundcube](http://code.google.com/p/myroundcube/).
Then forked from [roundcube_piwik_tracking](https://github.com/aspaninks/roundcube_piwik_tracking).

## Installation

Just put in plugins/crc_matomo_analytics folder and add crc_matomo_analytics to your `$rcmail_config['plugins']` array.
Now copy the config file:
``cp config/config.inc.php.dist config/config.inc.php``

Open the file in your favorite editor and modify the values to your liking.

## Features

* Set your Matomo Server
* Set whether you want a cookie added to subdomains
* Enable only for non-logged users
* Disable on choosen templates

## License

This plugin is released under Apache License 2.0.
