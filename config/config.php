<?php

$minAge = 0;
$maxAge = 130;
$maxLen = 100;

$config = [
    'minAge' => $minAge,
    'maxAge' => $maxAge,
    'maxLen' => $maxLen,

    'filter' => ['FName', 'LName', 'Age'],

    'messages' => [
        'Age' => [
            'notNum' => 'Age is not numeric. ',
            'young' => 'Age is less than '.$minAge.'. ',
            'old' => 'Age is greater than '.$maxAge.'. '
        ],
        'long' => ' is longer than '.$maxLen.'. ',
        'empty' => ' is empty. '
    ],

    'tableHeads' => [
        'FName' => 'First Name',
        'LName' => "Last Name",
        'Age' => 'Age'
    ],

    'options' => [
        'default' => 'Initial source',
        'test1' => 'Test case 1',
        'test2' => 'Test case 2',
        'test3' => 'Test case 3'],

];
