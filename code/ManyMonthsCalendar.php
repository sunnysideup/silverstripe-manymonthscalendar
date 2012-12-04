<?php



class ManyMonthsCalendar extends ViewableData {

	//settings
	private static $day_names = array ( 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
	private static $month_names = array ( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' );
	private static $previous_month_nav_text = '&laquo';
	private static $next_month_nav_text = '&raquo';
	private static $start_day_of_the_week = 1;
	private static $show_year_in_calendar = true;
	private static $enable_navigation = true;
	private static $calendar_day_format = "j"; // see http://nz.php.net/manual/en/function.date.php for formatting options
	private static $table_cell_spacing = 0; // see http://nz.php.net/manual/en/function.date.php for formatting options

	//variables to be added at runtime
	private $events = array();
	private $calendarName = "MMC";
	private $pageLink = "pleasesetlink";

	//internal variables
	private static $count_of_calenders_shown = 0;
	private $month = 1;
	private $year = 1974;
	private $timeStamp = 0;
	private $daysInMonth = 25; //set to unrealistic number to make sure that it actually gets set correctly

	static function setDayNames ($dayArray) {
		if(!is_array($dayArray) || count($dayArray) != 7) {
			debug::show($dayArray);
			user_error('There should be seven days in the array passed tot he setDayNames function in the ManyMonthsCalendar Class', E_USER_ERROR);
		}
		self::$day_names = $dayArray;
	}

	static function setMonthNames ($monthArray) {
		if(!is_array($monthArray) || count($monthArray) != 12) {
			debug::show($monthArray);
			user_error('There should be twelve months in the array passed tot he setMonthNames function in the ManyMonthsCalendar Class', E_USER_ERROR);
		}
		self::$month_names = $monthArray;
	}

	static function setPreviousMonthNavText($text) {
		self::$previous_month_nav_text = $text;
	}

	static function setNextMonthNavText($text) {
		self::$next_month_nav_text = $text;
	}

	static function setStartDayOfTheWeek ($numericDay) {
		if (is_int($numericDay) && $numericDay >= 0 && $numericDay <=6) {
			self::$start_day_of_the_week = $numericDay;
			for ( $i = 0 ; $i < $numericDay ; $i++ ) {
				array_unshift(self::$day_names, array_pop(self::$day_names));
			}
		}
		else {
			debug::show($numericDay);
			user_error('Start Day of the Week should be between 0 and 6, 0=monday, 1=sunday, 2=saturday etc...)', E_USER_ERROR);
		}
	}

	static function setShowYearInCalendar($booleanValue) {
		self::$show_year_in_calendar = $booleanValue;
	}

	static function setEnableNavigation($booleanValue) {
		self::$enable_navigation = $booleanValue;
	}

	static function setCalendarDayFormat($format) {
		self::$calendar_day_format = $format;
	}

	static function setTableCellSpacing ($pixels) {
		if (is_int($pixels) && $pixels >= 0 && $pixels <=500) {
			self::$table_cell_spacing = $pixels;
		}
		else {
			debug::show($pixels);
			user_error('Please set the right number of pixels for table spacing (e.g. 0, 1, 2 or 3)', E_USER_ERROR);
		}
	}

	public function __construct($pageLink, $name, $year = 0, $month = 0) {
		$this->pageLink = $pageLink;
		// Assign name to calendar
		if (strpos($name, ' ') || strpos($name, '_') || is_numeric(substr($name, 0, 1))) {
			debug::show($name);
			user_error('Calendar should have a valid CSS name in the ManyMonthsCalendar Class', E_USER_ERROR);
		}
		$this->calendarName = $name;
		
		// Set day, month and year of calendar
		$this->month = (0 == $month ) ?	date('n') : $month;
		$this->year = (0 == $year) ? date('Y') : $year;

		// Check for valid input
		if (!preg_match('~[0-9]{4}~', $this->year)) {
			debug::show($this->year);
			user_error('Year should be a valid number in the ManyMonthsCalendar Class', E_USER_ERROR);
		}
		if (!is_numeric($this->month) || $this->month < 0 || $this->month > 13) {
			debug::show($this->month);
			user_error('Month should be a valid number in the ManyMonthsCalendar Class', E_USER_ERROR);
		}
		// Set the current timestamp
		$this->timeStamp = mktime(1,1,1,$this->month, 1, $this->year);
		// Set the number of days in the current month
		$this->daysInMonth = date('t',$this->timeStamp);
	}



	public function addEvent($timeStamp, $title, $link = '') {
		$this->events[$timeStamp][$this->cleanEventTitle($title)] = array(
			'Title' => $title,
			'Link' => $link
		);
	}

	public function removeEvent($timeStamp, $title) {
		if(isset($this->events[$timeStamp][$this->cleanEventTitle($title)])) {
			unset($this->events[$timeStamp][$this->cleanEventTitle($title)]);
		}
	}

	public function ManyMonthsCalendar() {
		Requirements::themedCSS("ManyMonthsCalendar");
		//make sure that all arrays added to calendarArray are DoSets in their own right
		self::$count_of_calenders_shown++;
		$calendarArray = array();
		//general variables
		$calendarArray["TableID"] = $this->calenderName.'-'.self::$count_of_calenders_shown;
		$calendarArray["TableClass"] = $this->calenderName;
		$calendarArray["EnableNavigation"] = self::$enable_navigation;
		$calendarArray["ColSpan"] = 5;
		$calendarArray["PageLink"] = $this->pageLink;
		$calendarArray["MonthName"] = $this->getMonthName();
		if(self::$show_year_in_calendar) {
			$calendarArray["YearName"] = $this->year;
		}
		$calendarArray["CellSpacing"] = self::$table_cell_spacing;
		$calendarArray["PreviousMonthNavText"] = self::$previous_month_nav_text;
		$calendarArray["NextMonthNavText"] = self::$next_month_nav_text;
		if (self::$enable_navigation) {
			$previousMonth = explode('-', date('n-Y', strtotime('-1 month', $this->timeStamp)));
			$calendarArray["PreviousMonthMonthNumber"] = $previousMonth[0];
			$calendarArray["PreviousMonthYearNumber"] = $previousMonth[1];
			$nextYear = explode('-', date('n-Y', strtotime('+1 month', $this->timeStamp)));
			$calendarArray["NextMonthMonthNumber"] = $nextYear[0];
			$calendarArray["NextMonthYearNumber"] = $nextYear[1];
			$calendarArray["ColSpan"] = 7;
		}

		$doSetDayNames = new DataObjectSet();
		//day name
		foreach(self::$day_names as $name) {
			$doSetDayNames->push(new ArrayData(array("DayName" => $name)));
		}
		$calendarArray["DayNames"] = $doSetDayNames;

		$extraDaysToBeAddedFromPreviousMonth = date('N', $this->timeStamp) + self::$start_day_of_the_week - 1;
		$position = 0;

		$doSetDates = new DataObjectSet();
		// previous month
		for ( $e = 1 ; $e <= $extraDaysToBeAddedFromPreviousMonth ; $e++ ) {
			$position++;
			$timeStamp = $this->makeTimeStamp("-" . intval($extraDaysToBeAddedFromPreviousMonth -$e) . " days");
			$doSetDates->push($this->getDateDataObject($position, $timeStamp, false));
		}

		// current Month
		for ($i = 1 ; $i <= $this->daysInMonth ; $i++ ) {
			$position++;
			$timeStamp = $this->makeTimeStamp("+".($i-1)." days");
			$doSetDates->push($this->getDateDataObject($position, $timeStamp));
		}

		// next month
		for ( $e2 = 1 ; $e2 < (7 - (($e + $this->daysInMonth -1) % 7)) ; $e2++ ) {
			$position++;
			$timeStamp = $this->makeTimeStamp("+$e2 days");
			$doSetDates->push($this->getDateDataObject($position, $timeStamp, false));
		}
		$calendarArray["Days"] = $doSetDates;
		return new ArrayData($calendarArray);
	}

	private function getDateDataObject($position, $timeStamp, $currentMonth = TRUE) {
		$array = array();
		$array["Day"] = date(self::$calendar_day_format, $timeStamp);
		if($currentMonth) {
			$array["OutsideCurrentMonth"] = true;
		}
		else {
			$array["OutsideCurrentMonth"] = false;
		}
		$eventsDoSet = new DataObjectSet();
		if(isset($this->events[$timeStamp])) {
			if(is_array($this->events[$timeStamp])) {
				foreach($this->events[$timeStamp] as $event) {
					$eventsDoSet->push(new ArrayData($event));
				}
			}
		}
		$array["Events"] = $eventsDoSet;
		if(($position % 7) == 0 /*&& $position < */) {
			$array["LastDayOfTheWeek"] = true;
		}
		if($timeStamp == mktime(1,1,1,date('n'),date('j'),date('Y'))) {
			$array["IsCurrentDay"] = true;
		}
		else {
			$array["IsCurrentDay"] = false;
		}
		if($position % 2) {
			$array["EvenCol"] = true;
		}
		if(round($position / 7) % 2) {
			$array["EvenRow"] = false;
		}
		return new ArrayData($array);
	}

	private function cleanEventTitle($title) {
		$title = eregi_replace("[^[:alnum:]]", " ", $title);
		$title = trim(eregi_replace(" +", "", $title)); //removes excess spaces
		return $title;
	}

	private function makeTimeStamp($offset) {
		return strtotime($offset, $this->timeStamp);
	}

	private function getMonthName() {
		return ucwords(self::$month_names[$this->month-1]);
	}

	function forTemplate() {return $this->renderWith('ManyMonthsCalendar');}
}
