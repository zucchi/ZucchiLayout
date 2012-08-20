**ZucchiLayout**

Module to allow managment and injection of site layouts

*Installation*

From the root of your ZF2 Skeleton Application run

    ./composer.phar require zucchi/layout
    
This module will require your vhost to use an AliasMatch

    AliasMatch /_([^/]+)/(.+)/([^/]+) /path/to/vendor/$2/public/$1/$3