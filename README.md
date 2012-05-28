dlist - simple directory listing
====

An empty folder presented by Apache (and others) is very ugly. Now it can be less-ugly. You can view the [demo online](http://smarterfish.com/assets/).

Installation
----

You only *really* need `dir-listing.php`, place it somewhere accessible on your web-host of choice. It is recommended to place it somewhere generic, and then link it into other directories.

    cd /var/www/bin/
    ln -s ../assets/dir-listing.php ./index.php

I'm very aware that for multiple directories this is a chore, I'm open to suggestions on how to alleviate the initial investment.

There are various settings contained within the file (dir-listing.php) that are set to their safest options. You can adjust them accordingly.

Enhancement
----

It will look prettier if you also utilize four icons and a web-font, they are "turned off" by default so you can get up and running immediately, you should enable them though.

The four icons are from http://somerandomdude.com/work/iconic/ and included in this release for your convenience.

The font is Universalis from http://arkandis.tuxfamily.org/adffonts.html and included in this release for your convenience.