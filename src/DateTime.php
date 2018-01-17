<?php
namespace RobinTheHood\DateTime;

class DateTime
{
    /*
     **********************
     *    DATUM FORMAT    *
     **********************
     */

    public static function weekDayNameLong($weekDay)
    {
        $dayNames = ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'];
        return $dayNames[$weekDay];
    }

    public static function dayName($datetime, $today = false)
    {
        $dayNames = ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'];
        $timeStamp = self::dateTimeToTimeStamp($datetime);
        $dateToday = date('d.m.Y');
        $dateTimeDate = date('d.m.Y', $timeStamp);
        if ($dateToday == $dateTimeDate && $today) {
            return 'Heute';
        } else {
            return $dayNames[date('w', $timeStamp)];
        }
    }

    /*Gibt ein UNIX Timestamp im Long-Formart aus.
    Z.B.: 17. Julie 2008 09:34:21*/
    public static function longDateTime($datetime)
    {
        if (self::isDateTime($datetime)) {
            $str = self::dateTimeToTimeStamp($datetime);
            $string = date("d. F Y - H:i ", $str);
            return $string;
        } else {
            return null;
        }
    }

    /*Gibt ein UNIX Timestamp im Short-Formart aus.
    Z.B.: 13.12.2009 21:33*/
    public static function shortDateTime($datetime)
    {
        if (self::isDateTime($datetime)) {
            $str = self::dateTimeToTimeStamp($datetime);
            $string = date("d.m.Y H:i", $str);
            return $string;
        } else {
            return '';
        }
    }

    public static function dynamicDateTime($datetime)
    {
        if (self::isDateTime($datetime)) {
            $diffMin = round(self::diffDateTimeSecondsToNow($datetime) / 60 * -1);
            $diffHours = round(self::diffDateTimeSecondsToNow($datetime) / 60 / 60 * -1);
            if ($diffMin < 60) {
                return 'Vor ' . $diffMin . ' Minuten';
            }

            if ($diffHours < 24) {
                return 'Vor ' . $diffHours . ' Stunden';
            }

            if (self::isYesterday($datetime)) {
                return 'Gestern um ' . self::shortTime($datetime);
            }

            return 'Am ' . self::shortDateTime($datetime);
        } else {
            return 'Unbekanntes Datum';
        }
    }



