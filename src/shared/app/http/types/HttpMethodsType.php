<?php
namespace PostApi\shared\app\http\types;

enum HttpMethodsType : string {
    case GET = "GET" ;
    case POST = "POST";
    case PUT = "PUT" ;
    case PATCH = "PATCH";
    case DELETE = "DELETE";
} 