<?php
require_once 'env-reader.php';

function debug_write( $obj ) {
    ob_start();
    var_dump( $obj );
    echo '<xmp>' . ob_get_clean() . '</xmp>';
}

function get_rates() {
    if( file_exists( 'conversion_cache.json' ) && filemtime( 'conversion_cache.json' ) > ( time() - 3600 ) ) {
        return json_decode( file_get_contents( 'conversion_cache.json' ), true );
    }

    $content = file_get_contents( 'https://openexchangerates.org/api/latest.json?app_id=' . env( 'OPEN_EXCHANGE_RATES_KEY' ) . '&base=EUR' );
    file_put_contents( 'conversion_cache.json', $content );

    return json_decode( $content, true );
}

function get_currency($currency) {
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

    return $selected_currency;
}

function render_donation($currency, $tier) {
    $selected_currency = get_currency( $currency );
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
                <input type="hidden" name="amount" value="<?php echo $selected_currency['Tier'][$tier]['Sum']; ?>" />
                <input type="hidden" name="currency" value="<?php echo $currency; ?>" />
            </form>
    <?php
}

function render_paymentrequest($currency, $tier) {
    $selected_currency = get_currency( $currency );
    ?>
    <h3>Donate</h3><br> <h1><?php echo $selected_currency['Symbol'] . $tier; ?></h1><br>
            <p><?php echo $selected_currency['Tier'][$tier]['Text']; ?></p>
            <div class="donation_<?php echo $tier; ?>"></div>
    <script type="text/javascript">
    var pr<?php echo $tier; ?> = stripe.paymentRequest({
        country: 'NO',
        currency: '<?php echo strtolower($currency); ?>',
        total: {
            label: '<?php echo $selected_currency['Symbol'] . $tier; ?> Donation',
            amount: <?php echo $selected_currency['Tier'][$tier]['Sum']; ?>,
        }
    });

    var elements<?php echo $tier; ?> = stripe.elements();
    var prButton<?php echo $tier; ?> = elements<?php echo $tier; ?>.create('paymentRequestButton', {
    paymentRequest: pr<?php echo $tier; ?>,
    });

    // Check the availability of the Payment Request API first.
    pr<?php echo $tier; ?>.canMakePayment().then(function(result) {
    if (result) {
        prButton<?php echo $tier; ?>.mount('.donation_<?php echo $tier; ?>');
    } else {
        document.querySelector('.donation_<?php echo $tier; ?>').style.display = 'none';
    }
    });

    pr<?php echo $tier; ?>.on('token', function(ev) {

        var amount = <?php echo $selected_currency['Tier'][$tier]['Sum']; ?>;
        var token = ev.token.id;

        document.getElementById('mob_amount').value = amount;
        document.getElementById('mob_token').value = token;
        ev.complete('success');
        document.getElementById('sub_token').submit();
  // Send the token to your server to charge it!
  /*fetch('/charges', {
    method: 'POST',
    body: JSON.stringify({token: ev.token.id}),
    headers: {'content-type': 'application/json'},
  })
  .then(function(response) {
    if (response.ok) {
      // Report to the browser that the payment was successful, prompting
      // it to close the browser payment interface.
      ev.complete('success');
    } else {
      // Report to the browser that the payment failed, prompting it to
      // re-show the payment interface, or show an error message and close
      // the payment interface.
      ev.complete('fail');
    }
  });*/
});
    </script>
    <?php
}