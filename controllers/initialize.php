<?php
session_start();
// Either pull data from a file or the web.
if (isset($_SESSION['source'])) {
    $json = file_get_contents('./tests/' . $_SESSION['source'] . '.txt');
} else {
//        $url = 'http://some.data.source';
//        require "./helperFunctions/curlit.php";
//        $json = curlIt($url);
    $json = '[{"FName":"Bob","LName":"Rasputin","Age":"25"},
{"FName":"Larry","LName":"polanski","Age":"25"},
{"FName":"Max","LName":"Xzavier","Age":"25"},
{"FName":"Steve","LName":"filman","Age":"25"},
{"FName":"Thomas","LName":"Govenator","Age":"25"}]';
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
