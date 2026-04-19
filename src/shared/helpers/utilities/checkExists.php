<?php

use Error;

function checkExists(array $values , string $message) {
    foreach ($values as $value) {
    if (isset($value)) {
        return $values;
    } else {
        throw new Error($message);
    }
}
}