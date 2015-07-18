#eabickmull

eZ Publish 'legacy' extension to export articles in ICML format, suitable for import into Adobe InDesign.

##SUMMARY

It is a wrapper for [ickmull](https://code.google.com/p/ickmull) and adds a button to the ezwt
toolbar allowing a user to download the page's text in a ZIP file containing the content in
ICML format plus required images.

##LICENSE

[GNU General Public License 2.0](http://www.gnu.org/licenses/gpl-2.0.html)

##COPYRIGHT

Copyright (C) 2008-2014 [Enterprise AB Ltd](http://eab.uk)

##REQUIREMENTS

Requires eZ Publish 4 or 5 and the following extensions:

* ezwt

##INSTALL

1. Copy the `eabickmull` folder to the `extension` folder.

2. Edit `settings/override/site.ini.append.php`

3. Under `[ExtensionSettings]` add:

        ActiveExtensions[]=eabickmull

4. Run the following script to give Editor role permission to download:

        php extension/eabickmull/update/editor_role_add_policy.php

5. Clear the cache:

        bin/php/ezcache.php --clear-all
