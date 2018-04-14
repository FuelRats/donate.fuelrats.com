<?php
require_once 'env-reader.php';
/**
 * Created by PhpStorm.
 * User: Kenneth
 * Date: 26/03/2018
 * Time: 14:12
 */
$currency = 'GBP';
function render_donation($currency, $tier) {
    $currencies = array(
        'EUR' => array(
            'Name' => 'Euro',
            'Symbol' => '€',
            'Tier' => array(
                '1' => array(
                    'Sum' => 100,
                    'Text' => 'Every little bit helps! If half of our rescued commanders were to donate one Euro, we would easily cover our server costs each month.',
                ),
                '5' => array(
                    'Sum' => 500,
                    'Text' => 'Thank you so much! A €5 donation will go a long way towards covering our running costs. o7, CMDR!',
                ),
                '10' => array(
                    'Sum' => 1000,
                    'Text' => 'Wow! Ten euros is a major donation for our sake; it offsets the costs of running our servers for three whole days! You\'re awesome!',
                )
            ),
        ),
        'GBP' => array(
            'Name' => 'Great British Pound',
            'Symbol' => '£',
            'Tier' => array(
                '1' => array(
                    'Sum' => 100,
                    'Text' => 'Every little bit helps! If half of our rescued commanders were to donate one Great British Pound, we would easily cover our server costs each month.',
                ),
                '5' => array(
                    'Sum' => 500,
                    'Text' => 'Thank you so much! A £5 donation will go a long way towards covering our running costs. o7, CMDR!',
                ),
                '10' => array(
                    'Sum' => 1000,
                    'Text' => 'Wow! Ten Great British Pounds is a major donation for our sake; it offsets the costs of running our servers for three whole days! You\'re awesome!',
                )
            ),
        ),
        'USD' => array(
            'Name' => 'US Dollar',
            'Symbol' => '$',
            'Tier' => array(
                '1' => array(
                    'Sum' => 100,
                    'Text' => 'Every little bit helps! If half of our rescued commanders were to donate one US Dollar, we would easily cover our server costs each month.',
                ),
                '5' => array(
                    'Sum' => 500,
                    'Text' => 'Thank you so much! A $5 donation will go a long way towards covering our running costs. o7, CMDR!',
                ),
                '10' => array(
                    'Sum' => 1000,
                    'Text' => 'Wow! Ten US Dollars is a major donation for our sake; it offsets the costs of running our servers for three whole days! You\'re awesome!',
                )
            ),
        ),
    );

    switch($currency) {
        case 'USD':
        $selected_currency = $currencies['USD'];
        break;
        case 'GBP':
        $selected_currency = $currencies['GBP'];
        break;
        default:
        $selected_currency = $currencies['EUR'];
        break;
    }
    ?>
    <h3>Donate</h3><br> <h1><?php echo $selected_currency['Symbol'] . $tier; ?></h1><br>
            <p><?php echo $selected_currency['Tier'][$tier]['Text']; ?></p>
            <form action="stripe_submit.php" method="POST">
                <script
                        src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                        data-key="<?php echo env( 'STRIPE_PUBLIC_KEY' ); ?>"
                        data-amount="<?php echo $selected_currency['Tier'][$tier]['Sum']; ?>"
                        data-name="The Fuel Rats"
                        data-description="<?php echo $selected_currency['Symbol'] . $tier; ?> Donation"
                        data-image="https://donate.fuelrats.com/fuelrats.png"
                        data-locale="auto"
                        data-currency="<?php echo strtolower($currency); ?>">
                </script>
                <input type="hidden" name="amount" value="<?php echo $selected_currency['Tier'][$tier]['Sum']; ?>">
            </form>
    <?php
}

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
<p>Thank you for considering donating to The Fuel Rats. As simple as our jobs are in the end, we actually have a lot of
systems in place to help us do our rescues, and the servers they run on amount to over €100 per month. We've got rats
who have fronted the money to keep these servers running, but if you'd like to contribute, that would be great!</p>
<p>Our donations are processed through Stripe, and they take most major credit cards.</p>
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
	  <h3>Donate</h3><br /><h1>Custom amount!</h1><br />
	  <p>Holy limpet! You sure like to live dangerously! Please enter a custom value that you want to donate. We are most grateful for everything you can give.</p>
	  <form action="stripe_submit.php" method="POST" id="custom_amount_form">
	    <input class="custom_amount" onkeyup="custom_amount_calculate();" placeholder="€ Custom amount" type="number" name="custom_amount" />
	    <input id="custom_amount_value" type="hidden" name="amount" />
	    <script
	      src="https://checkout.stripe.com/checkout.js" class="stripe-button"
	      data-key="<?php echo env( 'STRIPE_PUBLIC_KEY' ); ?>"
	      data-name="The Fuel Rats"
	      data-description="Custom amount donation"
	      data-image="https://donate.fuelrats.com/fuelrats.png"
	      data-locale="auto"
	      data-currency="eur">
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
  sca.dataset.currency = 'eur';
  sca.dataset.amount = amount * 100;
  sca.dataset.description = '€' + amount + ' Donation';

  amount_box.parentNode.insertBefore(sca, amount_box.nextSibling);
}

function custom_amount_calculate() {
  custom_amount();
}
</script>
</body>
</html>
