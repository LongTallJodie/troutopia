<?php

/* TROUTOPIA WEBCOMIC MANAGEMENT SYSTEM */

/* Version Alpha */

/* Troutopia is a simple webcomic backend for any creator, but mostly for, me, John S. Troutman. It allows users to avoid large installations of other CMS platforms and also gives users more control over their website's design. Troutopia is fairly no-frills and does absolutely no web design for you; all it does is run the webcomic archive with some simple hooks. I taught myself PHP in the process of coding this, so things may or may not work correctly. Feel free to shoot any questions about Troutopia to me on Twitter via @theonlytrout or through e-mail via troutcave@gmail.com. Read my comics at troutcave.net. */

/* The following code has been annotated to the best of my ability, but please reference the Troutopia manual for installation details. */

/* Change the following variables to match your site.  Edit only what's inside the quotes. */

$site = "http://troutcave.net/troutopia";		// Website URL with leading HTTP:// or HTTPS://
$comic_dir = "comics";				// Comic Directory
$news_dir = "news";					// News Directory
$cast_dir = "cast";					// Cast Directory
$extra_dir = "extra";				// Extra Directory
$comic_ext = "jpg";					// Comic File Extension (PNG, JPG)
$news_ext = "php";					// News File Extension (Also Used For Bonuses)
$first_comic = "2017-01-31";		// Date of the First Comic in YYYY-MM-DD

/* Please stop editing now, unless you really know what you're doing. You shouldn't have to edit the rest of the file unless you're a particularly elite hacker, possibly in a van surrounded by several monitors. */

/* This creates the array of comic dates ($comics) used to populate the archive. */

$comics_raw = glob("$comic_dir/*.*");

function comic_rtrim($t) {
	return rtrim($t,".jpg");
}
function comic_ltrim($t) {
	return ltrim($t,"comics/");
}
function comic_dates($t) {
	$date = strtotime($t);
	return date('Y-m-d', $date);
}

$comics_rare = array_map("comic_rtrim", $comics_raw);
$comics_medium = array_map("comic_ltrim", $comics_rare);
$comics = array_map("comic_dates", $comics_medium);

/* Eliminate future comics from the array to hide scheduled updates. */

$comics = array_filter($comics,function($date){
    return strtotime($date) <= strtotime('today');
});

/* Pull comic date from the URL query. */

$query_date = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);

/* Redirect to the homepage if the URL contains an invalid comic date. */

if (in_array("$query_date", $comics)) {
    null ;
} elseif ($query_date == null) {
		null ;
	} else {
    header("Location: $site/index.php");
}

/* Set the comic array pointer to the current comic. */

reset($comics);
while (!in_array(current($comics), ["$query_date", null])) {
    next($comics);
}
if (current($comics) !== false) {
    $now = (current($comics));
}

/* Define the "next" and "previous" dates. */

next($comics);
$next = current($comics);
prev($comics);
prev($comics);
$previous = current($comics);
next($comics);
$now = current($comics);
$last = $comics[count($comics)-1];

if ($query_date == $last) {
	$previous = $comics[count($comics)-2];
}

if ($query_date == null) {
	$previous = $comics[count($comics)-2];
}

$string_date = strtotime($query_date);
$pretty_date = date("F d, Y", $string_date);
$string_last_date = strtotime($last);
$pretty_last_date = date("F d, Y", $string_last_date);

/* These functions are used for comic archive navigation */

function first_comic() {
	global $site, $comic_dir, $comic_ext, $first_comic, $comics, $previous, $now, $query_date, $last;
	if ($query_date == $first_comic) {
		echo "<span style='opacity:.3'>First Comic</span>";
	} else {
		echo "<a href='$site/comic.php?$first_comic'>First Comic</a>";
	}
}

function previous_comic() {
	global $site, $comic_dir, $comic_ext, $first_comic, $comics, $previous, $now, $query_date, $last;
	if ($query_date == $first_comic) {
		echo "<span style='opacity:.3'>Previous Comic</span>";
	} else {
		echo "<a href='$site/comic.php?$previous'>Previous Comic</a>";
	}
}

function next_comic() {
	global $site, $comic_dir, $comic_ext, $comics, $next, $now, $query_date, $last;
	if ($query_date == null) {
		echo "<span style='opacity:.3'>Next Comic</span>";
	} elseif ($query_date == $last) {
		echo "<span style='opacity:.3'>Next Comic</span>";
	} else {
		echo "<a href='$site/comic.php?$next'>Next Comic</a>";
	}
}

function last_comic() {
	global $site, $comic_dir, $comic_ext, $comics, $next, $now, $query_date, $last;
	if ($query_date == null) {
		echo "<span style='opacity:.3'>Last Comic</span>";
	} elseif ($query_date == $last) {
		echo "<span style='opacity:.3'>Last Comic</span>";
	} else {
		echo "<a href='$site/comic.php?$last'>Last Comic</a>";
	}
}

/* These functions display the various dynamic sections. */

function show_comic() {
	global $site, $comic_dir, $comic_ext, $query_date, $last, $pretty_date, $pretty_last_date;
	if ($query_date == null) {
		echo "<img src='$site/$comic_dir/$last.$comic_ext' alt='Comic for $pretty_last_date' title='Comic for $pretty_last_date'>";
	} else {
		echo "<img src='$site/$comic_dir/$query_date.$comic_ext' alt='Comic for $pretty_date' title='Comic for $pretty_date'>";
	}
}

function show_news() {
	global $site, $news_dir, $news_ext, $query_date, $last;
	if ($query_date == null) {
		$news_file = file_get_contents("$site/$news_dir/$last.$news_ext");
		echo $news_file;
	} else {
		$news_file = file_get_contents("$site/$news_dir/$query_date.$news_ext");
		echo $news_file;
	}
}

?>