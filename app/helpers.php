<?php

function cisNumFix ($num) {
    $numCorr = str_replace(',', '.', trim($num));
    if (is_numeric($num)) {
        return $numCorr;
    } else {
        return '0';
    }
}
function cisGetCompList($id=0) {
    $q = DB::table('company');
    if($id > 1) {
        return $q->where('id',$id)->pluck('name', 'id')->toArray();
    } else {
        return array('0' => 'izberi stranko') + $q->orderBy('name')->pluck('name', 'id')->toArray();
    }               
}
function ovrIsDate($str) {
    $stamp = strtotime($str);
    if (!is_numeric($stamp)) {
        return FALSE;
    }
    $month = date('m', $stamp);
    $day = date('d', $stamp);
    $year = date('Y', $stamp);
    return checkdate($month, $day, $year);
}

function ovrGetYearList() {
    return array('0' => 'izberi leto') + array_combine(range(date("Y"), date("Y") - 10), range(date("Y"), date("Y") - 10));
}

function ovrDateFixSql($date) {
    if (empty($date)) {
        return '';
    } else {
        return date('Y-m-d', strtotime($date));
    }
}

function ovrDateFixShow($date) {
    if (empty($date)) {
        return '';
    } else {
        return date('d.m.Y', strtotime($date));
    }
}
function ovrCsvToArr($filename, $delimiter = ',', $enclosure = '"', $escape = '\\') {
    if (!file_exists($filename) || !is_readable($filename))
        return false;
    $header = null;
    $data = array();
    $lines = file($filename);

    foreach ($lines as $line) {
        $values = str_getcsv($line, $delimiter, $enclosure, $escape);
        if (!$header)
            $header = $values;
        else
            $data[] = array_combine($header, $values);
    }
    return $data;
}
