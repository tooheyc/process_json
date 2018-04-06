<?php
session_start();

if (isset($_POST['source']) && isset($_POST['column']) && isset($_POST['order']) ) {
    $sources = ['test1', 'test2', 'test3'];

    // Decide whether to load data from a file or pull it from the web.
    if (in_array($_POST['source'], $sources)) {
        $_SESSION['source'] = $_POST['source'];
    } elseif ($_POST['source'] = 'default') unset($_SESSION['source']);

    if (isset($_SESSION['source'])) {
        $json = file_get_contents('./tests/' . $_SESSION['source'] . '.txt');
    } else {
        require "./helperFunctions/curlit.php";
        $json = curlIt("https://x-24.io/DevTestData.json");
    }

    // Process the json into "good" and "bad" data sets.
    $people = new peopleObjectHandler($json);

    $col = str_replace('Too', '', $_POST['column']);
    $sortField = $col;
    $direction = $_POST['order'];
    // Sort both data sets by current setting..
    $people->sortPeople($sortField, $direction);

    // Generate the table(s) and/or error messages.
    $view = new viewTable($people);
    $view->generateHMTL($sortField, $direction);
    $view->output_page($col, $direction);

} else {
    // Redirect browser
    header("Location: ./");
    exit();
}