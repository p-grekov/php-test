<?php

namespace App\Component\Request;

use CurlHandle;

class Response
{
    private int $statusCode;

    private int $bodySize;
    
    private string $body;

    private array $info;

    public function __construct(CurlHandle $handler)
    {
        $response = curl_exec($handler);

        $info = curl_getinfo($handler);
        $this->info = $info;

        $this->statusCode = (int) $info['http_code'];
        $this->body = substr($response, (int) $info['header_size']);
        $this->bodySize = (int) $info['download_content_length'];

        if ($this->bodySize < 0) {
            $this->bodySize = strlen($this->body);
        }

        curl_close($handler);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getBodySize(): int
    {
        return $this->bodySize;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }
}