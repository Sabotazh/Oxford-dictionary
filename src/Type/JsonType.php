<?php

namespace App\Type;

class JsonType
{
    private string $data;

    public function __construct(string $data)
    {
        $this->data = $data;
    }

    public function toObject()
    {
        return json_decode($this->data);
    }

    public function toArray()
    {
        return json_decode($this->data, true);
    }
}
