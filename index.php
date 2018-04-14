<?php 
header("Content-type: text/html; charset=utf-8");
include_once 'functions.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Donation - The Fuel Rats Mischief</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="fuelrats.css" type="text/css" rel="stylesheet" />
    </head>
<body>
    <h2><div style="text-align: center;">Donate to The Fuel Rats</div></h2><br>
    <div style="text-align: center;"><img class="logotype" src="fuelrats2.png"></div>
    <div class="center">Please select your wished currency and way to donate.</div>
    <hr />
    <form action="donate_gateway.php" method="post">
    <div class="center">
        <select required name="currency">
            <option value="">Select currency</option>
            <option value="EUR">Euro €</option>
            <option value="USD">US Dollar $</option>
            <option value="GBP">Great British Pounds £</option>
        </select>
    </div>
    <div class="flex-box">
        <div class="selection">
            <label>
                <h3>Donate with Card</h3>
                <input type="radio" name="donation_type" value="card" />
                I want to donate with my credit card!
            </label>
        </div>
        <div class="selection">
            <label>
                <h3>Donate with Apple/Google Pay</h3>
                <input type="radio" name="donation_type" value="aplgogl" />
                I want to donate with Apple / Google Pay!
            </label>
        </div>
        <div class="selection">
            <label>
                <h3>Donate with Giropay (Only EUR)</h3>
                <input type="radio" name="donation_type" value="giro" />
                I want to donate with Giropay!
            </label>
        </div>
    </div>
    <hr />
    <div class="center">
        <input type="submit" value="Proceed to donation page" />
    </div>
</body>
</html>