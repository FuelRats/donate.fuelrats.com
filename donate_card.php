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
<h2><div style="text-align: center;">Donate to The Fuel Rats</div></h2><br>
<div style="text-align: center;"><img src="fuelrats2.png"></div>
<div class="flex-box">
    <div class="selection">
          <?php render_donation($currency, '1'); ?>
</div>
        <div class="selection">
        <?php render_donation($currency, '5'); ?>
</div>
        <div class="selection">
        <?php render_donation($currency, '10'); ?>
</div>
	<div class="selection">
	  <h3>Donate</h3><br /><h1>Custom amount of <?php echo $selected_currency['Name']; ?>!</h1><br />
	  <p>Holy limpet! You sure like to live dangerously! Please enter a custom value that you want to donate. We are most grateful for everything you can give.</p>
	  <form action="stripe_submit.php" method="POST" id="custom_amount_form">
	    <input class="custom_amount" onkeyup="custom_amount_calculate();" placeholder="<?php echo $selected_currency['Symbol']; ?> Custom amount" type="number" name="custom_amount" />
	    <input id="custom_amount_value" type="hidden" name="amount" />
      <input type="hidden" name="currency" value="<?php echo $currency; ?>" />
	    <script
	      src="https://checkout.stripe.com/checkout.js" class="stripe-button"
	      data-key="<?php echo env( 'STRIPE_PUBLIC_KEY' ); ?>"
	      data-name="The Fuel Rats"
	      data-description="Custom amount donation"
	      data-image="https://donate.fuelrats.com/fuelrats.png"
	      data-locale="auto"
	      data-currency="<?php echo strtolower($currency); ?>">
	    </script>
    </div>
</div>
    <script type="text/javascript">
function custom_amount() {

  var custom_form = document.getElementById('custom_amount_form');
  var stripe_button = document.querySelector('#custom_amount_form button');
  var stripe_script = document.querySelector('#custom_amount_form script');
  custom_form.removeChild(stripe_button);
  custom_form.removeChild(stripe_script);

  var amount_box = document.querySelector('.custom_amount');
  var amount = amount_box.value;
  var amount_value = document.getElementById('custom_amount_value');
  amount_value.value = amount * 100;

  var sca = document.createElement('script');
  sca.src = 'https://checkout.stripe.com/checkout.js';
  sca.className = 'stripe-button';
  sca.dataset.key = '<?php echo env( 'STRIPE_PUBLIC_KEY' ); ?>';
  sca.dataset.name = 'The Fuel Rats';
  sca.dataset.image = 'https://donate.fuelrats.com/fuelrats.png';
  sca.dataset.locale = 'auto';
  sca.dataset.currency = '<?php echo strtolower($currency); ?>';
  sca.dataset.amount = amount * 100;
  sca.dataset.description = '<?php echo $selected_currency['Symbol']; ?>' + amount + ' Donation';

  amount_box.parentNode.insertBefore(sca, amount_box.nextSibling);
}

function custom_amount_calculate() {
  custom_amount();
}
</script>
</body>
</html>
