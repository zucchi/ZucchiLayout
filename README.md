ZucchiLayout
============

Module to allow management and injection of site layouts and schedule layout changes

Installation
------------

From the root of your ZF2 Skeleton Application run

    ./composer.phar require zucchi/layout
    
This module will require your vhost to use an AliasMatch

    AliasMatch /_layout/([^/]+)/([^/]+)/(.+) /path/to/project/data/zucchi/layout/$1/assets/$2/$3

This will result in you being able to generate urls such as "/_layout/zucchi-simple/css/style.css"

You will also need to copy the ./config/zucchilayout.local.php.dist to your autoload folder and configure to suit.

Admin
-----

The module depends on the ZucchiAdmin Module to allow you to manage the layouts.

This will allow you to install new layouts and schedule when they will display from

NB: The scheduling is very simplistic at the moment and will always display the most recent scheduled layout.

Layouts
-------

Layouts can be uploaded using as ZIP or TAR files. The file MUST contain the following in its root

*    layout.phtml - The phtml template to use
*    layout.json - A valid Json file containing MetaData about the layout. The file must contain keys for *"name"* and *"vendor"*
*    layout.png - A 560 x 400 pixel image of the layout

If the layout has its own static assets these must be stored in a folder called assets and can be accessed using the AliasMatch defined above

A VERY simplistic sample layout can be found at ./sample/zucchi-layout.tar.gz

Roadmap
-------

*    Improve validation for scheduling for conflict management
*    Add Dependency Management for Layouts


