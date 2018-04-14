<?php
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