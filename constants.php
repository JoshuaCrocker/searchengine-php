<?php

/*
 * PHP Search Engine Project
 *
 * Copyright (C) 2020 Joshua Crocker
 */

function crocker_log($text, $prefix = '*')
{
    echo '[' . $prefix . '] ' . $text . "\n";
}

function crocker_err($text)
{
    crocker_log($text, '!');
    die('Exited.');
}
