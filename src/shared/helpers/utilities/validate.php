<?php 

function validateValue(int $type , mixed $value , ?array $options=[] , string $message) {
    if (filter_var($value , $type , $options )) {
        return $value;
    } else {
        throw new Exception($message);
    }
    
}