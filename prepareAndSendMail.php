<?php
    include('mailHandler.php');
    include('connect.php');

    $vaccineData = $_POST['vaccineData'];
    $age = $_POST['age'];
    $vaccineRequirement = $_POST['vaccineRequirement'];
    $email = $_POST['email'];
    $id = $_POST['id'];
    $requestType = $_POST['requestType'];

    $vaccineData = json_decode($vaccineData);
    if (count($vaccineData->centers) > 0) {
        $body = 'Hi! <br/>We hope you and your loved ones are safe and well. <br/>Following is the list of centers where vaccines are available at your pincode/district:<br/>';
        $vaccineFound = 0;

        foreach($vaccineData->centers as $center) {
            $centerName = 'Center: '.$center->name.', Block: '.$center->block_name.', District: '.$center->district_name.' - '.$center->pincode.' From '.$center->from.' to '.$center->to.' ('.$center->fee_type.')';
            $sessionCount = 0;
            $sessionDetails = '';
            foreach($center->sessions as $session) {
                if ($age >= $session->min_age_limit && ($vaccineRequirement == 'Both' || strtolower($vaccineRequirement) == strtolower($session->vaccine)) && $session->available_capacity > 0) {
                    $sessionCount += 1;
                    $vaccineFound += $session->available_capacity;
                    $sessionDetails .= $session->available_capacity.' '.$session->vaccine.' vaccines are available on '.$session->date.' for min age '.$session->min_age_limit.'<br/>';
                }
            }
            if ($sessionCount > 0) {
                $body .= '<br/><br/><b>'.$centerName.'</b><br/>'.$sessionDetails;
            }
        }

        $body .= '<br/><br/><h3>For some reason if you could not book the slot, you can re-visit https://vaccinenotifier.azurewebsites.net/ to register yourself again for another availability reminder. :)</h3>';
        if ($vaccineFound) {
            if ($requestType == 'pin') {
                sendMail($email, "Vaccine is Available in your pincode", $body);
                mysqli_query($conn, "UPDATE notifybypin SET mailSent=1 WHERE p_id=$id");
            } else {
                sendMail($email, "Vaccine is Available in your district", $body);
                mysqli_query($conn, "UPDATE notifybydistrict SET mailSent=1 WHERE d_id=$id");
            }
        }
    }
?>
