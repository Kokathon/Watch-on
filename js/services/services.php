<?php

Header("content-type: application/x-javascript");
$serviceFiles = scandir( '.' );
foreach( $serviceFiles as $service ) {
    if( $service[0] === '.' ) {
        continue;
    }

    if (end(explode('.', $service)) == "js") {
    	echo file_get_contents($service);
    }
}

?>