<?php

namespace CaioMarcatti12\Webserver\Enum;

enum RequestMethodEnum : string
{
    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    case DELETE = 'DELETE';
    case PATCH = 'PATCH';
    case HEAD = 'HEAD';
}