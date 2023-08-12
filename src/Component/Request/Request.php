<?php

namespace App\Component\Request;

use App\Component\Container\ClosureInterface;
use CurlHandle;

final class Request implements ClosureInterface
{
    private CurlHandle $handler;

    private ?string $log = null;

    private bool $verbose = false;

    public function __construct()
    {
        $this->handler = curl_init();

        curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->handler, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->handler, CURLOPT_HEADER, true);
    }

    public function get(string $url): Response
    {
        curl_setopt($this->handler, CURLOPT_CUSTOMREQUEST, 'GET');

        return $this
            ->setUrl($url)
            ->request();
    }

    public function post(string $url, mixed $data): Response
    {
        curl_setopt($this->handler, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($this->handler, CURLOPT_POSTFIELDS, $data);

        return $this
            ->setUrl($url)
            ->request();
    }

    public function setUrl(string $url): self
    {
        curl_setopt($this->handler, CURLOPT_URL, $url);

        return $this;
    }

    public function request(): Response
    {
        curl_setopt($this->handler, CURLOPT_VERBOSE, $this->verbose);

        if ($this->verbose) {
            $fp = fopen('php://temp', 'w+');
            curl_setopt($this->handler, CURLOPT_STDERR, $fp);
        }
        
        $response = new Response($this->handler);
        
        if ($this->verbose) {
            rewind($fp);
            $this->log = stream_get_contents($fp);
            fclose($fp);
        }

        return $response;
    }

    public function enableVerbose(bool $flag = true): self
    {
        $this->verbose = $flag;

        return $this;
    }

    public function enableBody(bool $flag = true): self
    {
        curl_setopt($this->handler, CURLOPT_NOBODY, !$flag);

        return $this;
    }

    public function getLog(): ?string
    {
        return $this->log;
    }

    public function close(): void
    {
        if ($this->handler) {
            curl_close($this->handler);
        }
    }
}