<?php
// upload this file (along with the icons and font) somewhere accessible on your web-host
// link other directories to that file for simplicity:
	// ln -s ../assets/php/dir-listing.php ./index.php
// enjoy!

// the following two are only required if you set $pretty to true
// the next two tell dir-listing.php how to look and what to present
// and the last is where we can find special handling files

// looks better with iconic pack!
// http://somerandomdude.com/work/iconic/
// place them in the following directory
$imgdir = "http://borma.lan.smarterfish.com/dev/dlist/img/";


// looks better with Universalis!
// http://arkandis.tuxfamily.org/adffonts.html
// make it a web-font and place it in the following directory (or use the included version)
$fontdir = "http://borma.lan.smarterfish.com/dev/dlist/font/";
// <code> blocks in HTML are setup for another font, included within the package, and available through http://google.com/webfonts: AnonymousPro

// set this to true if you have setup the four icons and web-font
// make sure you place the respective files in an accessible location, and updated $imgdir & $fontdir above.
$pretty = true; // note: you can also set is per directory in .dir-list

// would you like to show hidden files? (recommendation: false)
// also keep in mind that no matter what you put here, all "helper files", and "structure" files (".", "..") will not be displayed
$showhidden = false; // note: you can also set is per directory in .dir-list

// remember, only required if you want to handle markdown files specially

// processess markdown with markdown (oddly enough)!
// http://daringfireball.net/projects/markdown
// place them in the following directory
$handlerdir = "/var/www/html/dev/dlist/handlers/";
// there is no way to turn handlers "on"; you just send requests for markdown files to this file through the use of an .htaccess

// do you want to use your own stylesheet instead of the provided one?
// leaving this field blank will default to using the default one.
$stylesheet = ""; // note: you can also set is per directory in .dir-list




/* STOP EDITING */ // of course, you're more than welcome to poke around, but any alterations below may lead to issues/problems

$markdown = false; if (@$_GET["action"] == "markdown") { $markdown = true; $title = $_GET["file"].".md"; }

// get the ignore file, make it an array now, so if it doesn't exist, we can safely ignore it.
$ignore = array(); // empty array for errorless merging later
if (file_exists(".dir-list")) { include(".dir-list"); }

// a human readable filesize
function format_bytes($size) {
	// via: http://www.php.net/manual/en/function.filesize.php#100097
    $units = array(' B', ' KB', ' MB', ' GB', ' TB');
    for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
    return number_format(round($size, 2)).$units[$i];
}

// various sorting functions
function sort_date ($a, $b) { return strnatcmp($a["last-modified-raw"], $b["last-modified-raw"]); }
function sort_size ($a, $b) { return strnatcmp($a["file-size-raw"], $b["file-size-raw"]); }
function sort_type ($a, $b) { return strnatcmp($a["file-type"], $b["file-type"]); }
function sort_name ($a, $b) { return strnatcmp($a["filename"], $b["filename"]); }

// these are the holders to set reverse order for sorting
$rlm = ""; $rln = ""; $rlt = ""; $rls = "";

// build the links for navigation
$request = $_SERVER["REQUEST_URI"];
$splitRequest = explode("/", $request);
$urlLinks = "<a href=\"http://".$_SERVER["HTTP_HOST"]."\">".$_SERVER["HTTP_HOST"]."</a><span class=\"spc\"/>/</span>";
// the first and last are empty
array_shift($splitRequest); array_pop($splitRequest);
// the beginning of our links                 // and the rest
$links = "http://".$_SERVER["HTTP_HOST"]."/"; foreach ($splitRequest as $splitItem) { $links .= $splitItem."/"; $urlLinks.= "<a href=\"".$links."\">".$splitItem."</a><span class=\"spc\"/>/</span>"; }

// figure out the sorting
$sort = $_SERVER["QUERY_STRING"]; if (substr($sort, 0, 1) == "!") { $reverse = true; $sort = str_replace("!", "", $sort); } else { $reverse = false; }

// via: http://stackoverflow.com/a/5478353
// merge with the files from '.dir-list'
$ignore = array_merge(array('.', '..', 'index.php', '.dir-list', 'dir-listing.php'), $ignore);

// sortable by these only, nothing else
$sortable = array('name', 'modified', 'size', 'type', '');

