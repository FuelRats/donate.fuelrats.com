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