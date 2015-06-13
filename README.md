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

<a name="enhancement">Enhancement</a>
----

The following options can be set universally in `dir-listing.php` and then over&mdash;ridden (if you need) for specific directories through the use of our [helper file](#helper-file).

### <a name="pretty">General Prettiness</a>

It will look prettier if you also utilize four icons and web-font. They are "turned on" by default, but you can turn them off easily by changing `$pretty` to `false`.

The four icons are from <http://somerandomdude.com/work/iconic/>, while the font is Universalis from <http://arkandis.tuxfamily.org/adffonts.html> both are included in this release for your convenience. If you happen to use dlist for processing markdown files there is an additional font in use: Anonymous from <http://www.ms-studio.com/FontSales/anonymous.html>, it is also included in this release for your convenience.

To beautify your installation: place the icons and font(s) somewhere web-accessible, open `dir-listing.php` in your favorite editor and update `$imgdir` &amp; `$fontdir` accordingly.

A refresh of your browser should display the changes.

### <a name="showhidden">Hidden Files</a>

By default, dlist will not display hidden files (some call them _dot-files_) within each directory; this is intentional and recommended.

You can override the default and change `$showhidden` to `true` and have dlist make your hidden files available for all the world to see. **This is not recommended.** If you follow the recommendation or not, dlist will not show it's [helper files](#helper-file) or <span class="help" title="Specifically: index.php and dir-listing.php">itself</span>.

You can also add your own files to be excluded by use of our [helper file](#helper-file) `$ignore` variable.

### <a name="stylesheet">Custom Stylesheets</a>

dlist utilizes it&apos;s not&mdash;too&mdash;shabby stylesheet to present your files with a non&mdash;award winning interface, you can alter how it looks through the use of a custom stylesheet that can be set by changing the `$stylesheet` declaration to:

	$stylesheet = "http://example.com/assets/style.css";

<a name="helper-file">Helper File</a>
----

Each directory can have a helper file with specific information for that directory: `.dir-list`. It has three variables: `$ignore`, `$details`, &amp; `$status`.

The first will take whatever is contained in the the array and remove it from being displayed within the rendered page:

	$ignore = array(
		"markdown.php",
		"secret-file.html"
	);

The second will allow you to include some introductory text at the top of the page:

	$details = "<p>A small collection of <em>hopefully</em> helpful documents.<p>";

The last can help you lockdown a directory as needed and not list any files or resources, just presenting an _optional_ message and response header:

	$status = array(
		"message" => "<p>You are not welcome, please leave</p>",
		"header" => "HTTP/1.1 403 Forbidden"
	);

**Please note:** locking down a directory through dlist **is not secure**; resources can still be accessed directly (if someone already knows the URL&hellip;), if you need serious protection you must use another solution.

### Other Variables

Other variables set universally in `dir-listing.php` can be customized _per directory_ with the use of `.dir-list`: [`$pretty`](#pretty), [`$showhidden`](#showhidden), and [`$stylesheet`](#showhidden).

Markdown Handling
----

We can process markdown-enabled files by placing `handlers/markdown.php` somewhere accessible on your server, updating the `$handlerdir` variable within `dir-listing.php` and adding a rewrite rule within your `.htaccess` file:

	RewriteRule (.+)\.md$  dir-listing.php?action=markdown&file=$1

This is a simplified example, check out [README.md](https://github.com/amgraham/dlist/blob/master/handlers/README.md) file for more details & examples.

Future
----

1. 	Count of items in a folder (not sure about this one; [feedback welcome](https://github.com/amgraham/dlist/issues/new))
2. 	Modify the helper file to be able to perform simple regex/wildcard matching.
3. 	More handlers for more types of files. Source code? Images? Sound? [Requests welcome](https://github.com/amgraham/dlist/issues/new).
4. 	Offer up a limited RSS feed for a directory.
5. 	A way to "go back" to the default directory listing if a user prefers it.
6. 	Showing an alternate directory of files instead of the currently visited folder.
