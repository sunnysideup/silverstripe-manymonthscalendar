###############################################
ManyMonthsCalendar
Pre 0.1 proof of concept
###############################################

Developer
-----------------------------------------------
Nicolaas Francken [at] sunnysideup.co.nz

Requirements
-----------------------------------------------
SilverStripe 2.3.0 or greater.

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
2. copy configurations from this module's _config.php file
into mysite/_config.php file and edit settings as required.
NB. the idea is not to edit the module at all, but instead customise
it from your mysite folder, so that you can upgrade the module without redoing the settings.
