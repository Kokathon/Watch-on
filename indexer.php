<?php

if (isset($_GET['service'])) {
    $serviceName = $_GET['service'];

    require_once('services/lovefilm.php');
    require_once('services/hbo.php');
    require_once('services/viaplay.php');
    require_once('services/voddler.php');
    require_once('services/headweb.php');
    require_once('services/svtplay.php');
    require_once('services/tv4play.php');

    $service = new $serviceName();

    if ($service instanceof Indexable) {

        echo "creating index for " . $serviceName;

        $service->createIndex();
    }

}

?>
