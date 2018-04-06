<?php
// Function to retrieve data from another server.
function curlIt($url)
{
    $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_URL => $url,
    );
    $ch = curl_init();
    curl_setopt_array($ch, $options);
    return curl_exec($ch);
}