// make sure its one we want to sort by, ignore others
if (!in_array($sort, $sortable)) { unset($sort); }

// go over the files create our two arrays for later, one for folders and another for files, and open a file info handle
$dh = opendir("."); $files = array(); $directories = array(); $finfo = finfo_open(FILEINFO_MIME_TYPE);

// begin processing the current directory

while (false !== ($file = readdir($dh))) {

	// do we want to show hidden files?
	if ($showhidden) { 	$hiddenFile = true;
	} else { 			$hiddenFile = substr($file, 0, 1) != ".";
	}

	// make sure the filename isn't a dot-file, this file, or the details file.
	if (!in_array($file, $ignore) and $hiddenFile) { $filetype = finfo_file($finfo, $file);

		// directories are different, and treated as such from folders (below)
		if ($filetype == "directory") {
			// we know what icon to use, these are all folders
			$directories[] = array(
				"filename" => 			$file,
				"last-modified" => 		date("D j M Y h:i A T", filemtime($file)),
				"last-modified-raw" => 	date("c", filemtime($file)),
        "last-modified-small" => 	date("d/j/y h:i a", filemtime($file)),
				"file-type" => 			finfo_file($finfo, $file),
				"file-size-raw" => 		0,
				"icon" => 				$imgdir."folder_stroke_16x16.png");

		// these are files
		} else {
			// what icon should we use?
			// keep in mind if $pretty if false, none of this matters
			if ($pretty) {

				if (strstr($filetype, "image")) { 		$icon = $imgdir."image_16x16.png";
				} elseif (strstr($filetype, "text")) { 	$icon = $imgdir."document_stroke_16x16.png";
				} else { 								$icon = $imgdir."cog_16x16.png";}

			} else { $icon = false;}

			$files[] = array(
				"filename" => 			$file,
				"last-modified" => 		date("D j M Y h:i A T", filemtime($file)),
				"last-modified-raw" => 	date("c", filemtime($file)),
        "last-modified-small" => 	date("d/m/y h:i a", filemtime($file)),
				"file-size" => 			format_bytes(filesize($file)),
				"file-size-raw" => 		filesize($file),
				"file-type" => 			finfo_file($finfo, $file),
				"icon" => 				$icon);
		}
	}
}

// close the fileinfo link & directory
finfo_close($finfo); closedir($dh);

// begin sorting the files and directories
if (@$sort == "modified") { 		usort($files, 'sort_date'); usort($directories, 'sort_date'); $rlm = "!";
} else if (@$sort == "size") { 		usort($files, 'sort_size'); usort($directories, 'sort_size'); $rls = "!";
} else if (@$sort == "type") { 		usort($files, 'sort_type'); usort($directories, 'sort_type'); $rlt = "!";
} else { 							usort($files, 'sort_name'); usort($directories, 'sort_name'); $rln = "!";
}

// taking care of reverse
if ($reverse) {
	// reverse the files
	$files = array_reverse($files);
	// type and size doesn't change for directories (size, for now)
	if (($sort != "size") and ($sort != "type")) { $directories = array_reverse($directories); }
	// we're reversed, we want normal sorting first
	$rlm = ""; $rln = ""; $rlt = ""; $rls = "";
}

