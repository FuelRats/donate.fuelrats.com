<?php

function load_environment() {
  if( file_exists( '.env' ) ) {
    $env = file( '.env', FILE_SKIP_EMPTY_LINES );
    foreach( $env as $line ) {
      putenv( trim( $line ) );
    }
  }
}

function env( $environmentName, $default ) {
  return ( getenv( $environmentName ) ? getenv( $environmentName ) : $default );
}

load_environment();
