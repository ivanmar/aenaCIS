<?php

function cisNumFix ($num) {
    $numCorr = str_replace(',', '.', trim($num));
    if (is_numeric($num)) {
        return $numCorr;
    } else {
        return '0';
    }
}
