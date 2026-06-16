<?php
namespace PostApi\shared\helpers\fecade;
require_once __DIR__ . "/../utilities/serializeToSerin.php";
require_once __DIR__ . "/../utilities/serializeCollectionToSerin.php";

class SerializeToSerin {
    
    public static function serialize(object $entity) {
        $serin = serializeToSerin($entity);      
        return $serin;
    }

    public static function serializeCollection(array $entities) {
        $serin = serializeCollectionToSerin($entities);
        return $serin;
    }
}