<?php
require_once 'env-reader.php';
require_once 'functions.php';
/**
 * Created by PhpStorm.
 * User: Kenneth
 * Date: 26/03/2018
 * Time: 14:12
 */
$currency = $_REQUEST['currency'];

$validCurrency = array('EUR','GBP','USD');
if(!in_array($currency, $validCurrency)) {
    $currency = 'EUR';
}

$selected_currency = get_currency( $currency );

header("Content-type: text/html; charset=utf-8");
?>
<html>
<head>
<title>The Fuel Rats Mischief - Donations</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="fuelrats.css" type="text/css" rel="stylesheet" />
</head>
<body>
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
  var stripe = Stripe('<?php echo env('STRIPE_PUBLIC_KEY'); ?>');
</script>
<h2><div style="text-align: center;">Donate to The Fuel Rats</div></h2><br>
<div style="text-align: center;"><img src="fuelrats2.png"></div>
<p>Thank you for considering donating to The Fuel Rats. As simple as our jobs are in the end, we actually have a lot of
systems in place to help us do our rescues, and the servers they run on amount to over €100 per month. We've got rats
who have fronted the money to keep these servers running, but if you'd like to contribute, that would be great!</p>
<p>Our donations are processed through Stripe, and they take most major credit cards.</p>
<div class="flex-box">
    <div class="selection">
          <?php render_paymentrequest($currency, '1'); ?>
    </div>
    <div class="selection">
        <?php render_paymentrequest($currency, '5'); ?>
    </div>
    <div class="selection">
        <?php render_paymentrequest($currency, '10'); ?>
    </div>
</div>
<form action="stripe_submit.php" method="post" id="sub_token">
<input type="hidden" name="currency" id="mob_currency" value="<?php echo strtolower($currency); ?>" />
<input type="hidden" name="amount" id="mob_amount" value="0" />
<input type="hidden" name="stripeToken" id="mob_token" value="" />
</form>
</body>
</html>
