<?php


function fd($var)
{
    return (Tools::fd($var));
}

function p($var)
{
    return (Tools::p($var));
}

function d($var)
{
    Tools::d($var);
}

function ppp($var)
{
    return (Tools::p($var));
}

function ddd($var)
{
    Tools::d($var);
}

/**
 * Sanitize data which will be injected into SQL query
 *
 * @param string $string SQL data which will be injected into SQL query
 * @param boolean $htmlOK Does data contain HTML code ? (optional)
 * @return string Sanitized data
 */
function pSQL($string, $htmlOK = false)
{
    // Avoid thousands of "Db::getInstance()"...
    static $db = false;
    if (!$db)
        $db = Db::getInstance();

    return $db->escape($string, $htmlOK);
}

function bqSQL($string)
{
    return str_replace('`', '\`', pSQL($string));
}

function displayFatalError()
{
    $error = null;
    if (function_exists('error_get_last'))
        $error = error_get_last();
    if ($error !== NULL && in_array($error['type'], array(E_ERROR, E_PARSE, E_COMPILE_ERROR )))
        echo 'Fatal error in  '.substr(basename($error['file']), 0, -4).':<br />'.$error['message'];
}

/**
 * @deprecated
 */
function nl2br2($string)
{
    return Tools::nl2br($string);
}