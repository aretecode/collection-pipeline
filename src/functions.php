<?php

use Arete\CollectionPipeline\CollectionPipeline as CP;

// collectionPipeline
if (!function_exists('cp')) {
    function cp($array) {
        return CP::from($array);
    }
}