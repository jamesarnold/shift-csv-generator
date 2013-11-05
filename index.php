<?php

//Start date
$date = new DateTime("2013-09-23");

$shifts = [];

$entry[] = 'Subject, Start Date, Start Time, End Date, End Time, All Day Event';

if ( ($handle = fopen("shifts.csv", "r", ",")) !== FALSE )
{
	while ( ($data = fgetcsv($handle, 1000, ",")) !== FALSE )
	{
		$shifts = array_merge($shifts, $data);
	}

	fclose($handle);

	foreach ( $shifts as $shift )
	{

		if ( $result = buildentry($shift, $date) )
		{
			$entry[] = $result;
		}

		$date = $date->add(new DateInterval("P1D"));
	}

}

echo '<pre>';
print_r(implode("\n", $entry));


/**
 * Generate a CSV string based on shift type
 * 
 */
function buildEntry($shift, DateTime $date)
{
	$sTime = null;
	$sDate = clone $date;

	$allDay = 'false';

	switch ( $shift )
	{
		case "ET":
			$sTime = "07:00";
			$hrs = "8";
			break;
		
		case "MID":
			$sTime = "12:00";
			$hrs = "10";
			break;
		
		case "L9":
			$sTime = "14:00";
			$hrs = "9";
			break;
		
		case "L10":
			$sTime = "13:00";
			$hrs = "10";
			break;
		
		case "DAY":
			$sTime = "09:00";
			$hrs = "8";
			break;
		
		case "N":
			$sTime = "17:00";
			$hrs = "10";
			break;
		
		case "RD":
			return false;
	}

	$entry = implode(',',
		[
		'subject' => $shift,
		'sDate' => $sDate->format("m/d/y"),
		'sTime' => $sDate->modify($sTime)->format("H:i"),
		'eDate' => $sDate->modify($hrs . " hours")->format("m/d/y"),
		'eTime' => $sDate->format("H:i"),
		'allDay' => $allDay
	]);

	return trim($entry, ',');
}
