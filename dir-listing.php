<?php 
// upload this file (and the four icons) somewhere accessible on your web-host
// link other directories to that file:
	// ln -s ../assets/php/dir-listing.php ./index.php
// enjoy!

// looks better with iconic pack!
// http://somerandomdude.com/work/iconic/
// place them in the following directory
// your image directory
$imgdir = "http://smarterfish.com/assets/img/iconic/raster/black/";

// looks better with Universalis!
// http://arkandis.tuxfamily.org/adffonts.html
// place them in the following directory
// your font directory
$fontdir = "http://smarterfish.com/assets/fonts/";

function format_bytes($size) {
	// via: http://www.php.net/manual/en/function.filesize.php#100097
    $units = array(' B', ' KB', ' MB', ' GB', ' TB');
    for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
    return number_format(round($size, 2)).$units[$i];
}

// the rest is hand-built

// build the links for navigation
$request = $_SERVER["REQUEST_URI"];
$splitRequest = split("/", $request);
$urlLinks = "<a href=\"http://".$_SERVER["HTTP_HOST"]."\">".$_SERVER["HTTP_HOST"]."</a><span class=\"spc\"/>/</span>";
array_shift($splitRequest); array_pop($splitRequest);
$links = "http://".$_SERVER["HTTP_HOST"]."/";
foreach ($splitRequest as $splitItem) {
	$links .= $splitItem."/";
	$urlLinks.= "<a href=\"".$links."\">".$splitItem."</a><span class=\"spc\"/>/</span>";
}

// go over the files
$files = array();
$directories = array();
$dirpath = ".";
$dh = opendir($dirpath);
$finfo = finfo_open(FILEINFO_MIME_TYPE);

while (false !== ($file = readdir($dh))) {
	if (($file != "..") && ($file != ".") && ($file != "index.php")) {	
		$filetype = finfo_file($finfo, $file);
		if ($filetype == "directory") {
			$directories[$file] = array("filename" => $file, "last-modified" => date("D j M Y h:i A T", filemtime($file)), "file-type" => finfo_file($finfo, $file), "icon" => $imgdir."folder_stroke_16x16.png");
		} else {
			if (strstr($filetype, "image")) {
				$icon = $imgdir."image_16x16.png";
			} elseif (strstr($filetype, "text")) {
				$icon = $imgdir."document_stroke_16x16.png";
			} else {
				$icon = $imgdir."cog_16x16.png";
			}
			$files[$file] = array("filename" => $file, "last-modified" => date("D j M Y h:i A T", filemtime($file)), "file-size" => format_bytes(filesize($file)), "file-type" => finfo_file($finfo, $file), "icon" => $icon);
		}
	}
}
finfo_close($finfo);
closedir($dh);

asort($files); asort($directories);


?>
<!DOCTYPE html> 
<html lang="en-US"> 
<head> 
	<meta charset="UTF-8" /> 
	<style type="text/css">
		@font-face { font-family: 'UniversalisADFStdRegular'; src: url(<?php echo $fontdir; ?>'universalisadfstd-regular-webfont.eot') format('eot'), url(<?php echo $fontdir; ?>'universalisadfstd-regular-webfont.woff') format('woff'), url(<?php echo $fontdir; ?>'universalisadfstd-regular-webfont.ttf') format('truetype'), url(<?php echo $fontdir; ?>'universalisadfstd-regular-webfont.svg') format('svg');  font-weight: normal; font-style: normal; }/* http://arkandis.tuxfamily.org/adffonts.html */
		html,body,div,span,h1,p,a,em,font,img,table,caption,tbody,tfoot,thead,tr,th,td{margin:0;padding:0;border:0;outline:0;font-size:100%;vertical-align:baseline;background:transparent}body{line-height:1}ol,ul{list-style:none}table{border-collapse:collapse;border-spacing:0}
			html { }
			body { font-size: 1.2em; color: #333; line-height: 1.4em;font-family: "UniversalisADFStdRegular"; width:50em; margin-left: 2em; text-align: left; margin-top: 1em; margin-bottom: 4em; padding-bottom: 2em;}
			p { line-height:1.3em; }
			p,hr,h1,table{margin-bottom:1em;}
			a, a:visited, *[onclick]{ color: #333; text-decoration: none; border-bottom: 1px solid #ccc; cursor: pointer;}
			a:hover, a:visited:hover, *[onclick]:hover  { border-bottom: 1px solid #83b0fe; color: #2e52a4;}
			header { margin-bottom: 1em; } 
				header h1 {font-size:1.7em; margin: 0; padding: 0; display: inline-block;}
			section { padding: 0; margin: 0; }
			article { margin-top: .5em; }
			.help { cursor: help; border-bottom: 1px dashed #ddd; }
			ol.inline li { display: inline !important; }
			table.dir-list { width: 100%; }
				table.dir-list th { color: #ddd; }
				table.dir-list td { margin: 2px 5px; padding-bottom: .25em; }
				table.dir-list tr.folder td.filename { font-weight: bold; }
				table.dir-list a { border: 0px; }
				table.dir-list img { vertical-align:-1px; }
			.spc { margin: 0 .2em 0 .2em; }
	</style>
	<title>Directory listing: <?php echo $request; ?></title> 
</head> 
<body> 
	<header>
		<h1><?php echo $urlLinks; ?></h1>
	</header>
	
	<section>
		<article>
			<table class="dir-list">
				<tr>
					<th>filename</th>
					<th>last modified (<?php echo date("T"); ?>)</th>
					<th><span class="help" title="approximate">size</span></th>
					<th>filetype</th>
				</tr>
				<?php 
				foreach ($directories as $file) {
					echo "<tr class=\"folder\">";
					echo "<td class=\"filename\"><img src=\"".$file["icon"]."\"/> <a href=\"".$file["filename"]."\">".$file["filename"]."</a></td>";
					echo "<td>".$file["last-modified"]."</td>";
					echo "<td>&mdash;</td>";
					echo "<td>".$file["file-type"]."</td>";
					echo "</tr>";
				}
				foreach ($files as $file) {
					echo "<tr>";
					echo "<td><img src=\"".$file["icon"]."\"/> <a href=\"".$file["filename"]."\">".$file["filename"]."</a></td>";
					echo "<td>".$file["last-modified"]."</td>";
					echo "<td>".$file["file-size"]."</td>";
					echo "<td>".$file["file-type"]."</td>";
					echo "</tr>";
				}
				?>
			</table>
		</article>
	</section>
		
</body> 
</html>