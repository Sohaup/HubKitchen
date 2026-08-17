<?php

namespace PostApi\shared\helpers\fecade;

use Error;
use Exception;

require_once __DIR__ . "/../utilities/serializeToSerin.php";
require_once __DIR__ . "/../utilities/serializeCollectionToSerin.php";

class SerializeToSerin
{

    public static function serialize(object $entity)
    {
        try {
            $serin = serializeToSerin($entity);
            return $serin;
        } catch (Exception $err) {
            throw new Error($err->getMessage());
        }
    }

    public static function serializeCollection(array $entities)
    {
        try {
            $serin = serializeCollectionToSerin($entities);
            return $serin;
        } catch (Exception $err) {
            throw new Error($err->getMessage());
        }
    }
}
