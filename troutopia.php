<?php

/* TROUTOPIA WEBCOMIC MANAGEMENT SYSTEM */

/* Version Alpha 2022.05.13 */

/* Troutopia is a simple webcomic backend for any creator, but mostly for me, Jodie Troutman. It allows users to avoid large installations of other CMS platforms and also gives users more control over their website's design.
Troutopia is fairly no-frills and does absolutely no web design for you; all it does is run the webcomic archive with some simple hooks. I taught myself PHP in the process of coding this, so things may or may not work correctly.
Feel free to shoot any questions about Troutopia to me on Twitter via @LongTallJodie or through e-mail via troutcave@gmail.com. Read my comics at troutcave.net. */

/* The following code has been annotated to the best of my ability, but please reference the Troutopia manual for installation details. */

/* Change the following variables to match your site.  Edit only what's inside the quotes. */

$site = "https://troutcave.net/troutopia";		// Website URL with leading https:// or HTTPS://
$comic_dir = "comics";				// Comic Directory
$news_dir = "news";					// News Directory
$cast_dir = "cast";					// Cast Directory
$extra_dir = "extra";				// Extra Directory
$alttext_dir = "alttext";				// Extra Directory
$comic_ext = "png";					// Comic File Extension (PNG, JPG)
$news_ext = "php";					// News File Extension (Also Used For Bonuses)
$first_comic = "2013-11-04";		// Date of the First Comic in YYYY-MM-DD
$first_nav = "<span class='fa-stack fa-lg icon-hover' title='First Comic'><i class='fa fa-circle fa-stack-2x icon-background text-shadow'></i><i class='fas fa-angle-double-left fa-stack-1x icon-text'></i></span>";			// Navigation Text (Can Be Any HTML Span)
$last_nav = "<span class='fa-stack fa-lg icon-hover' title='Last Comic'><i class='fa fa-circle fa-stack-2x icon-background text-shadow'></i><i class='fas fa-angle-double-right fa-stack-1x icon-text'></i></span>";			// Navigation Text (Can Be Any HTML Span)
$previous_nav = "<span class='fa-stack fa-lg icon-hover' title='Previous Comic'><i class='fa fa-circle fa-stack-2x icon-background text-shadow'></i><i class='fas fa-angle-left fa-stack-1x icon-text'></i></span>";	// Navigation Text (Can Be Any HTML Span)
$next_nav = "<span class='fa-stack fa-lg icon-hover' title='Next Comic'><i class='fa fa-circle fa-stack-2x icon-background text-shadow'></i><i class='fas fa-angle-right fa-stack-1x icon-text'></i></span>";			// Navigation Text (Can Be Any HTML Span)
$random_nav = "<span class='fa-stack fa-lg icon-hover' title='Random Comic'><i class='fa fa-circle fa-stack-2x icon-background text-shadow'></i><i class='fas fa-dice fa-stack-1x icon-text'></i></span>";			// Navigation Text (Can Be Any HTML Span)

/* Please stop editing now, unless you really know what you're doing. You shouldn't have to edit the rest of the file unless you're a particularly elite hacker, possibly in a van surrounded by several monitors. */

/* This creates the array of comic dates ($comics) used to populate the archive. */

$comics_raw = glob("$comic_dir/*.*");

function comic_rtrim($t) {
	return rtrim($t,".png");
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

$query_date_full = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
$query_date_temp = parse_str($query_date_full, $query_date_array);
$query_date = $query_date_array['date'];

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

/* Define the random comic. */

$random = array_rand($comics, 1);

/* Define date strings. */

$string_date = strtotime($query_date);
$pretty_date = date("F d, Y", $string_date);
$string_last_date = strtotime($last);
$pretty_last_date = date("F d, Y", $string_last_date);

/* Defines the title of the current comic using the first line of the news file. */

$ftitle = fopen("$site/$news_dir/$query_date.$news_ext", "r");
$comic_title = strip_tags(fgets($ftitle));
fclose($ftitle);

/* These functions are used for comic archive navigation */

function first_comic() {
	global $site, $comic_dir, $comic_ext, $first_comic, $comics, $previous, $now, $query_date, $last, $first_nav;
	if ($query_date == $first_comic) {
		echo "<span style='opacity:.3'>$first_nav</span>";
	} else {
		echo "<a href='$site/comic.php?date=$first_comic'>$first_nav</a>";
	}
}

function previous_comic() {
	global $site, $comic_dir, $comic_ext, $first_comic, $comics, $previous, $now, $query_date, $last, $previous_nav;
	if ($query_date == $first_comic) {
		echo "<span style='opacity:.3'>$previous_nav</span>";
	} else {
		echo "<a href='$site/comic.php?date=$previous'>$previous_nav</a>";
	}
}

function next_comic() {
	global $site, $comic_dir, $comic_ext, $comics, $next, $now, $query_date, $last, $next_nav;
	if ($query_date == null) {
		echo "<span style='opacity:.3'>$next_nav</span>";
	} elseif ($query_date == $last) {
		echo "<span style='opacity:.3'>$next_nav</span>";
	} else {
		echo "<a href='$site/comic.php?date=$next'>$next_nav</a>";
	}
}

function last_comic() {
	global $site, $comic_dir, $comic_ext, $comics, $next, $now, $query_date, $last, $last_nav;
	if ($query_date == null) {
		echo "<span style='opacity:.3'>$last_nav</span>";
	} elseif ($query_date == $last) {
		echo "<span style='opacity:.3'>$last_nav</span>";
	} else {
		echo "<a href='$site/comic.php?date=$last'>$last_nav</a>";
	}
}

function random_comic() {
	global $site, $random, $random_nav, $comics;
	echo "<a href='$site/comic.php?date=$comics[$random]'>$random_nav</a>";
}


/* These functions display the various dynamic sections. */

function show_comic() {
	global $site, $comic_dir, $comic_ext, $query_date, $last, $pretty_date, $pretty_last_date, $comic_title, $alttext_dir;
	if ($query_date == null) {
		$alttext_file = file_get_contents("$site/$alttext_dir/$last.txt");
		echo "<img src='$site/$comic_dir/$last.$comic_ext' alt='Comic Strip' title='";
		echo $alttext_file;
		echo "'>";
	} else {
		$alttext_file = file_get_contents("$site/$alttext_dir/$query_date.txt");
		echo "<img src='$site/$comic_dir/$query_date.$comic_ext' alt='Comic Strip' title='";
		echo $alttext_file;
		echo "'>";
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

/* This function aids in making the comic strip clickable. */

function comic_click() {
	global $site, $comic_dir, $comic_ext, $comics, $next, $now, $query_date, $last, $next_nav;
	if ($query_date == null) {
		null;
		} elseif ($query_date == $last) {
			null;
		} else {
			echo "<a href='$site/comic.php?date=$next'>";
		}
}

?>
