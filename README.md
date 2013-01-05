dlist - simple directory listing
====

An empty folder presented by [Apache](http://apache.org) (and others) is very ugly. [dlist](https://github.com/amgraham/dlist) strives to replace the default in a less-ugly, and slightly-more-useful manner. I hope you find it useful.

Features
----

dlist's goal is to match the feature set of Apache's default directory list, with a few enhancements (mostly style), in doing so, you can sort the files by the various columns. Clicking them again will reverse the order.

There is a [demo available](http://smarterfish.com/assets/) that showcases the various features and design of dlist. Another [demo is available](http://craft.smarterfish.com/map/) that showcases some of the [helper file](#helper-files) features.

There is a soft feature (one might call it a dangling-of-toes-into-a-cold-pool-implementation) of handling [markdown](http://daringfireball.net/projects/markdown/) files (`*.md`) [which could creep](https://github.com/amgraham/dlist/issues/new) into handling other types of files.

Installation
----

You only *really* need `dir-listing.php`, [grab the file](https://raw.github.com/amgraham/dlist/master/dir-listing.php), make any [adjustments](#enhancement) you might need to make, and place it somewhere accessible on your web-host of choice.

It is recommended to place it somewhere easy (read; universally accessible), and then link it into other directories. Keep in mind, any associated [helper files](#helper-files) will need to go within the various directories that require them.

    cd /var/www/bin/
    ln -s ../assets/dir-listing.php ./index.php

If you wanted to have a `.dir-list` file as well, it must be placed in the same directory.

    #/var/www/bin/.dir-list
    <?php 
    $ignore = array("markdown.php", "secret-file.html");

	$details = "A small collection of <em>hopefully</em> helpful documents.";
	?>

I'm very aware that for multiple directories this is a chore, I'm [open to suggestions](https://github.com/amgraham/dlist/issues/new) on how to alleviate the initial investment.

Enhancement
----

### General Prettiness

It will look prettier if you also utilize four icons and a web-font, they are "turned on" by default so you can get up and running immediately.

The four icons are from http://somerandomdude.com/work/iconic/, while the font is Universalis from http://arkandis.tuxfamily.org/adffonts.html both are included in this release for your convenience.

You should beautify your installation and upload the four icons and web font somewhere easy (read; universally accessible), open `dir-listing.php` in your favorite editor, and update `$imgdir` &amp; `$fontdir` accordingly (wherever you decided to keep the icons and webfont).

A refresh of your browser should display the changes.

### Hidden Files

By default, dlist will not display hidden files (some call them "dot-files") within the current directory; this is intentional and recommended.

You can override the default and change `$showhidden` to `true` and have dlist make your hidden files available for all the world to see. **This is not recommended.** If you follow the recommendation or not, dlist will not show it's [helper files](#helper-files), you can also add your own files to excluded by use of our [helper file](#helper-files) `$ignore` variable.

Helper Files
----

There is one helper file in use: `.dir-list`, it currently has two options: `$ignore` &amp; `$details`.

The first will take whatever is contained in the the array and remove it from being displayed within the rendered page:

	$ignore = array("markdown.php", "secret-file.html");

The second will allow you to include some introductory text at the top of the page:

	$details = "A small collection of <em>hopefully</em> helpful documents.";


Future
----

1. Count of items in a folder (not sure about this one; [feedback welcome](https://github.com/amgraham/dlist/issues/new))
2. Modify the helper file to be able to perform simple regex/wildcard matching.
3. More handlers for more types of files. Source code? Images? Sound (I doubt it). [Requests welcome](https://github.com/amgraham/dlist/issues/new).
4. Easy drop-in stylesheets/themes.