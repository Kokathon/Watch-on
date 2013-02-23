<?php
    
    if( isset( $_GET[ 'callback' ] ) ) :
        $callback = $_GET[ 'callback' ];
    else :
        $callback = 'callback';
    endif;

    $serviceFiles = scandir( 'services' );
    foreach( $serviceFiles as $service ):
        if( $service[0] === '.' ) :
            continue;
        endif;

        $services[] = substr( $service, 0, strlen( $service ) - 4);

    endforeach;

    header( 'Content-type: application/json' );
    echo $callback . "(";
    echo json_encode( $services );
    echo ");";
?>