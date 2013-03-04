<?php

$serviceFiles = scandir( '.' );
foreach( $serviceFiles as $service ) {
    if (strstr($service, '.service.php')) {
    	require_once(__DIR__ . '/' . $service);
    }
}

?>
