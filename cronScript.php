<?php
require('connect.php');
include('mailHandler.php');

$query = "SELECT * FROM notifybypin WHERE mailSent=0";
$result = mysqli_query($conn, $query);
$notifybypin = array();
while($row = mysqli_fetch_assoc($result)) {
    $notifybypin[] = $row;
}

foreach($notifybypin as $p_id) {
    $id = $p_id['p_id'];
    $vaccineData = json_decode(file_get_contents('https://cdn-api.co-vin.in/api/v2/appointment/sessions/public/calendarByPin?pincode='.$p_id['pincode'].'&date='.$p_id['date']));
    if (count($vaccineData->centers) > 0) {
        $body = '';
        $vaccineFound = 0;
        foreach($vaccineData->centers as $center) {
            $center_name = 'Center: '.$center->name.', Block: '.$center->name.', District: '.$center->district_name.' - '.$center->pincode.' From '.$center->from.' to '.$center->to.' ('.$center->fee_type.')';
            $body .= '<br/><br/><b>'.$center_name.'</b><br/>';
            foreach($center->sessions as $session) {
                if ($session->available_capacity > 0) {
                    $vaccineFound += $session->available_capacity;
                    $session_details = $session->available_capacity.' '.$session->vaccine.' vaccines are available on '.$session->date.' for min age '.$session->min_age_limit;
                    $body .= $session_details.'<br/>';
                }
            }
        }
        if ($vaccineFound) {
            sendMail($p_id['email'], "Vaccine is Available in your pincode", $body);
            mysqli_query($conn, "UPDATE notifybypin SET mailSent=1 WHERE p_id=$id");
        }
    }
}

$query = "SELECT * FROM notifybydistrict WHERE mailSent=0";
$result = mysqli_query($conn, $query);
$notifybydistrict = array();
while($row = mysqli_fetch_assoc($result)) {
    $notifybydistrict[] = $row;
}

foreach($notifybydistrict as $d_id) {
    $id = $d_id['d_id'];
    $vaccineData = json_decode(file_get_contents('https://cdn-api.co-vin.in/api/v2/appointment/sessions/public/calendarByDistrict?district_id='.$d_id['district'].'&date='.$d_id['date']));
    if (count($vaccineData->centers) > 0) {
        $body = '';
        $vaccineFound = 0;
        foreach($vaccineData->centers as $center) {
            $center_name = 'Center: '.$center->name.', Block: '.$center->name.', District: '.$center->district_name.' - '.$center->pincode.' From '.$center->from.' to '.$center->to.' ('.$center->fee_type.')';
            $body .= '<br/><br/><b>'.$center_name.'</b><br/>';
            foreach($center->sessions as $session) {
                if ($session->available_capacity > 0) {
                    $vaccineFound += $session->available_capacity;
                    $session_details = $session->available_capacity.' '.$session->vaccine.' vaccines are available on '.$session->date.' for min age '.$session->min_age_limit;
                    $body .= $session_details.'<br/>';
                }
            }
        }
        if ($vaccineFound) {
            sendMail($d_id['email'], "Vaccine is Available in your pincode", $body);
            mysqli_query($conn, "UPDATE notifybydistrict SET mailSent=1 WHERE d_id=$id");
        }
    }
}

mysqli_close($conn);
?>
