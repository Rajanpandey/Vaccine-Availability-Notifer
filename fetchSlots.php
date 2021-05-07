<?php
    // The Co-WIN Public APIs are subject to a rate limit of 100 API calls per 5 minutes per IP.
    // Modern browsers can send max 6 concurrent requests at the same time.
    // Sleeping 20s (5 minutes / 18s * 6 API calls = 100 API Calls) before every API call makes sure that only 90 API calls take place every 5 mins.
    sleep(20);
    $url = $_POST['url'];
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
      'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36'
    ]);
    $response = curl_exec($curl);
    curl_close($curl);
    echo $response;
?>
