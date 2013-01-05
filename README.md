dlist - simple directory listing
====

An empty folder presented by [Apache](http://apache.org) (and others) is very ugly. [dlist](https://github.com/amgraham/dlist) strives to replace the default in a less-ugly, and slightly-more-useful manner. I hope you find it useful.

Features
----

dlist's goal is to match the feature set of Apache's default directory list, with a few enhancements (mostly style), in doing so, you can sort the files by the various columns. Clicking them again will reverse the order.

There is a [demo available](http://smarterfish.com/assets/) that showcases the various features and design of dlist. Another [demo is available](http://craft.smarterfish.com/map/) that showcases some of the [helper file](#helper-files) features.

There is a [soft feature](#handler-files) (one might call it a dangling-of-toes-into-a-cold-pool-implementation) of handling [markdown](http://daringfireball.net/projects/markdown/) files (`*.md`) [which could creep](https://github.com/amgraham/dlist/issues/new) into handling other types of files.

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

Enhancement 	{#enhancement}
----

### General Prettiness

It will look prettier if you also utilize four icons and a web-font, they are "turned on" by default so you can get up and running immediately.

The four icons are from <http://somerandomdude.com/work/iconic/>, while the font is Universalis from <http://arkandis.tuxfamily.org/adffonts.html> both are included in this release for your convenience.

You should beautify your installation and upload the four icons and web font somewhere easy (read; universally accessible), open `dir-listing.php` in your favorite editor, and update `$imgdir` &amp; `$fontdir` accordingly (wherever you decided to keep the icons and webfont).

A refresh of your browser should display the changes.

### Hidden Files

By default, dlist will not display hidden files (some call them "dot-files") within the current directory; this is intentional and recommended.

You can override the default and change `$showhidden` to `true` and have dlist make your hidden files available for all the world to see. **This is not recommended.** If you follow the recommendation or not, dlist will not show it's [helper files](#helper-files), you can also add your own files to excluded by use of our [helper file](#helper-files) `$ignore` variable.


Helper Files 	{#helper-files}
----

There is one helper file in use: `.dir-list`, it currently has two options: `$ignore` &amp; `$details`.

The first will take whatever is contained in the the array and remove it from being displayed within the rendered page:

	$ignore = array("markdown.php", "secret-file.html");

The second will allow you to include some introductory text at the top of the page:

	$details = "A small collection of <em>hopefully</em> helpful documents.";

Handler Files 	{#handler-files}
----

[dlist](https://github.com/amgraham/dlist) stays out of your way when it comes to individual file access, this is by design. You can add some _very simple_ processing to it through the addition of a handler for [markdown](http://daringfireball.net/projects/markdown/) files (`*.md`).

To enable handling of markdown files, you must keep your `dir-listing.php` file (and related files) off a single directory, not having individual copies spread throughout your web server.

Most people do this anyway; you only have to consolidate things if you don't soft link copies of `dir-listing.php` off a centrally located file:

	ln -s ../assets/php/dir-listing.php ./index.php

Create a directory off that main location called `handlers` and place the three files from this distribution (conveniently located within the `handlers` folder) within that directory:

	.htaccess
	dir-listing.php
	handlers/
	- markdown.php
	- markdown-extra.php
	- markdown-syntax.md

Update your top-most `.htaccess` file with the following data (typically the one from the example above): 

	RewriteEngine On
	RewriteRule (.+)\.md$  dir-listing.php?action=markdown&file=$1

Note that if you keep you files in a sub-directory off your root, it would look like this:

	.htaccess
	index.php
	file.md
	pictures.jpg
	other_documents/
	- other-files
	- lots-of-them
	dir-list/
	- dir-listing.php
	- handlers/
	  - markdown.php
	  - markdown-extra.php
	  - markdown-syntax.md

Keep in mind, in this example `index.php` **is not** a copy of `dir-listing.php`.

Your `.htaccess` would look like this:

	RewriteEngine On
	RewriteRule (.+)\.md$  /dir-list/dir-listing.php?action=markdown&file=$1

You will also need to make one minor change to dir-listing.php, but only if it is located in an offshoot (like the example above). You need to tell PHP to traverse up into the same hierarchy as any markdown files that need processing:

	# dir-listing.php - original
	<?php if ($markdown) { ?><article>
	<?php include($handlerdir."markdown-extra.php"); 
	echo Markdown(file_get_contents($_GET["file"].".md")); ?>
	<article>

Must be changed to:

	# dir-listing.php - updated for depth
	<?php if ($markdown) { ?><article>
	<?php include($handlerdir."markdown-extra.php"); 
	echo Markdown(file_get_contents("../".$_GET["file"].".md")); ?>
	<article>

Note the addition of `../` within `file_get_contents` on the second to last line. Keep in mind if `dir-listing.php` is in an even deeper directory from your actual content, you should double the depth: `../../` to compensate, and any additional depth will require another `../`.

Future
----

1. Count of items in a folder (not sure about this one; [feedback welcome](https://github.com/amgraham/dlist/issues/new))
2. Modify the helper file to be able to perform simple regex/wildcard matching.
3. More handlers for more types of files. Source code? Images? Sound (I doubt it). [Requests welcome](https://github.com/amgraham/dlist/issues/new).
4. Easy drop-in stylesheets/themes.