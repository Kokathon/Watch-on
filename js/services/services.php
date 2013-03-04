<?php

Header("content-type: application/x-javascript");
$serviceFiles = scandir( __DIR__ );
foreach( $serviceFiles as $service ) {
    if( $service[0] === '.' ) {
        continue;
    }

    $fileParts = explode('.', $service);
    if (end($fileParts) == "js") {
    	echo file_get_contents($service);
    }
}

?>