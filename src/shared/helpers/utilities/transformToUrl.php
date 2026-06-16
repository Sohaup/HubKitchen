<?php 

function transformToUrl(string $path) {
    return "http" . "://" . $_SERVER['HTTP_HOST'] . $path;
}