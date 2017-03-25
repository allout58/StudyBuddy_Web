<?php

function colorIntToHex($color){
    return "#".substr("000000".dechex($color),-6);
}

function colorIntToARGBHex($color) {
    return "#".substr("00000000".dechex($color),-8);
}

function makeColoredBox($color) {
    return "<div style='width: 40px; height: 40px; margin: 10px; background-color:" . colorIntToHex($color) . "'></div>";
}