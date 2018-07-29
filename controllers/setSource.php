<?php
session_start();

if (isset($_POST['source']) && isset($_POST['column']) && isset($_POST['order'])) {
    $sources = ['test1', 'test2', 'test3'];

    // Decide whether to load data from a file or pull it from the web.
    if (in_array($_POST['source'], $sources)) {
        $_SESSION['source'] = $_POST['source'];
    } elseif ($_POST['source'] = 'default') unset($_SESSION['source']);

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

    $col = str_replace('Too', '', $_POST['column']);
    $sortField = $col;
    $direction = $_POST['order'];
    // Sort both data sets by current setting..
    $people->sortPeople($sortField, $direction);

    // Generate the table(s) and/or error messages.
    $view = new viewTable($config, $people);
    $view->generateHMTL($sortField, $direction);
    $view->output_page($col, $direction);

} else {
    // Redirect browser
    header("Location: ./");
    exit();
}