?>
<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<?php if ($stylesheet == "") { ?>
	<style type="text/css" media="screen, projection">
		<?php if ($pretty) { ?>@font-face { font-family: 'Universalis'; src: url('<?php echo $fontdir; ?>universalis.eot') format('eot'), url('<?php echo $fontdir; ?>universalis.woff') format('woff'), url('<?php echo $fontdir; ?>universalis.ttf') format('truetype'), url('<?php echo $fontdir; ?>universalis.svg') format('svg');  font-weight: normal; font-style: normal; }/* http://arkandis.tuxfamily.org/adffonts.html */
		@font-face {font-family: 'AnonymousRegular';src: url('<?php echo $fontdir; ?>/Anonymous-webfont.eot') format('eot'), url('<?php echo $fontdir; ?>/Anonymous-webfont.woff') format('woff'), url('<?php echo $fontdir; ?>/Anonymous-webfont.ttf') format('truetype'), url('<?php echo $fontdir; ?>/Anonymous-webfont.svg') format('svg');	font-weight: normal; font-style: normal;} /* http://www.ms-studio.com/FontSales/anonymous.html */
		<?php } ?>html,body,div,span,h1,p,a,em,font,img,table,caption,tbody,tfoot,thead,tr,th,td{margin:0;padding:0;border:0;outline:0;font-size:100%;vertical-align:baseline;background:transparent}body{line-height:1}table{border-collapse:collapse;border-spacing:0}
		html { }
		<?php if ($pretty) { ?>body { font-size: 1.2em; color: #333; line-height: 1.4em;font-family: "Universalis", "Trebuchet MS", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Tahoma, sans-serif; width:50em; margin-left: 2em; text-align: left; margin-top: 1em; margin-bottom: 4em; padding-bottom: 2em;}
		<?php } else { ?>body { font-size: 1em; color: #333; line-height: 1.2em;font-family: sans-serif; width:50em; margin-left: 2em; text-align: left; margin-top: 1em; margin-bottom: 4em; padding-bottom: 2em;}
		<?php } ?>p { line-height:1.3em; }
		p,hr,h1,table{margin-top:1em;}
		h3, h2 { margin-bottom: 0; }
		h2 a[name], h3 a[name] { border: 0px; color: #333!important; cursor: default; }
		h2 + p, h3 + p { margin-top: 0em; }
		a, a:visited, *[onclick]{ color: #333; text-decoration: none; border-bottom: 1px solid #ccc; cursor: pointer;}
		a:hover, a:visited:hover, *[onclick]:hover  { border-bottom: 1px solid #83b0fe; color: #2e52a4;}
		header { margin-bottom: 1em; }
			header h1 {font-size:1.7em; margin: 0; padding: 0; display: inline-block;}
		section { padding: 0; margin: 0; }
		article { margin-top: .5em; }
			article#details { margin-bottom: 1em; }
		.help { cursor: help; border-bottom: 1px dashed #ddd; }
		code { font-family: "AnonymousRegular", "Lucida Console", "Lucida Sans Typewriter", Monaco, "Bitstream Vera Sans Mono", monospace; font-size: 80%;}
		pre {  padding: 1em 0em 1em 2em; background: #efefef; -moz-border-radius: 3px; border-radius: 3px; }
		table.dir-list { width: 100%; }
			table.dir-list th { color: #ddd; }
			table.dir-list td { margin: 2px 5px; padding-bottom: .25em; }
			table.dir-list tr.folder td.filename { font-weight: bold; }
			table.dir-list a { border: 0px; }
			table.dir-list img { vertical-align:-1px; }
			tr.folder + tr.file td { padding-top: .5em; }
			th a { font-weight: normal; color: #ddd!important; }
		ul.dir-list { width: 50em; margin: 0; padding: 0; }
			ul.dir-list li { display: inline; }
			ul.dir-list a { border: 0; margin: .2em;}
		.spc { margin: 0 .2em 0 .2em; }
    .last-modified-small { display: none; }
    @media (max-width: 1000px) { body { width: 95%; font-size: 90%; } pre { overflow: auto;} .last-modified-small { display: inline; } .last-modified, td.small-hide, th.small-hide { display: none; } }
    @media (max-width: 800px) { body { font-size: 80%; } .small-hide { font-size: 90%; } }
    @media (max-width: 650px) { body { font-size: 70%; } .small-hide { font-size: 90%; } }
    @media (max-width: 450px) { body { font-size: 55%; } .small-hide { font-size: 80%; } }
    @media (max-width: 320px) { body { font-size: 50%; } .small-hide { font-size: 80%; } }
	</style>
	<? } else { ?>
		<link rel="stylesheet" type="text/css" href="<?php echo $stylesheet; ?>">
	<?php } ?>
	<?php if ($markdown) { ?>
	<title><?php echo $title; ?></title>
	<?php } else { ?>
	<title>Directory listing: <?php echo $request; ?></title>
	<?php } ?>
</head>
<body>
	<?php if (@$status) { ?>
	<?php echo $status["message"];
	header($status["header"]); ?>

	<?php } else { ?>

	<?php if (!$markdown) { ?><header>
		<h1><?php echo $urlLinks; ?></h1>
	</header><?php } ?>

	<section>
		<?php if ($markdown) { ?><article id="document">
		<?php include($handlerdir."markdown.php"); echo Markdown(file_get_contents($_GET["file"].".md")); ?>
		<article>
		<?php } else { ?><?php if (@$details) { ?><article id="details"><?php echo $details; ?></article><?php } ?>

		<article>
			<?php if (@$gallery == true) { usort($files, 'sort_name'); ?>
			<?php if (count($directories) > 0) { ?>
			<table class="dir-list">
			<tr>
				<th><a href="?<?php echo $rln; ?>name">filename</a></th>
				<th><a href="?<?php echo $rlm; ?>modified">last modified</a></th>
				<th><a href="?<?php echo $rls; ?>size" class="help" title="approximate">size</a></th>
				<th class="small-hide"><a href="?<?php echo $rlt; ?>type">filetype</a></th>
			</tr>
			<?php
				foreach ($directories as $file) {
					echo "<tr class=\"folder\">\n\t\t\t\t\t";
					echo "<td class=\"filename\">";
					if ($pretty) { echo "<img src=\"".$file["icon"]."\"/> "; }
					echo "<a href=\"".$file["filename"]."\">".$file["filename"]."</a></td>\n\t\t\t\t\t";
					echo "<td class=\"small-hide\"><span class=\"last-modified\">".$file["last-modified"]."</span><span class=\"last-modified-small\">".$file["last-modified-small"]."</span></td>\n\t\t\t\t\t";
					echo "<td>&mdash;</td>\n\t\t\t\t\t";
					echo "<td class=\"small-hide\">".$file["file-type"]."</td>\n\t\t\t\t";
					echo "</tr>\n\t\t\t\t";
				} ?>
			</table>
			<?php } ?>
			<ul class="dir-list">
				<?php
				foreach ($files as $file) {
					echo "<li>";
					echo "<a href=\"".$file["filename"]."\"><img src=\".".$file["filename"]."\" /></a>";
					echo "</li>";
				} ?>
			</ul>

			<?php } else { ?>
			<table class="dir-list">
				<tr>
					<th><a href="?<?php echo $rln; ?>name">filename</a></th>
					<th><a href="?<?php echo $rlm; ?>modified">last modified</a></th>
					<th><a href="?<?php echo $rls; ?>size" class="help" title="approximate">size</a></th>
					<th class="small-hide"><a href="?<?php echo $rlt; ?>type">filetype</a></th>
				</tr>
				<?php
				if (count($directories) > 0) {
					foreach ($directories as $file) {
						echo "<tr class=\"folder\">\n\t\t\t\t\t";
						echo "<td class=\"filename\">";
						if ($pretty) { echo "<img src=\"".$file["icon"]."\"/> "; }
						echo "<a href=\"".$file["filename"]."\">".$file["filename"]."</a></td>\n\t\t\t\t\t";
            echo "<td><span class=\"last-modified\">".$file["last-modified"]."</span><span class=\"last-modified-small\">".$file["last-modified-small"]."</span></td>\n\t\t\t\t\t";
						echo "<td>&mdash;</td>\n\t\t\t\t\t";
						echo "<td class=\"small-hide\">".$file["file-type"]."</td>\n\t\t\t\t";
						echo "</tr>\n\t\t\t\t";
					}
				}
				foreach ($files as $file) {
					echo "<tr class=\"file\">\n\t\t\t\t\t";
					echo "<td class=\"filename\">";
					if ($pretty) { echo "<img src=\"".$file["icon"]."\"/> "; }
					echo "<a href=\"".$file["filename"]."\">".$file["filename"]."</a></td>\n\t\t\t\t\t";
          echo "<td><span class=\"last-modified\">".$file["last-modified"]."</span><span class=\"last-modified-small\">".$file["last-modified-small"]."</span></td>\n\t\t\t\t\t";
					echo "<td>".$file["file-size"]."</td>\n\t\t\t\t\t";
					echo "<td class=\"small-hide\">".$file["file-type"]."</td>\n\t\t\t\t";
					echo "</tr>\n\t\t\t\t";
				}
				echo "\n";
				?>
			</table>
			<?php } ?>
		</article>
	<?php } ?></section>
	<?php } ?>

</body>
</html>
