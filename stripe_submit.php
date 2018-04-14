<?php
require_once 'env-reader.php';
/**
 * Created by PhpStorm.
 * User: Kenneth
 * Date: 26/03/2018
 * Time: 14:36
 */

// Set your secret key: remember to change this to your live secret key in production
// See your keys here: https://dashboard.stripe.com/account/apikeys
require_once 'stripe-php/init.php';

\Stripe\Stripe::setApiKey( env( 'STRIPE_SECRET_KEY' ) );

$token = $_POST['stripeToken'];
$amount = $_POST['amount'];
$currency = $_POST['currency'];
// Charge the user's card:
$charge = \Stripe\Charge::create( array(
    'amount' => $amount,
    'currency' => $currency,
    'description' => 'Fuel Rats Donation',
    'source' => $token,
) );

?>
<h3>Thank you for your donation!</h3>
