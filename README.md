# Troutopia Webcomic Management System

Troutopia is a simple webcomic backend for any creator, but mostly for, me, John S. Troutman. It allows users to avoid large installations of other CMS platforms and also gives users more control over their website's code.

Troutopia is fairly no-frills and does absolutely no web design for you; all it does is run the webcomic archive with some simple hooks. Said hooks are one line bits of text you can drop into any section of your website.

I taught myself PHP in the process of coding this, so things may or may not work correctly. Feel free to shoot any questions about Troutopia to me on Twitter via @LongTallJodie or through e-mail via troutcave@gmail.com. I also have a Patreon page at https://www.patreon.com/troutcave.

A demo of Troutopia in action is available at https://litbrick.com/.

## Status

### Version Alpha

* **10/29/20:** Fixed a bug allowing you to still click the last comic in the archive. Added the "date=" key to all the comic page URLs.
* **9/2/20:** Refreshed the code to reflect my name change.
* **12/12/19:** Bugs ironed out index page comics and navigation.  News posts have been implemented.  Added a safety check to redirect readers to the homepage in case they type an invalid comic date into the URL.
* **12/11/19:** Basic comic navigation is fully functional.  Huzzah!

## Usage

This is pretty lazy documentation and will be updated in the future, on the off-chance anyone cares.

### Installation

* Place troutcave.php in your root directory, alongside comic.php and index.php.
* Edit the variables at the beginning of troutopia.php to reflect your own comic setup.  I'd suggest keeping the default directory variables the same.
* Place all your comic files (named precisely after YYYY-MM-DD dates) in the comics directory.
* Place all your news files (named identically to the comic files) in the news directory.
* Paste `<?php include('troutopia.php') ?>` at the very beginning of your comic.php and index.php files, in-between the `<head>` tags.
* Paste `<?php show_comic() ?>` anywhere you'd like the comic image to appear.
* Paste `<?php show_news() ?>` anywhere you'd like the news post to appear.
* Paste `<?php first_comic() ?>`, `<?php previous_comic() ?>`, `<?php next_comic() ?>`, and `<?php last_comic() ?>` anywhere you'd like the comic navigation links to appear.
  
### Extra Hooks

* `<?php pretty_date() ?>` will display the date of that page's comic in a "pretty" format (ie, January 31, 2000).  This only works on archive pages.  `<?php pretty_last_date() ?>` will display the date of the latest comic in a "pretty" format.  This works on any page.
* `<?php random_comic() ?>` will add a Random Comic option to your navigation links.  This is great for gag strips!
* `<?php comic_click() ?><?php show_comic() ?></a>` will show your comic **and** make it clickable for navigation.

### Notes

* The PHP hooks will **not** work inside of news post files.
* Use this code at your own risk.  It should be fine, but I'm not responsible if something on your website explodes.