    // Kontrolliert, ob das $datetime der gleiche Tag wie heute ist.
    public static function isToday($datetime)
    {
        $diff = self::diffDateTimeDaysToNow($datetime);
        if ($diff == 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function isYesterday($datetime)
    {
        $diff = self::diffDateTimeDaysToNow($datetime) * -1;
        if ($diff == 1) {
            return true;
        } else {
            return false;
        }
    }

    public static function shortDateTimeWithDay($datetime)
    {
        if (self::isDateTime($datetime)) {
            $str = self::dateTimeToTimeStamp($datetime);
            $string = date("d.m.Y \u\m H:i", $str);
            $dayName = self::dayName($datetime, true);
            return $dayName . '. ' . $string;
        } else {
            return null;
        }
    }

    public static function shortDateWithDay($datetime)
    {
        if (self::isDateTime($datetime)) {
            $str = self::dateTimeToTimeStamp($datetime);
            $string = date("d.m.Y", $str);
            $dayName = self::dayName($datetime, true);
            return $dayName . '. ' . $string;
        } else {
            return null;
        }
    }

    public static function veryShortDateTime($datetime)
    {
        if (self::isDateTime($datetime)) {
            $str = self::dateTimeToTimeStamp($datetime);
            $string = date("d.m.Y H:i", $str);
            return $string;
        } else {
            return null;
        }
    }

    /*Gibt ein UNIX Timestamp im Date-Short-Formart aus.
    Z.B.: 13.12.2009*/
    public static function shortDate($date)
    {
        if (self::isDate($date)) {
            $str = self::dateTimeToTimeStamp($date);
            $string = date("d.m.Y", $str);
            return $string;
        } else {
            return null;
        }
    }

    /*Gibt ein UNIX Timestamp im Time-Short-Formart aus.
    Z.B.: 13.12.2009 21:33*/
    public static function shortTime($time)
    {
        if (self::isTime($time)) {
            $str = self::dateTimeToTimeStamp($time);
            $string = date("H:i", $str);
            return $string;
        } else {
            return null;
        }
    }


    public static function schemaDateTime($datetime)
    {
        if (!self::isDateTimeTime($datetime)) {
            return date('Y-m-d', strtotime($datetime));
        } else {
            return date('Y-m-d\TH:i:s', strtotime($datetime));
        }
    }

    public static function dbDateTimeNow()
    {
        return date('Y-m-d H:i:s');
    }

    public static function dbDateNow()
    {
        return date('Y-m-d');
    }


    /*
     *****************************
     *    DATUM PARSE / INPUT    *
     *****************************
     */


    //17.07.1997 12:54:03 -> 1987-07-17 12:54:03
    public static function parseDateTime($input)
    {
        $input = trim($input);
        if ($input) {
            $dateTime = strtotime($input);
            return date('Y-m-d H:i:s', $dateTime);
        } else {
            return '0000-00-00 00:00:00';
        }
    }

    //17.07.1997 12:54:03 -> 1987-07-17
    public static function parseDate($input)
    {
        if ($input) {
            $dateTime = strtotime($input);
            return date('Y-m-d', $dateTime);
        } else {
            return '0000-00-00';
        }
    }

    //17.07.1997 12:54:03 -> 12:54:03
    public static function parseTime($input)
    {
        if ($input) {
            if (strlen($input) == 2) {
                $input .= ':00:00';
            }

            if (strlen($input) == 1) {
                $input = '0' . $input . ':00:00';
            }

            $dateTime = strtotime($input);
            return date('H:i:s', $dateTime);
        } else {
            return '00:00:00';
        }
    }

    //17.07.1997 12:54:03 -> 1987-07-17 00:00:00
    public static function parseDateTimeDate($input)
    {
        if ($input) {
            $dateTime = strtotime($input);
            return date('Y-m-d', $dateTime) . ' 00:00:00';
        } else {
            return '0000-00-00 00:00:00';
        }
    }

    //17.07.1997 12:54:03 -> 0000-00-00 12:54::03
    public static function parseDateTimeTime($input)
    {
        if ($input) {
            $dateTime = strtotime($input);
            return '0000-00-00 ' . self::parseTime($input);
        } else {
            return '0000-00-00 00:00:00';
        }
    }


    public static function parseDateTimeDateAndTime($dateInput, $timeInput)
    {
        return self::parseDate($dateInput) . ' ' . self::parseTime($timeInput);
    }

    /*
     ********************
     *    DATUM TEST    *
     ********************
     */

    public static function isDate($value) {
        if (!trim($value)) {
            return false;
        }

        if (trim($value) == '0000-00-00') {
            return false;
        }

        if (trim($value) == '0000-00-00 00:00:00') {
            return false;
        }

        return true;
    }

    public static function isTime($value) {
        if (!trim($value)) {
            return false;
        }
        if (trim($value) == '00:00:00') {
            return false;
        }
        return true;
    }

    public static function isDateTime($value) {
        if (!trim($value)) {
            return false;
        }
        if (trim($value) == '0000-00-00 00:00:00') {
            return false;
        }
        return true;
    }

    public static function isDateTimeDate($value) {
        if (!trim($value)) {
            return false;
        }
        if (substr(trim($value), 0, 10) == '0000-00-00') {
            return false;
        }
        return true;
    }

    public static function isDateTimeTime($value) {
        $value = trim($value);
        if (!$value) {
            return false;
        }
        if (substr($value, 11, 8) == '00:00:00') {
            return false;
        }
        return true;
    }

    /*
     ***************************
     *    DATUM KALKULATION    *
     ***************************
     */
    public static function diffDateTimeSeconds($dateTimeStart, $dateTimeEnd)
    {
        $ts1 = strtotime($dateTimeStart);
        $ts2 = strtotime($dateTimeEnd);
        $secondsDiff = $ts2 - $ts1;
        return $secondsDiff;
    }

    // Liefert den Unterschied zwischen zwei DateTime oder Date in Tagen als Interger
    // Es wird nur der Datumsanteil berücksichtigt. Als Ergebnis gibt es nur ganze Zahlen.
    // Info: Ist $dateTimeStart größer $dateTimeEnd ist das Ergebnis negativ. Ist $dateTimeStart
    // kleiner $dateTimeEnd ist das Ergebnis positiv.
    public static function diffDateTimeDays($dateTimeStart, $dateTimeEnd)
    {
        $dateStart = self::cutTime($dateTimeStart);
        $dateEnd = self::cutTime($dateTimeEnd);
        $secondsDiff = self::diffDateTimeSeconds($dateStart, $dateEnd);
        $daysDiff = $secondsDiff / 3600 / 24;
        return $daysDiff;
    }

    public static function diffDateTimeSecondsToNow($dateTime)
    {
        $secondsDiff = self::diffDateTimeSeconds(date('Y-m-d H:i:s'), $dateTime);
        return $secondsDiff;
    }

    // Liefert die Unterschied in Tagen als Integer zwischen dem aktuellem Datum
    // und einem anderem Datum. Als Eingabe kann DateTime oder Date verwendet
    // werden. Es wird jeweils nur der Datumsanteil ohne Zeit verwendet.
    public static function diffDateTimeDaysToNow($dateTime)
    {
        $daysDiff = self::diffDateTimeDays(date('Y-m-d'), $dateTime);
        return $daysDiff;
    }


    public static function modifyDateTime($dateTime, $value)
    {
        $date = new DateTime($dateTime);
        $date->modify($value);
        return $date->format("Y-m-d H:i:s");
    }

    public static function formatDiffDays($days, $zeroDays = 'heute')
    {
        $days = floor($days);
        if (abs($days) == 0) {
            return $zeroDays;
        } elseif (abs($days) == 1) {
            $postfix = ' Tag';
        } else {
            $postfix = ' Tage';
        }
        if ($days < 0 ) {
            $str = '-' . abs($days) . $postfix;
        } else {
            $str = $days . $postfix;
        }
        return $str;
    }


    /*
     ******************************
     *    DATUM HILFSFUNTIONEN    *
     ******************************
     */
    public static function cutTime($dateTime) {
        return substr($dateTime, 0, 10);
    }

    public static function cutDate($dateTime) {
        return substr($dateTime, 11, 8);
    }

    /*Gibt ein DateTime Obj zurueck passend fuer die Datenbank*/
    public static function makeDateTime($day, $month, $year, $hour, $min, $sec) {
        return $year ."-". $month ."-". $day ." ". $hour .":". $min .":". $sec;
    }


    /*Macht aus einem DateTime Objekt aus einer Datenbank ein UNIX Timestamp
    Ein UNIX Timestamp kann dann mit date in das gewuenschte Format gebracht
    werden oder mit den folgenen Funktionen*/
    public static function dateTimeToTimeStamp($datetime)
    {
        $year   = substr($datetime,0,4);
        $month  = substr($datetime,5,2);
        $day    = substr($datetime,8,2);
        $hour   = substr($datetime,11,2);
        $minute = substr($datetime,14,2);
        $second = substr($datetime,17,2);

        date_default_timezone_set("Europe/Berlin");
        $string = mktime($hour, $minute, $second, $month, $day, $year);

        return $string;
    }
}
