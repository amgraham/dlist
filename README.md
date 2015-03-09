dlist - simple directory listing
====

An empty folder presented by [Apache](http://apache.org) (and others) is very ugly, offers little in customization, and can be a chore to work with. [dlist](https://github.com/amgraham/dlist) strives to replace the default in a less-ugly, and slightly-more-useful manner. 

I hope you find it as useful as I have attempted to make it.

Features
----

dlist's goal is to match the feature set of Apache's default directory list, with a few enhancements--mostly style, but a little substance.

There is a [demo available](http://craft.smarterfish.com/map/) that showcases the various features and design of dlist.

Installation
----

You only **really** need `dir-listing.php`, [grab the file](https://raw.github.com/amgraham/dlist/master/dir-listing.php), make any [adjustments](#enhancement) you might need to make, and place it somewhere accessible on your web-host of choice.

If you are going to want to utilize dlist multiple times it is recommended to place it somewhere universally accessible, and then link it into other directories. 

    cd /var/www/
    ln -s assets/dlist/dir-listing.php ./index.php

Keep in mind, any associated [helper files](#helper-file) will need to go within the various directories that require them.

Enhancement 
----

### General Prettiness

It will look prettier if you also utilize four icons and web-font. They are "turned on" by default, but you can turn them off easily by changing `$pretty` to `$false`.

The four icons are from <http://somerandomdude.com/work/iconic/>, while the font is Universalis from <http://arkandis.tuxfamily.org/adffonts.html> both are included in this release for your convenience. If you happen to use dlist for processing markdown files there is an additional font in use: Anonymous from <http://www.ms-studio.com/FontSales/anonymous.html>, it is also included in this release for your convenience.

To beautify your installation: place the icons and font(s) somewhere web-accessible, open `dir-listing.php` in your favorite editor and update `$imgdir` &amp; `$fontdir` accordingly.

A refresh of your browser should display the changes.

### Hidden Files

By default, dlist will not display hidden files (some call them _dot-files_) within each directory; this is intentional and recommended.

You can override the default and change `$showhidden` to `true` and have dlist make your hidden files available for all the world to see. **This is not recommended.** If you follow the recommendation or not, dlist will not show it's [helper files](#helper-file), you can also add your own files to excluded by use of our [helper file](#helper-file) `$ignore` variable.

### Markdown Handling

We can process markdown-enabled files by placing `handlers/markdown.php` somewhere accessible on your server, updating the `$handlerdir` variable within `dir-listing.php` and adding a rewrite rule within your `.htaccess` file:

	RewriteRule (.+)\.md$  dir-listing.php?action=markdown&file=$1

This is a simplified example, check out <https://github.com/amgraham/dlist/blob/master/handlers/README.md> file for more details & examples.

Helper File
----

Each directory can have a helper file with specific information for that directory: `.dir-list`. It can have two variables: `$ignore` &amp; `$details`.

The first will take whatever is contained in the the array and remove it from being displayed within the rendered page:

	$ignore = array("markdown.php", "secret-file.html");

The second will allow you to include some introductory text at the top of the page:

	$details = "<p>A small collection of <em>hopefully</em> helpful documents.<p>";

Future
----

1. 	Count of items in a folder (not sure about this one; [feedback welcome](https://github.com/amgraham/dlist/issues/new))
2. 	Modify the helper file to be able to perform simple regex/wildcard matching.
3. 	More handlers for more types of files. Source code? Images? Sound? [Requests welcome](https://github.com/amgraham/dlist/issues/new).
4. 	Easy drop-in stylesheets/themes.
5. 	Add `$pretty` &amp; `$showpretty` to `.dir-list`.
6. 	Add a way to "lock down" a directory easily; hide all files, simplify the interface and generate a simple "Not Welcome" message.
7. 	Offer up a limited RSS feed for a directory.
8. 	A way to "go back" to the default directory listing if a user prefers it.
9. 	Showing an alternate directory of files instead of the currently visited folder.