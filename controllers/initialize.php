<?php
session_start();
// Either pull data from a file or the web.
if (isset($_SESSION['source'])) {
    $json = file_get_contents('./tests/' . $_SESSION['source'] . '.txt');
} else {
    require "./helperFunctions/curlit.php";
    $json = curlIt("https://x-24.io/DevTestData.json");
}

// Process the json into "good" and "bad" data sets.
$people = new peopleObjectHandler($config, $json);
$sortField = 'LName';
$direction = 'desc';
// Sort both data sets by default setting (First name, descending order).
$people->sortPeople($sortField, $direction);

// Generate the table(s) and/or error messages.
$view = new viewTable($config, $people);
$view->generateHMTL($sortField);
$view->output_page();
