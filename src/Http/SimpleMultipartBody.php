<?php

namespace Olsgreen\AdobeSign\Http;

class SimpleMultipartBody
{
    protected $elements = [];

    public function add(string $name, $contents, string $filename = null, array $headers = [])
    {
        $element = [
            'name' => $name,
            'contents' => $contents
        ];

        if (!is_null($filename)) {
            $element['File-Name'] = $filename;
        }

        if (!empty($headers)) {
            $element['headers'] = $headers;
        }

        $this->elements[] = $element;
    }

    public function toArray()
    {
        return $this->elements;
    }
}