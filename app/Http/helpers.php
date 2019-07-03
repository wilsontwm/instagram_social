<?php

/**
 * Truncate the string if exceeded a max limit
 * @param $string
 * @param int $chars
 * @return string
 */
function substr_with_ellipsis($string, $chars = 100)
{
    preg_match('/^.{0,' . $chars. '}(?:.*?)\b/iu', $string, $matches);
    $new_string = $matches[0];
    return ($new_string === $string) ? $string : $new_string . '&hellip;';
}

/**
 * Return a string of datetime based on difference from current time
 * @param string $datetime
 * @return string
 */
function timeDiff($datetime = '')
{
    $datetime = \Carbon\Carbon::parse($datetime);
    return $datetime->diffForHumans();
}

/**
 * Return a number and mutate the number if exceed certain capped number
 * @param int $number
 * @param int $cappedNumber
 * @return int|string
 */
function cappedNumber($number = 0, $cappedNumber = 99)
{
    if($number > $cappedNumber)
    {
        return $cappedNumber.'+';
    }
    return $number;
}
?>