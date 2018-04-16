<?php
include_once 'env-reader.php';
include_once 'functions.php';

$donation_type = $_REQUEST['donation_type'];

$valid_types = array( 'card', 'aplgogl', 'giro' );
if( ! in_array( $donation_type, $valid_types ) ) {
    $donation_type = 'card';
}

$currency = $_REQUEST['currency'];

$validCurrency = array( 'EUR','GBP','USD' );
if( ! in_array( $currency, $validCurrency ) ) {
    $currency = 'EUR';
}

switch( $donation_type )
{
    case 'card':
        include_once 'donate_card.php';
        break;
    case 'aplgogl':
        include_once 'donate_aplgogl.php';
        break;
    case 'giro':
        include_once 'donate_giro.php';
        break;
    default:
        include_once 'donate_card.php';
        break;
}
