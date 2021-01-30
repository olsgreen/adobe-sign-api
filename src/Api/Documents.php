<?php

namespace Olsgreen\AdobeSign\Api;

use Olsgreen\AdobeSign\Http\SimpleMultipartBody;

class Documents extends AbstractApi
{
    public function uploadFile($path): string
    {
        if (!is_file($path) || !is_readable($path)) {
            throw new \Exception(
                sprintf('The path is not readable [%s]', $path)
            );
        }

        $fileName = basename($path);
        $mimeType = mime_content_type($path);
        $data = fopen($path, 'r');

        return $this->upload($fileName, $mimeType, $data);
    }

    public function upload($fileName, $mimeType, $data): string
    {
        $body = new SimpleMultipartBody();
        $body->add('File-Name', $fileName);
        $body->add('Mime-Type', $mimeType);
        $body->add('File', $data);

        $response = $this->_post('/transientDocuments', [], $body);

        return $response['transientDocumentId'];
    }
}