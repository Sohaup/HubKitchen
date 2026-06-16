<?php 

function transformToRouteUrl(string $endPoint) {
    return "http" . "://" . $_SERVER['HTTP_HOST'] ."/postApi". $endPoint;
} 