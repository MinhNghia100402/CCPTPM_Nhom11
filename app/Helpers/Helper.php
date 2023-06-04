<?php

use Carbon\Carbon;

//hien thi tien
function printMoney($value)
{
    return number_format($value) . ' đ';
}
//hien thi thoi gian viet nam Y-m-d H:i:s
function timeForHumans($time)
{
    $time = strtotime($time);
    $temp = date('a', $time);
    $time = date('H ', $time);
    return $time . ($temp == 'am' ? 'Giờ sáng' : 'Giờ chiều');
}
//hien thi thoi gian 
function getDateTimeLaravel($timeJson)
{
    $time = new Carbon($timeJson);
    $time->setTimezone(config('app.timezone'));
    return $time->toDateTimeString();
}
function getDateLaravel($timeJson)
{
    $time = new Carbon($timeJson);
    $time->setTimezone(config('app.timezone'));
    return $time->toDateString();
}
function getTimeLaravel($timeJson)
{
    $time = new Carbon($timeJson);
    $time->setTimezone(config('app.timezone'));
    return $time->toTimeString();
}
//tinh tien cho order theo gio cao diem
function getPriceOrder($arrTimeMain, $arrDateTimeCheck, $price_per_hour, $price_per_peak_hour)
{
    $dateTimeStart = new Carbon($arrDateTimeCheck['start_at']);
    $dateTimeStart->setTimezone(config('app.timezone'));
    $dateTimeEnd = new Carbon($arrDateTimeCheck['end_at']);
    $dateTimeEnd->setTimezone(config('app.timezone'));



    $timeMainStart = new Carbon($dateTimeStart->format('Y-m-d H:i:s'));
    $time = explode(':', $arrTimeMain['time_start']);
    $timeMainStart->setTime($time[0], $time[1], $time[2]);
    $timeMainEnd = new Carbon($dateTimeEnd->format('Y-m-d H:i:s'));
    $time = explode(':', $arrTimeMain['time_end']);
    $timeMainEnd->setTime($time[0], $time[1], $time[2]);

    $start = $dateTimeStart;
    $end = $dateTimeEnd;
    if ($dateTimeEnd->diffInhours($timeMainEnd, false) < 0) {
        $end = $timeMainEnd;
    }
    if ($dateTimeStart->diffInHours($timeMainStart, false) >= 0) {
        $start = $timeMainStart;
    }
    $peak_hour = ($start->diffInHours($end, false) > 0) ? $start->diffInHours($end, false) : 0;
    $hour = $dateTimeStart->diffInMinutes($dateTimeEnd, false) / 60;
    return $peak_hour * $price_per_peak_hour + ($hour - $peak_hour) * $price_per_hour;
}

//kiem tra xem thoi gian time co nam trong khoang thoi gian time_start va time_end khong

function isOrderInTime(string $datetime_start, string $datetime_end, string $dateTimeMain_start, string $dateTimeMain_end): bool
{
    $datetime_start = new Carbon($datetime_start);
    $datetime_end = new Carbon($datetime_end);
    $dateTimeMain_start = new Carbon($dateTimeMain_start);
    $dateTimeMain_end = new Carbon($dateTimeMain_end);

    if ($datetime_start >= $dateTimeMain_start && $datetime_start < $dateTimeMain_end) {
        return true;
    }
    if ($datetime_end > $dateTimeMain_start && $datetime_end <= $dateTimeMain_end) {
        return true;
    }
    return false;
}

function setDateFilter($order)
{
    switch ($order['filter']) {
        case 'today':
            $order['date']['start']->startOfDay();
            $order['date']['end']->endOfDay();
            //
            $order['dateCompare']['start']->subDays();
            $order['dateCompare']['start']->startOfDay();
            $order['dateCompare']['end']->subDays();
            $order['dateCompare']['end']->endOfDay();
            break;
        case 'this_month':
            $order['date']['start']->startOfMonth();
            $order['date']['end']->endOfMonth();
            //
            $order['dateCompare']['start']->subMonths();
            $order['dateCompare']['start']->startOfMonth();
            $order['dateCompare']['end']->subMonths();
            $order['dateCompare']['end']->endOfMonth();
            break;
        case 'this_year':
            $order['date']['start']->startOfYear();
            $order['date']['end']->endOfYear();
            //
            $order['dateCompare']['start']->subYears();
            $order['dateCompare']['start']->startOfYear();
            $order['dateCompare']['end']->subYears();
            $order['dateCompare']['end']->endOfYear();
            break;
        default:
            $order['date']['start']->startOfDay();
            $order['date']['end']->endOfDay();
            //
            $order['dateCompare']['start']->subDays();
            $order['dateCompare']['start']->startOfDay();
            $order['dateCompare']['end']->subDays();
            $order['dateCompare']['end']->endOfDay();
    }
    return $order;
}
