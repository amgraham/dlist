Handler Files
====

[dlist](https://github.com/amgraham/dlist) stays out of your way when it comes to individual file access, this is by design. You can add some _very simple_ processing to it through the addition of a handler for [markdown](http://daringfireball.net/projects/markdown/) files (`*.md`). 

To enable this feature you must place `markdown.php` file somewhere on your server, and create (or edit) an `.htaccess` file. Next you have to tell `dir-listing.php` where to find markdown.php.

	# dir-listing.php
	$handlerdir = "/var/www/markdown.php";

Turning On
---

The top-most `.htaccess` file you wish to begin handling markdown files for must `rewrite` requests for `.md` files to a copy of `dlist`:

	RewriteEngine On
	RewriteRule (.+)\.md$  dir-listing.php?action=markdown&file=$1

You can keep the file anywhere you wish:

	RewriteEngine On
	RewriteRule (.+)\.md$  /assets/dlist/dir-listing.php?action=markdown&file=$1

If you maintain an installation closer to the second one, you will need to make one minor edit to `dir-listing.php`. Keep in mind the following examples are real code, but they have been formatted (linebreaks have been added):

	# dir-listing.php - original
	<?php if ($markdown) { ?><article>
	<?php include($handlerdir."markdown-extra.php"); 
	echo Markdown(file_get_contents($_GET["file"].".md")); ?>
	<article>

Must be changed to:

	# dir-listing.php - updated for depth
	<?php if ($markdown) { ?><article>
	<?php include($handlerdir."markdown-extra.php"); 
	echo Markdown(file_get_contents("../../".$_GET["file"].".md")); ?>
	<article>

Note the addition of `../../` within `file_get_contents()` on the second to last line. For however many folders you declare the depth in `.htaccess` you must increase the depth (`../`) in dir-listing.php