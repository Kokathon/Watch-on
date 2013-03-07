<?php
if( isset( $_GET[ 'callback' ] ) ) :
    $callback = $_GET[ 'callback' ];
else :
    $callback = 'callback';
endif;

$serviceFiles = scandir( 'services' );
$services = array();
foreach( $serviceFiles as $service ):
    if( $service[0] === '.' ) :
        continue;
    endif;
    if( strpos( $service, '.service.php' ) !== false ) :
        $services[] = substr( $service, 0, strlen( $service ) - 12 );
    endif;
endforeach;

header( 'Content-type: application/json' );
echo $callback . "(";
echo json_encode( $services );
echo ");";
?>