<?php 
// upload this file (along with the icons and font) somewhere accessible on your web-host
// link other directories to that file for simplicity:
	// ln -s ../assets/php/dir-listing.php ./index.php
// enjoy!

// the following two are only required if you set $pretty to true
// the third only if you want to handle markdown text specially

// looks better with iconic pack!
// http://somerandomdude.com/work/iconic/
// place them in the following directory
$imgdir = "http://aramaki/assets/images/iconic/raster/black/";

// looks better with Universalis!
// http://arkandis.tuxfamily.org/adffonts.html
// make it a web-font and place it in the following directory (or use the included version)
$fontdir = "http://aramaki/assets/font/";
// <code> blocks in HTML are setup for another font, included within the package, and available through http://google.com/webfonts: AnonymousPro


// remember, only required if you want to handle markdown files specially

// processess markdown with markdown (oddly enough)!
// http://daringfireball.net/projects/markdown
// place them in the following directory 
$handlerdir = "/var/www/dev/dlist/handlers/";

// set this to true if you have setup the four icons and web-font
// make sure you place the respective files in an accessible location
$pretty = true;

// there is no setting for turning handlers 'on'
// if you want to process markdown files specially then add the following lines to your top-most .htaccess file:
/*

	RewriteEngine On
	RewriteRule (.+)\.md$  dir-listing.php?action=markdown&file=$1

*/
// just remember to change dir-listing.php (from the snippet above) to match the top-most copy of this file.


// would you like to show hidden files? (recommendation: false)
// also keep in mind that no matter what you put here, all "helper files", and "structure" files (".", "..") will not be displayed
$showhidden = false;

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
$splitRequest = split("/", $request);
$urlLinks = "<a href=\"http://".$_SERVER["HTTP_HOST"]."\">".$_SERVER["HTTP_HOST"]."</a><span class=\"spc\"/>/</span>";
// the first and last are empty
array_shift($splitRequest); array_pop($splitRequest);
// the beginning of our links                 // and the rest
$links = "http://".$_SERVER["HTTP_HOST"]."/"; foreach ($splitRequest as $splitItem) { $links .= $splitItem."/"; $urlLinks.= "<a href=\"".$links."\">".$splitItem."</a><span class=\"spc\"/>/</span>"; }

// figure out the sorting
$sort = $_SERVER["QUERY_STRING"]; if (substr($sort, 0, 1) == "!") { $reverse = true; $sort = str_replace("!", "", $sort); } else { $reverse = false; }

