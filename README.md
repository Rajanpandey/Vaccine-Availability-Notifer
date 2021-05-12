# Vaccine Availability Notifier

A web app where people can register to get email notification whenever Covid-19 vaccine is available in their locality.
It uses Co-WIN API from API Setu to find the availability of the vaccine.

# Website link
I have hosted the site on Azure on my own subscription: https://vaccinenotifier.azurewebsites.net/

# How it works
A cron job is run twice a day, and if any vaccine is available for the pincode/district, then an email is sent to the requester.

# How run the project (2 Methods):

## 1. With XAMPP:
1. Clone the repo: `https://github.com/Rajanpandey/Vaccine-Availability-Notifer.git`
2. Install `XAMPP` software
3. Cut-Paste the repo into the `htdocs` folder of `xampp`, so it looks like this `{xampp-installation-folder}/htdocs/{repo}`. Eg, for me it is: `C:/xampp/htdocs/Vaccine-Availability-Notifer`
4. Run `XAMPP` and start `Apache` and `MySQL` server
5. Visit `http://localhost/phpmyadmin/`, click on Import from the upper tabs, and select `data_dump.sql` file to generate the db and tables.
6. Visit `http://localhost/Vaccine-Availability-Notifer/` to run the app!
7. Schedule a cron job to run `cronScript.php` or run it manually to send mail. (Note: You need to change the file change `config_example.php` to `config.php` and update the email and password after allowing 'Less secure apps' on Gmail for that email).

## 2. With Docker:
1. Clone the repo: `https://github.com/Rajanpandey/Vaccine-Availability-Notifer.git`
2. Install `Docker` software
3. Go into the `docker` folder and run `docker-compose up -d` to start docker
4. Open `connect.php` and uncomment the `$conn` config for docker
5. Visit `http://localhost/` to open the site and `http://localhost:8080/` to open the PHPMyAdmin (MySQL DB)
6. Schedule a cron job to run `cronScript.php` or run it manually to send mail. (Note: You need to change the file change `config_example.php` to `config.php` and update the email and password after allowing 'Less secure apps' on Gmail for that email).
7. Run `docker-compose down -v` to shut down the docker service.
