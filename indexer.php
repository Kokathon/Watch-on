<?php
require_once('services/require_services.php');

if (isset($_GET['service'])) {
    $serviceName = $_GET['service'];

    $service = new $serviceName();

    if ($service instanceof Indexable) {

        echo "creating index for " . $serviceName;

        $service->createIndex();
    }

}

?>
