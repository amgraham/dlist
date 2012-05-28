dlist - simple directory listing
====

An empty folder presented by [Apache](http://apache.org) (and others) is very ugly. [dlist](https://github.com/amgraham/dlist) strives to replace the default in a less-ugly, and slightly-more-useful manner. 

There is a [demo available](http://smarterfish.com/assets/) for those people that need proof.

Installation
----

You only *really* need `dir-listing.php`, [grab the file](https://raw.github.com/amgraham/dlist/master/dir-listing.php), make any [adjustments](#enhancement) you might need to make, and place it somewhere accessible on your web-host of choice.

It is recommended to place it somewhere easy (read; universally accessible), and then link it into other directories. Keep in mind, any associated [helper files](#helper-files) will need to go within the various directories that require them.

    cd /var/www/bin/
    ln -s ../assets/dir-listing.php ./index.php

If you wanted to have a `.dir-list-details` file as well, it must be placed in the same directory.

    cd /var/www/bin/
    cat > .dir-list-details
    <p>Hello there!</p>

I'm very aware that for multiple directories this is a chore, I'm [open to suggestions](https://github.com/amgraham/dlist/issues) on how to alleviate the initial investment.

Enhancement
----

### General Prettiness

It will look prettier if you also utilize four icons and a web-font, they are "turned off" by default so you can get up and running immediately.

The four icons are from http://somerandomdude.com/work/iconic/ and included in this release for your convenience.

The font is Universalis from http://arkandis.tuxfamily.org/adffonts.html and included in this release for your convenience.

### Hidden Files

By default, dlist will not display hidden files (some call them "dot-files") within the current directory; this is intentional and recommended.

If you decide to display hidden files, dlist will still hide it's various [helper files](#helper-files).

Helper Files
----

Currently there is one helper file in use: `.dir-list-details`. It will take whatever is contained in the file `.dir-list-details` from the same directory being displyed and insert it at the top of the current directory listing.

There is no processing for security, the text contained within `.dir-list-details` is passed through to the browser.