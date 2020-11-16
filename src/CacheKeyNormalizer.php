<?php

declare(strict_types=1);

namespace Yiisoft\Cache;

use Yiisoft\Cache\Exception\InvalidArgumentException;

use function ctype_alnum;
use function json_encode;
use function json_last_error_msg;
use function is_int;
use function is_string;
use function md5;
use function mb_strlen;

final class CacheKeyNormalizer
{
    /**
     * Normalizes cache key from a given key.
     *
     * If the given key is a string containing alphanumeric characters only and no more than 32 characters,
     * then the key will be returned back as it is, integers will be converted to strings. Otherwise,
     * a normalized key is generated by serializing the given key and applying MD5 hashing.
     *
     * @param mixed $key The key to be normalized.
     * @return string The normalized cache key.
     */
    public function normalize($key): string
    {
        if (is_string($key) || is_int($key)) {
            $key = (string) $key;
            return ctype_alnum($key) && mb_strlen($key, '8bit') <= 32 ? $key : md5($key);
        }

        if (($key = json_encode($key)) === false) {
            throw new InvalidArgumentException('Invalid key. ' . json_last_error_msg());
        }

        return md5($key);
    }
}
