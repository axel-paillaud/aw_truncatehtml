<?php

class TruncateHtml
{
    public static function truncate($text, $length = 100, $ellipsis = '...')
    {
        if (mb_strlen(strip_tags($text)) <= $length) {
            return $text;
        }

        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML(mb_convert_encoding($text, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        $totalLength = 0;
        $truncated = '';

        $xpath = new DOMXPath($doc);
        $body = $xpath->query('//body')->item(0);

        if ($body) {
            foreach ($body->childNodes as $node) {
                if ($totalLength >= $length) {
                    break;
                }

                $nodeText = $node->ownerDocument->saveHTML($node);
                $plainText = strip_tags($nodeText);

                if ($totalLength + mb_strlen($plainText) > $length) {
                    $remaining = $length - $totalLength;
                    $truncated .= mb_substr($plainText, 0, $remaining) . $ellipsis;
                    break;
                }

                $truncated .= $nodeText;
                $totalLength += mb_strlen($plainText);
            }
        }

        return $truncated;
    }
}

