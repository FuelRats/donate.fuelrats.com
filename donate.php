<?php
header("Content-type: text/html; charset=utf-8");
require_once 'env-reader.php';
require_once 'functions.php';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Donation - The Fuel Rats Mischief</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="fuelrats.css?t=<?php echo time(); ?>" type="text/css" rel="stylesheet"/>
</head>
<body>
<div class="page">
<h2>
    <div style="text-align: center;">Donate to The Fuel Rats</div>
</h2>
<br>
<div style="text-align: center;"><img class="logotype" src="fuelrats2.png"></div>
<div class="center"><p>Thank you for considering donating to The Fuel Rats. As simple as our jobs are in the end, we
        actually have a lot of
        systems in place to help us do our rescues, and the servers they run on amount to over €100 per month. We've got
        rats
        who have fronted the money to keep these servers running, but if you'd like to contribute, that would be
        great!</p>
    <p>Our donations are processed through Stripe, and they take most major credit cards.</p>
</div>
<div class="center">Please select your wished currency and way to donate.</div>
<hr/>
<form action="donate_gateway.php" method="post">
    <div class="center">
        <select required name="currency">
            <option value="">Select currency</option>
            <option value="EUR">Euro €</option>
            <option value="USD">US Dollar $</option>
            <option value="GBP">British Pounds £</option>
        </select>
    </div>
    <div class="center">
        <select required name="amount">
            <option value="">Select amount</option>
            <option value="1">1</option>
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="custom">Custom (enter in field below)</option>
        </select>
    </div>
    <div class="center">
        <input type="number" name="custom_amount" placeholder="Custom amount" />
    </div>
    <div class="flex-box">
        <?php /*<div class="selection">
            <label>
                <h3>Donate with Card</h3>
                <input type="radio" name="donation_type" value="card"/>
                I want to donate with my credit card!
            </label>
        </div> */ ?>
        <?php /*<div class="selection">
            <label>
                <h3>Donate with Apple/Google Pay</h3>
                <input type="radio" name="donation_type" value="aplgogl"/>
                I want to donate with Apple / Google Pay!
            </label>
        </div>*/ ?>
        <?php /*<div class="selection">
            <label>
                <h3>Donate with Giropay (Only EUR)</h3>
                <input type="radio" name="donation_type" value="giro" />
                I want to donate with Giropay!
            </label>
        </div>*/ ?>

    </div>
    <hr/>
    <div class="center">
        <div class="g-recaptcha" data-sitekey="<?php echo env('RECAPTCHA_SITE_KEY'); ?>" style="display: inline-block;"></div>
        <br/>
        <input type="hidden" name="donation_type" value="card" />
        <input type="hidden" name="csrf-protec-not-attac" value="<?php echo get_csrf_token(); ?>" />
        <input type="submit" value="Proceed to donation page"/>
    </div>
</form>
</div>
</body>
</html>