// via: http://stackoverflow.com/a/5478353
// merge with the files from '.dir-list'
$ignore = array_merge(array('.', '..', 'index.php', '.dir-list'), $ignore);

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
	<style type="text/css">
		<?php if ($pretty) { ?>@font-face { font-family: 'UniversalisADFStdRegular'; src: url('<?php echo $fontdir; ?>universalisadfstd-regular-webfont.eot') format('eot'), url('<?php echo $fontdir; ?>universalisadfstd-regular-webfont.woff') format('woff'), url('<?php echo $fontdir; ?>universalisadfstd-regular-webfont.ttf') format('truetype'), url('<?php echo $fontdir; ?>universalisadfstd-regular-webfont.svg') format('svg');  font-weight: normal; font-style: normal; }/* http://arkandis.tuxfamily.org/adffonts.html */
		@font-face {font-family: 'AnonymousRegular';src: url('<?php echo $fontdir; ?>/Anonymous-webfont.eot') format('eot'), url('<?php echo $fontdir; ?>/Anonymous-webfont.woff') format('woff'), url('<?php echo $fontdir; ?>/Anonymous-webfont.ttf') format('truetype'), url('<?php echo $fontdir; ?>/Anonymous-webfont.svg') format('svg');	font-weight: normal; font-style: normal;} /* http://www.ms-studio.com/FontSales/anonymous.html */
		<?php } ?>html,body,div,span,h1,p,a,em,font,img,table,caption,tbody,tfoot,thead,tr,th,td{margin:0;padding:0;border:0;outline:0;font-size:100%;vertical-align:baseline;background:transparent}body{line-height:1}table{border-collapse:collapse;border-spacing:0}
		html { }
		<?php if ($pretty) { ?>body { font-size: 1.2em; color: #333; line-height: 1.4em;font-family: "UniversalisADFStdRegular"; width:50em; margin-left: 2em; text-align: left; margin-top: 1em; margin-bottom: 4em; padding-bottom: 2em;}
		<?php } else { ?>body { font-size: 1em; color: #333; line-height: 1.2em;font-family: sans-serif; width:50em; margin-left: 2em; text-align: left; margin-top: 1em; margin-bottom: 4em; padding-bottom: 2em;}
		<?php } ?>p { line-height:1.3em; }
		p,hr,h1,table{margin-top:1em;}
		h3, h2 { margin-bottom: 0; }
		h2 + p, h3 + p { margin-top: 0em; }
		a, a:visited, *[onclick]{ color: #333; text-decoration: none; border-bottom: 1px solid #ccc; cursor: pointer;}
		a:hover, a:visited:hover, *[onclick]:hover  { border-bottom: 1px solid #83b0fe; color: #2e52a4;}
		header { margin-bottom: 1em; } 
			header h1 {font-size:1.7em; margin: 0; padding: 0; display: inline-block;}
		section { padding: 0; margin: 0; }
		article { margin-top: .5em; }
			article#details { margin-bottom: 1em; }
		.help { cursor: help; border-bottom: 1px dashed #ddd; }
		code { font-family: "AnonymousRegular"; font-size: 80%; }
		pre {  padding: 1em 0em 1em 2em; background: #efefef; -moz-border-radius: 3px; border-radius: 3px;}
		table.dir-list { width: 100%; }
			table.dir-list th { color: #ddd; }
			table.dir-list td { margin: 2px 5px; padding-bottom: .25em; }
			table.dir-list tr.folder td.filename { font-weight: bold; }
			table.dir-list a { border: 0px; }
			table.dir-list img { vertical-align:-1px; }
			tr.folder + tr.file td { padding-top: .5em; }
			th a { font-weight: normal; color: #ddd!important; }
		.spc { margin: 0 .2em 0 .2em; }
	</style>
	<?php if ($markdown) { ?>
	<title><?php echo $title; ?></title> 
	<?php } else { ?>
	<title>Directory listing: <?php echo $request; ?></title> 
	<?php } ?>
</head> 
<body> 
	<?php if (!$markdown) { ?><header>
		<h1><?php echo $urlLinks; ?></h1>
	</header><?php } ?>
	
	<section>
		<?php if ($markdown) { ?><article>
		<?php include($handlerdir."markdown.php"); echo Markdown(file_get_contents($_GET["file"].".md")); ?>
		<article>
		<?php } else { ?><?php if (@$details) { ?><article id="details"><?php echo $details; ?></article><?php } ?>

		<article>
			<table class="dir-list">
				<tr>
					<th><a href="?<?php echo $rln; ?>name">filename</a></th>
					<th><a href="?<?php echo $rlm; ?>modified">last modified</a></th>
					<th><a href="?<?php echo $rls; ?>size" class="help" title="approximate">size</a></th>
					<th><a href="?<?php echo $rlt; ?>type">filetype</a></th>
				</tr>
				<?php 
				if (count($directories) > 0) {
					foreach ($directories as $file) {
						echo "<tr class=\"folder\">\n\t\t\t\t\t";
						echo "<td class=\"filename\">";
						if ($pretty) { echo "<img src=\"".$file["icon"]."\"/> "; }
						echo "<a href=\"".$file["filename"]."\">".$file["filename"]."</a></td>\n\t\t\t\t\t";
						echo "<td>".$file["last-modified"]."</td>\n\t\t\t\t\t";
						echo "<td>&mdash;</td>\n\t\t\t\t\t";
						echo "<td>".$file["file-type"]."</td>\n\t\t\t\t";
						echo "</tr>\n\t\t\t\t";
					}
				}
				foreach ($files as $file) {
					echo "<tr class=\"file\">\n\t\t\t\t\t";
					echo "<td class=\"filename\">";
					if ($pretty) { echo "<img src=\"".$file["icon"]."\"/> "; }
					echo "<a href=\"".$file["filename"]."\">".$file["filename"]."</a></td>\n\t\t\t\t\t";
					echo "<td>".$file["last-modified"]."</td>\n\t\t\t\t\t";
					echo "<td>".$file["file-size"]."</td>\n\t\t\t\t\t";
					echo "<td>".$file["file-type"]."</td>\n\t\t\t\t";
					echo "</tr>\n\t\t\t\t";
				}
				echo "\n";
				?>
			</table>
		</article>
	<?php } ?></section>
		
</body> 
</html>