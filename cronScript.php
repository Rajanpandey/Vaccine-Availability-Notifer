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
        $body = 'Hi! <br/>We hope you and your loved ones are safe and well. <br/>Following is the list of centers where vaccines are available at your pincode:<br/>';
        $vaccineFound = 0;
        foreach($vaccineData->centers as $center) {
            $center_name = 'Center: '.$center->name.', Block: '.$center->block_name.', District: '.$center->district_name.' - '.$center->pincode.' From '.$center->from.' to '.$center->to.' ('.$center->fee_type.')';
            $sessionCount = 0;
            foreach($center->sessions as $session) {
                if ($p_id['age'] >= $session->min_age_limit && ($p_id['vaccine'] == 'Both' || strtolower($d_id['vaccine']) == strtolower($session->vaccine)) && $session->available_capacity > 0) {
                    $vaccineFound += $session->available_capacity;
                    $session_details .= $session->available_capacity.' '.$session->vaccine.' vaccines are available on '.$session->date.' for min age '.$session->min_age_limit.'<br/>';
                }
            }
            if ($sessionCount > 0) {
                $body .= '<br/><br/><b>'.$center_name.'</b><br/>'.$session_details;
            }
        }
        $body .= '<br/><br/><h3>For some reason if you could not book the slot, you can re-visit https://vaccinenotifier.azurewebsites.net/ to register yourself again for another availability reminder. :)</h3>';
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
        $body = 'Hi! <br/>We hope you and your loved ones are safe and well. <br/>Following is the list of centers where vaccines are available in your district:<br/>';
        $vaccineFound = 0;
        foreach($vaccineData->centers as $center) {
            $center_name = 'Center: '.$center->name.', Block: '.$center->block_name.', District: '.$center->district_name.' - '.$center->pincode.' From '.$center->from.' to '.$center->to.' ('.$center->fee_type.')';
            $sessionCount = 0;
            $session_details = '';
            foreach($center->sessions as $session) {
                if ($d_id['age'] >= $session->min_age_limit && ($d_id['vaccine'] == 'Both' || strtolower($d_id['vaccine']) == strtolower($session->vaccine)) && $session->available_capacity > 0) {
                    $sessionCount += 1;
                    $vaccineFound += $session->available_capacity;
                    $session_details .= $session->available_capacity.' '.$session->vaccine.' vaccines are available on '.$session->date.' for min age '.$session->min_age_limit.'<br/>';
                }
            }
            if ($sessionCount > 0) {
                $body .= '<br/><br/><b>'.$center_name.'</b><br/>'.$session_details;
            }
        }
        $body .= '<br/><br/><h3>For some reason if you could not book the slot, you can re-visit https://vaccinenotifier.azurewebsites.net/ to register yourself again for another availability reminder. :)</h3>';
        if ($vaccineFound) {
            sendMail($d_id['email'], "Vaccine is Available in your pincode", $body);
            mysqli_query($conn, "UPDATE notifybydistrict SET mailSent=1 WHERE d_id=$id");
        }
    }
}

mysqli_close($conn);
?>
