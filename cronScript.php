<!DOCTYPE html>
<html>
<head>
    <title>Cron Script</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript">
        function call_API(url, age, vaccineRequirement, email, id, requestType) {
            $.ajax({
                type:"POST",
                url:"fetchSlots.php",
                data:{url: url},
                success: function(vaccineData) {
                    $.ajax({
                        type:"POST",
                        url:"prepareAndSendMail.php",
                        data: {vaccineData: vaccineData, age: age, vaccineRequirement: vaccineRequirement, email: email, id: id, requestType: requestType},
                        success: function(data) {}
                    });
                }
            });
        }
    </script>
</head>
</html>


<?php
include('mailHandler.php');
include('connect.php');

function getRequests($requestType) {
    include('connect.php');
    $today = date('d-m-Y');

    $query = '';
    if ($requestType == 'pin') {
        $query = "SELECT * FROM notifybypin WHERE mailSent=0";
    } else {
        $query = "SELECT * FROM notifybydistrict WHERE mailSent=0";
    }
    $result = mysqli_query($conn, $query);

    $requests = array();
    while($row = mysqli_fetch_assoc($result)) {
        // Discard requests that are more than 1 week old
        if (date($row['date']) - $today > -7) {
            $requests[] = $row;
        }
    }
    return $requests;
}

// function callAPI($url) {
//     // The Co-WIN Public APIs are subject to a rate limit of 100 API calls per 5 minutes per IP.
//     // Sleeping 4s before every API call makes sure that only 75 API calls take place every 5 mins.
//     sleep(4);
//     $curl = curl_init($url);
//     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($curl, CURLOPT_HTTPHEADER, [
//       'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36'
//     ]);
//     $response = curl_exec($curl);
//     curl_close($curl);
//     return json_decode($response);
// }

// function prepareBodyAndSendMail($vaccineData, $requester, $requestType) {
//     include('connect.php');
//     if (count($vaccineData->centers) > 0) {
//         $body = 'Hi! <br/>We hope you and your loved ones are safe and well. <br/>Following is the list of centers where vaccines are available at your pincode:<br/>';
//         $vaccineFound = 0;

//         foreach($vaccineData->centers as $center) {
//             $centerName = 'Center: '.$center->name.', Block: '.$center->block_name.', District: '.$center->district_name.' - '.$center->pincode.' From '.$center->from.' to '.$center->to.' ('.$center->fee_type.')';
//             $sessionCount = 0;
//             $sessionDetails = '';
//             foreach($center->sessions as $session) {
//                 if ($requester['age'] >= $session->min_age_limit && ($requester['vaccine'] == 'Any' || strtolower($requester['vaccine']) == strtolower($session->vaccine)) && $session->available_capacity > 0) {
//                     $sessionCount += 1;
//                     $vaccineFound += $session->available_capacity;
//                     $sessionDetails .= $session->available_capacity.' '.$session->vaccine.' vaccines are available on '.$session->date.' for min age '.$session->min_age_limit.'<br/>';
//                 }
//             }
//             if ($sessionCount > 0) {
//                 $body .= '<br/><br/><b>'.$centerName.'</b><br/>'.$sessionDetails;
//             }
//         }

//         $body .= '<br/><br/><h3>For some reason if you could not book the slot, you can re-visit https://vaccinenotifier.azurewebsites.net/ to register yourself again for another availability reminder. :)</h3>';
//         if ($vaccineFound) {
//             sendMail($requester['email'], "Vaccine is Available in your pincode", $body);
//             if ($requestType == 'pin') {
//                 $id = $requester['p_id'];
//                 mysqli_query($conn, "UPDATE notifybypin SET mailSent=1 WHERE p_id=$id");
//             } else {
//                 $id = $requester['d_id'];
//                 mysqli_query($conn, "UPDATE notifybydistrict SET mailSent=1 WHERE d_id=$id");
//             }
//         }
//     }
// }

// Main:
$notifyByPinRequests = getRequests('pin');
foreach($notifyByPinRequests as $notifyByPinRequest) {
    $age = $notifyByPinRequest['age'];
    $vaccineRequirement = $notifyByPinRequest['vaccine'];
    $email = $notifyByPinRequest['email'];
    $id = $notifyByPinRequest['p_id'];
    $url = 'https://cdn-api.co-vin.in/api/v2/appointment/sessions/public/calendarByPin?pincode='.$notifyByPinRequest['pincode'].'&date='.$notifyByPinRequest['date'];
    echo "<script type='text/javascript'> call_API('$url', '$age', '$vaccineRequirement', '$email', '$id', 'pin'); </script>";
    // $vaccineData = callAPI($url);
    // prepareBodyAndSendMail($vaccineData, $notifyByPinRequest, 'pin');
}

$notifyByDistrictRequests = getRequests('district');
foreach($notifyByDistrictRequests as $notifyByDistrictRequest) {
    $age = $notifyByDistrictRequest['age'];
    $vaccineRequirement = $notifyByDistrictRequest['vaccine'];
    $email = $notifyByDistrictRequest['email'];
    $id = $notifyByDistrictRequest['d_id'];
    $url = 'https://cdn-api.co-vin.in/api/v2/appointment/sessions/public/calendarByDistrict?district_id='.$notifyByDistrictRequest['district'].'&date='.$notifyByDistrictRequest['date'];
    echo "<script type='text/javascript'> call_API('$url', '$age', '$vaccineRequirement', '$email', '$id', 'district'); </script>";
    // $vaccineData = callAPI($url);
    // prepareBodyAndSendMail($vaccineData, $notifyByDistrictRequest, 'district');
}

mysqli_close($conn);
?>
