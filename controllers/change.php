<?php
session_start();

if (isset($_POST['column']) && isset($_POST['order']) && isset($_POST['json'])) {
    // Create new people handler from the json posted.
    $people = new peopleObjectHandler($config, $_POST['json']);

    // Sort data set by user inputs.
    $people->sortPeople($_POST['column'], $_POST['order']);

    // Generate the table rows and send them back as json.
    $view = new viewTable($config, $people);
    $view->output_json($_POST['type']);
} else {
    // Redirect browser
    header("Location: ./");
    exit();
}