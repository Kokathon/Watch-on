<?php

Header("content-type: application/x-javascript");
$serviceFiles = scandir( __DIR__ );
foreach( $serviceFiles as $service ) {
    if( $service[0] === '.' ) {
        continue;
    }

    if (pathinfo($service, PATHINFO_EXTENSION) === 'js') {
    	echo file_get_contents($service);
    }
}

?>