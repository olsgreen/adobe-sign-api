<?php

namespace Olsgreen\AdobeSign\Http;

/**
 * SimpleMultipartBody
 * Provides a simple, HTTP client agnostic way of
 * holding multipart request bodies before transmission.
 */
class SimpleMultipartBody
{
    /**
     * Parts
     *
     * @var array
     */
    protected $elements = [];

    /**
     * Add a part.
     *
     * @param string $name
     * @param $contents
     * @param string|null $filename
     * @param array $headers
     */
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

    /**
     * Remove an part.
     *
     * @param $name
     * @return $this
     */
    public function remove($name): SimpleMultipartBody
    {
        $this->elements = array_filter($this->elements, function($item) use ($name) {
            return $item['name'] !== $name;
        });

        return $this;
    }

    /**
     * Express the class as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->elements;
    }
}