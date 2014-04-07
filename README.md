ManyMonthsCalendar
================================================================================

Developer
-----------------------------------------------
Nicolaas Francken [at] sunnysideup.co.nz

Requirements
-----------------------------------------------
see composer.json

Documentation
-----------------------------------------------
Allows you to add one or more calendars to your pages.

to do so add the following code to your controller

function MyCalendar() {
	if(isset($_REQUEST["y"])) {
		$year = $_REQUEST["y"];
	}
	else {
		$year = 0;
	}
	if(isset($_REQUEST["m"])) {
		$month = $_REQUEST["m"];
	}
	else {
		$month = 0;
	}
	$calendar = new ManyMonthsCalendar($link = $this->Link(), $title = "MyCSSCODE", $year, $month); // set  year and month to 0 for current year and month
	$calendar->addEvent($timeStamp = 123, $title = "My Title", $link = "http://www.mysite.com")
	return $calender->ManyMonthsCalendar();
}

to style, add a css file called ManyMonthsCalendar.css to your themed folder
(review css file in module itself for selectors).


Installation Instructions
-----------------------------------------------
1. Find out how to add modules to SS and add module as per usual.
2. Review configs and add entries to mysite/_config/config.yml
(or similar) as necessary.
In the _config/ folder of this module
you can usually find some examples of config options (if any).
