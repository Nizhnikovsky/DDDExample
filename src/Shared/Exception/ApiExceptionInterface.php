<?php

namespace App\Shared\Exception;

interface ApiExceptionInterface
{
    public function getCode();

    public function getMessage();

    public function getStatusCode();
}
