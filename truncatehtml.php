<?php
/**
 * Copyright since 2024 Axel Paillaud - Axelweb
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to [ton email] so we can send you a copy immediately.
 *
 * @author    Axel Paillaud - Axelweb <contact@axelweb.fr>
 * @copyright Since 2024 Axel Paillaud - Axelweb
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

class TruncateHtml
{
    public static function truncate($text, $length = 100, $ellipsis = '...', $cutAfterWord = true)
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

                    if ($cutAfterWord) {
                        // Cut the last word
                        $words = explode(' ', mb_substr($plainText, 0, $remaining));
                        array_pop($words); // Remove the last word
                        $truncated .= implode(' ', $words) . $ellipsis;
                    } else {
                        // Cut the text at the exact length
                        $truncated .= mb_substr($plainText, 0, $remaining) . $ellipsis;
                    }

                    break;
                }

                $truncated .= $nodeText;
                $totalLength += mb_strlen($plainText);
            }
        }

        return $truncated;
    }
}
