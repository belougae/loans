<?php

namespace App\Http\Controllers\Api;

use Dingo\Api\Http\Response\Format\Json;

class NewJson extends Json
{
    /**
     * Encode the content to its JSON representation.
     *
     * @param mixed $content
     *
     * @return string
     */
    protected function encode($content)
    {
        $jsonEncodeOptions = [];

        // Here is a place, where any available JSON encoding options, that
        // deal with users' requirements to JSON response formatting and
        // structure, can be conveniently applied to tweak the output.

        if ($this->isJsonPrettyPrintEnabled()) {
            $jsonEncodeOptions[] = JSON_PRETTY_PRINT;
        }

        if(!isset($content['status_code'])){
            $newContent['status'] = 'success';
            $newContent['code'] = '200';
            if(isset($content['data'])){
                $newContent['data'] = $content['data'];
                if(isset($content['meta']))
                    $newContent['meta'] = $content['meta'];
            }else{
                $newContent['data'] = $content;
            }
        }else{
            $newContent = $content;
            unset($newContent['status_code']);
            $newContent['code'] = $content['status_code'];
        }

        $encodedString = $this->performJsonEncoding($newContent, $jsonEncodeOptions);

        if ($this->isCustomIndentStyleRequired()) {
            $encodedString = $this->indentPrettyPrintedJson(
                $encodedString,
                $this->options['indent_style']
            );
        }

        return $encodedString;
    }
}