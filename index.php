<?php
require('connect.php');

if(isset($_POST['notifyByPin'])) {
    $pincode = mysqli_real_escape_string($conn, trim($_POST['pincode']));
    $vaccine = mysqli_real_escape_string($conn, trim($_POST['vaccine']));
    $age = mysqli_real_escape_string($conn, trim($_POST['age']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $date = mysqli_real_escape_string($conn, trim($_POST['date']));
    $date = date_format(date_create($date),"d-m-Y");

    $sql = "INSERT INTO notifybypin (pincode, email, vaccine, age, date, mailSent) VALUES ('$pincode', '$email', '$vaccine', '$age', '$date', 0)";

    if(mysqli_query($conn, $sql)) {
        echo "<script type=\"text/javascript\"> alert('You are registered! We will notify you as the vaccine in your pincode becomes available!'); location.href = 'index.php'; </script>";
    } else {
        echo "Error: <br>" . mysqli_error($conn);
    }
}

if(isset($_POST['notifyByDistrict'])) {
    $district = mysqli_real_escape_string($conn, trim($_POST['district']));
    $vaccine = mysqli_real_escape_string($conn, trim($_POST['vaccine']));
    $age = mysqli_real_escape_string($conn, trim($_POST['age']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $date = mysqli_real_escape_string($conn, trim($_POST['date']));
    $date = date_format(date_create($date),"d-m-Y");

    $sql = "INSERT INTO notifybydistrict (district, email, vaccine, age, date, mailSent) VALUES ('$district', '$email', '$vaccine', '$age', '$date', 0)";

    if(mysqli_query($conn, $sql)) {
        echo "<script type=\"text/javascript\"> alert('You are registered! We will notify you as the vaccine in your distict becomes available!'); location.href = 'index.php'; </script>";
    } else {
        echo "Error: <br>" . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vaccine Notifier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="row pt-5">
            <h2 class="pb-4 text-center text-primary">Welcome to Vaccine Availability Notifier</h2>
            <div class="col-6">
                <h5 class="text-center text-success">How it works:</h5>
                <p>1. Register your email to get notified about vaccine availability in your district or pin (or both!) for the whole week from the selected date.</p>
                <p>2. For some reason if you couldn't book the slot, <b>you can visit the site again and register yourself for another availability reminder</b>.</p>
                <p>3. Finding by pincode is the best for finding nearest center. Finding centers by District may contain far off locations.</p>
            </div>
            <div class="col-6">
                <h5 class="text-center text-danger">Data disclaimer and privacy policy:</h5>
                <p>1. We do not sell your data in any way whatsoever.</p>
                <p>2. Due to the new API regulations (rate limit of 100 API calls/5 mins/IP address, and data caching resulting in stale data by 30 mins) introduced by Co-WIN on 5th May (<a href="https://apisetu.gov.in/public/marketplace/api/cowin/cowin-public-v2#/">as written here</a>), real-time notifications service isn't possible. We highly recommend checking the <a href="https://www.cowin.gov.in/">official Co-WIN website</a> from time to time, while having the email notifier as a backup.</p>
                <p>3. Open source code can be found <a href="https://github.com/Rajanpandey/Vaccine-Availability-Notifer">here</a>.</p>
            </div>
            <hr/>
            <div class="col-12 col-xl-6 pt-4">
                <h3 class="ml-3">Notify Vaccine by PinCode:</h3>
                <form action="" method="POST" class="form-inline">
                    <div class="form-group">
                        <br/>
                        <label for="pincode" class="ml-3"><b>Pincode:</b></label>
                        <input id="pincode" type="text" pattern="[0-9]{6}" maxlength="6" class="form-control ml-2" name="pincode" placeholder="Enter your 6 digit pincode" required>

                        <label for="vaccine" class="ml-3"><b>Select a Vaccine whose reminder you want:</b></label>
                        <select class="form-select" name="vaccine" aria-label="Vaccine" required>
                            <option value="Both">Both</option>
                            <option value="COVISHIELD">Covishield</option>
                            <option value="COVAXIN">Covaxin</option>
                        </select>

                        <label for="age" class="ml-3"><b>Minimum Age Limit for the slot:</b></label>
                        <select class="form-select" name="age" aria-label="Vaccine" required>
                            <option value="45">45 (age 45 and above only)</option>
                            <option value="18">18 (age 18 and above)</option>
                        </select>

                        <label for="email" class="ml-3"><b>Email:</b></label>
                        <input id="email" type="email" class="form-control ml-2" name="email" placeholder="Enter your email address" required>

                        <label for="date" class="ml-3"><b>Select a date (You will receive an email if the vaccine is available in that week):</b></label>
                        <input type="date" class="form-control ml-2" name="date" required value="">

                        <br/>
                        <button type="submit" name="notifyByPin" class="btn btn-primary ml-3">Submit</button>
                    </div>
                </form>
            </div>
            <div class="col-12 col-xl-6 pt-4">
                <h3 class="ml-3">Notify Vaccine by District:</h3>
                <form action="" method="POST" class="form-inline">
                    <div class="form-group">
                        <br/>
                        <label for="state" class="ml-3"><b>State:</b></label>
                        <select class="form-select" name="state" aria-label="State" id="stateDropdown" required onchange="updateCity()">
                            <option value="">Select your State</option>
                        </select>

                        <label for="district" class="ml-3"><b>District:</b></label>
                        <select class="form-select" name="district" aria-label="City" id="cityDropdown" required>
                            <option value="">Select your District</option>
                        </select>

                        <label for="vaccine" class="ml-3"><b>Select a Vaccine whose reminder you want:</b></label>
                        <select class="form-select" name="vaccine" aria-label="Vaccine" required>
                            <option value="Both">Both</option>
                            <option value="COVISHIELD">Covishield</option>
                            <option value="COVAXIN">Covaxin</option>
                        </select>

                        <label for="age" class="ml-3"><b>Minimum Age Limit for the slot:</b></label>
                        <select class="form-select" name="age" aria-label="Vaccine" required>
                            <option value="45">45 (age 45 and above only)</option>
                            <option value="18">18 (age 18 and above)</option>
                        </select>

                        <label for="email" class="ml-3"><b>Email:</b></label>
                        <input id="email" type="email" class="form-control ml-2" name="email" placeholder="Enter your email address" required>

                        <label for="date" class="ml-3"><b>Select a date (You will receive an email if the vaccine is available in that week):</b></label>
                        <input type="date" class="form-control ml-2" name="date" required value="">

                        <br/>
                        <button type="submit" name="notifyByDistrict" class="btn btn-primary ml-3">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src = "stateAndCity.js"/></script>
    <script type="text/javascript">
        $(document).ready(function () {
            Object.keys(stateAndCity).forEach(function(state) {
                $('#stateDropdown').append('<option value="' + state + '">' + state + '</option>');
            });
        });

        function updateCity () {
            var stateSelected = document.getElementById("stateDropdown").value;
            for(idx in stateAndCity[stateSelected]) {
                $('#cityDropdown').append('<option value="' + stateAndCity[stateSelected][idx]['district_id'] + '">' + stateAndCity[stateSelected][idx]['district_name'] + '</option>');
            }
        }
    </script>
</body>
</html>
