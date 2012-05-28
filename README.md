dlist - simple directory listing
====

An empty folder presented by Apache (and others) is very ugly. Now it can be less ugly. You can view a [demo online](http://smarterfish.com/assets/).

Installation
----

You only *really* need `dir-listing.php`, you should place it somewhere accessible on your web-host of choice.

There are various settings contained within the file (dir-listing.php) that are set to their safest options. You can adjust them accordingly. 

It is recommended to place it somewhere easy (read; universally accessible), and then link it into other directories. Keep in mind, any associated "helper files" will need to go into the linked directories.

    cd /var/www/bin/
    ln -s ../assets/dir-listing.php ./index.php

If you wanted to have a `.dir-list-details` file as well, it must be placed in the same directory.

    cd /var/www/bin/
    cat > .dir-list-details
    <p>Hello there!</p>

I'm very aware that for multiple directories this is a chore, I'm open to suggestions on how to alleviate the initial investment.

Enhancement
----

It will look prettier if you also utilize four icons and a web-font, they are "turned off" by default so you can get up and running immediately.

The four icons are from http://somerandomdude.com/work/iconic/ and included in this release for your convenience.

The font is Universalis from http://arkandis.tuxfamily.org/adffonts.html and included in this release for your convenience.