<?php

if (isset($_GET['service'])) {
    $serviceName = $_GET['service'];

    require_once('services/require_services.php');

    $service = new $serviceName();

    if ($service instanceof Indexable) {

        echo "creating index for " . $serviceName;

        $service->createIndex();
    }

}

?>
