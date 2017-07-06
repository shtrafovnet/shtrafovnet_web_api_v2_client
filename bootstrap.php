<?php

function warningForegroundColor($string)
{
    return "\033[1;33m".$string."\033[0m";
}

function dangerForegroundColor($string)
{
    return "\033[1;31m".$string."\033[0m";
}

function successForegroundColor($string)
{
    return "\033[0;32m".$string."\033[0m";
}

function displayResponse($headers, $body)
{
    echo successForegroundColor(str_pad("", 40, "=", STR_PAD_LEFT))."\n";
    echo successForegroundColor("RESPONSE HEADERS")."\n";
    echo successForegroundColor(str_pad("", 40, "=", STR_PAD_LEFT))."\n";
    echo $headers."\n\n";


    echo successForegroundColor(str_pad("", 40, "=", STR_PAD_LEFT))."\n";
    echo successForegroundColor("RESPONSE BODY (JSON)")."\n";
    echo successForegroundColor(str_pad("", 40, "=", STR_PAD_LEFT))."\n";
    print_r(json_encode(json_decode($body, true), JSON_PRETTY_PRINT));
    echo "\n\n";
}

function displayError(\Exception $e)
{
    echo dangerForegroundColor(str_pad("", 40, "=", STR_PAD_LEFT))."\n";
    echo dangerForegroundColor("ERROR:")."\n";
    echo dangerForegroundColor(str_pad("", 40, "=", STR_PAD_LEFT))."\n";

    echo $e->getMessage()."\n\n";
}

if (!is_file("app/config/parameters.yml")) {
    throw new \Exception("Config file not found!\nUse: cat app/config/parameters.yml.dist > app/config/parameters.yml");
}