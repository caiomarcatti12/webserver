<?php

namespace CaioMarcatti12\Webserver\Enum;

enum ContentTypeEnum: string
{
    case TEXT = 'text/plain';
    case CSV = 'text/csv';
    case JSON = 'application/json';
    case XML = 'application/xml';
}