<?php
require_once 'env-reader.php';
require_once 'functions.php';

$allowed = false;

if( isset( $_POST['g-recaptcha-response'] ) && ! empty( $_POST['g-recaptcha-response'] ) )
{
        $secret = env( 'RECAPTCHA_SECRET_KEY' );
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
        $responseData = json_decode( $verifyResponse );
        if( $responseData->success )
        {
            $allowed = true;
            $_SESSION['probably-human'] = true;
        }
        else
        {
            $_SESSION['probably-human'] = false;
        }
}

if( ! validate_csrf_token() ) {
    $allowed = false;
    $_SESSION['probably-human'] = false;
}

if( $allowed ) {
    $donation_type = $_REQUEST['donation_type'];

    $valid_types = array( 'card', 'aplgogl', 'giro' );
    if ( ! in_array( $donation_type, $valid_types ) ) {
        $donation_type = 'card';
    }

    $currency = $_REQUEST['currency'];

    $validCurrency = array( 'EUR', 'GBP', 'USD' );
    if ( ! in_array( $currency, $validCurrency ) ) {
        $currency = 'EUR';
    }

    switch ($donation_type) {
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
} else {
    header( 'Location: donate.php' );
}

