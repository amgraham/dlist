dlist - simple directory listing
====

An empty folder presented by [Apache](http://apache.org) (and others) is very ugly. [dlist](https://github.com/amgraham/dlist) strives to replace the default in a less-ugly, and slightly-more-useful manner. 

Features
----

dlist's goal is to match the feature set of Apache's default directory list, with a few enhancements (mostly style), in doing so, you can sort the files by the various columns. Clicking them again will reverse the order.

There is a [demo available](http://smarterfish.com/assets/) that showcases the various features and design of dlist. Another [demo is available](http://craft.smarterfish.com/map/) that showcases the [details helper file](#helper-files) feature.

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

The four icons are from http://somerandomdude.com/work/iconic/, while the font is Universalis from http://arkandis.tuxfamily.org/adffonts.html both are included in this release for your convenience.

If you decide to beautify your version upload the four icons and web font somewhere easy (read; universally accessible), open `dir-listing.php` in your favorite editor, and change `$pretty` to `true` then update `$imgdir` &amp; `$fontdir` accordingly (wherever you decided to keep the icons and webfont).

A refresh of your browser should display the changes.

### Hidden Files

By default, dlist will not display hidden files (some call them "dot-files") within the current directory; this is intentional and recommended.

You can override the default and change `$showhidden` to `true` and have dlist make your hidden files available for all the world to see. **This is not recommended.** If you follow the recomendation or not, dlist will not show it's [helper files](#helper-files).

Helper Files
----

Currently there is one helper file in use: `.dir-list-details`. It will take whatever is contained in the file `.dir-list-details` from the same directory being displyed and insert it at the top of the current directory listing. There is a [demo displaying this in action](http://craft.smarterfish.com/map/).

There is no processing for security, the text contained within `.dir-list-details` is passed directly to the browser.

Future
----

1. Count of items in a folder (not sure about this one; feedback welcome)
2. Add another "helper file" to force a specific folder/file to be hidden regardless of the specific setting