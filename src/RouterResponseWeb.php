<?php

namespace CaioMarcatti12\Web;

use CaioMarcatti12\Data\ObjectMapper;
use CaioMarcatti12\Validation\Assert;

class RouterResponseWeb
{
    private mixed $_body;
    private int $_status = 200;
    private array $_headers = [];

    public function __construct(mixed $body, int $status = 200, array $headers = [])
    {
        $this->_body = $body;
        $this->_status = $status;
        $this->_headers = $headers;
    }

    public function headers(): array
    {
        if(Assert::isEmpty($this->_headers)){
            $this->_headers = [
                'Content-Type' => 'application/json'
            ];
        }

        return $this->_headers;
    }

    public function code(): int
    {
        return $this->_status;
    }

    public function response(): string
    {
        if(is_array($this->_body)) return $this->arrayResponse();
        if(is_object($this->_body)) return $this->objectResponse();

        return $this->stringResponse();
    }

    private function stringResponse(): string{
        return $this->_body ?? '';
    }

    private function arrayResponse(): string{
        return json_encode($this->_body);
    }

    private function objectResponse(): string{
        $this->_body = ObjectMapper::toArray($this->_body);
        return $this->arrayResponse();
    }
}