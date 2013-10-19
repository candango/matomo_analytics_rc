#Roundcube plugin for piwik_tracking

Easy setup for PiWik Tracking in Roundcube Webmail. 
This is fork of plugin from [myroundcube](http://code.google.com/p/myroundcube/).
Then forked from [roundcube_google_analytics](https://github.com/igloonet/roundcube_google_analytics).

## Installation

Just put in plugins/piwik_tracking folder and add piwik_tracking to your `$rcmail_config['plugins']` array.
Now copy the config file:
``cp config/config.inc.php.dist config/config.inc.php``

Open the file in your favorite editor and modify the values to your liking.

## Features

* Set your PiWik Server
* Set whether you want a cookie added to subdomains
* Enable only for non-logged users
* Disable on choosen templates

## License

This plugin is released under GNU GPL
