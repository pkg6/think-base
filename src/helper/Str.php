<?php

/*
 * This file is part of the tp5er/think-base
 *
 * (c) pkg6 <https://github.com/pkg6>
 *
 * (L) Licensed <https://opensource.org/license/MIT>
 *
 * (A) zhiqiang <https://www.zhiqiang.wang>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace tp5er\think\helper;

class Str extends \think\helper\Str
{
    /**
     * Make a string's first character uppercase.
     *
     * @param  string  $string
     *
     * @return string
     */
    public static function ucfirst($string)
    {
        return static::upper(static::substr($string, 0, 1)) . static::substr($string, 1);
    }

    /**
     * Parse a Class[@]method style callback into class and method.
     *
     * @param  string  $callback
     * @param  string|null  $default
     *
     * @return array<int, string|null>
     */
    public static function parseCallback($callback, $default = null)
    {
        return static::contains($callback, '@') ? explode('@', $callback, 2) : [$callback, $default];
    }

    /**
     * Returns the number of bytes in the given string.
     * This method ensures the string is treated as a byte array by using `mb_strlen()`.
     *
     * @param string $string the string being measured for length
     *
     * @return int the number of bytes in the given string.
     */
    public static function byteLength($string)
    {
        return mb_strlen((string) $string, '8bit');
    }

    /**
     * Safely casts a float to string independent of the current locale.
     * The decimal separator will always be `.`.
     *
     * @param float|int $number a floating point number or integer.
     *
     * @return string the string representation of the number.
     */
    public static function floatToString($number)
    {
        // . and , are the only decimal separators known in ICU data,
        // so its safe to call str_replace here
        return str_replace(',', '.', (string) $number);
    }

    /**
     * Returns the portion of string specified by the start and length parameters.
     * This method ensures the string is treated as a byte array by using `mb_substr()`.
     *
     * @param string $string the input string. Must be one character or longer.
     * @param int $start the starting position
     * @param int|null $length the desired portion length. If not specified or `null`, there will be
     * no limit on length i.e. the output will be until the end of the string.
     *
     * @return string the extracted part of string, or FALSE on failure or an empty string.
     *
     * @see https://www.php.net/manual/en/function.substr.php
     */
    public static function byteSubstr($string, $start, $length = null)
    {
        if ($length === null) {
            $length = static::byteLength($string);
        }

        return mb_substr($string, $start, $length, '8bit');
    }
    /**
     * Returns the trailing name component of a path.
     * This method is similar to the php function `basename()` except that it will
     * treat both \ and / as directory separators, independent of the operating system.
     * This method was mainly created to work on php namespaces. When working with real
     * file paths, php's `basename()` should work fine for you.
     * Note: this method is not aware of the actual filesystem, or path components such as "..".
     *
     * @param string $path A path string.
     * @param string $suffix If the name component ends in suffix this will also be cut off.
     *
     * @return string the trailing name component of the given path.
     *
     * @see https://www.php.net/manual/en/function.basename.php
     */
    public static function basename($path, $suffix = '')
    {
        $len = mb_strlen($suffix);
        if ($len > 0 && mb_substr($path, -$len) === $suffix) {
            $path = mb_substr($path, 0, -$len);
        }
        $path = rtrim(str_replace('\\', '/', $path), '/');
        $pos = mb_strrpos($path, '/');
        if ($pos !== false) {
            return mb_substr($path, $pos + 1);
        }

        return $path;
    }
    /**
     * Returns parent directory's path.
     * This method is similar to `dirname()` except that it will treat
     * both \ and / as directory separators, independent of the operating system.
     *
     * @param string $path A path string.
     *
     * @return string the parent directory's path.
     *
     * @see https://www.php.net/manual/en/function.basename.php
     */
    public static function dirname($path)
    {
        $normalizedPath = rtrim(
            str_replace('\\', '/', $path),
            '/'
        );
        $separatorPosition = mb_strrpos($normalizedPath, '/');

        if ($separatorPosition !== false) {
            return mb_substr($path, 0, $separatorPosition);
        }

        return '';
    }
    /**
     * Explodes string into array, optionally trims values and skips empty ones.
     *
     * @param string $string String to be exploded.
     * @param string $delimiter Delimiter. Default is ','.
     * @param mixed $trim Whether to trim each element. Can be:
     *   - boolean - to trim normally;
     *   - string - custom characters to trim. Will be passed as a second argument to `trim()` function.
     *   - callable - will be called for each value instead of trim. Takes the only argument - value.
     * @param bool $skipEmpty Whether to skip empty strings between delimiters. Default is false.
     *
     * @return array
     */
    public static function explode($string, $delimiter = ',', $trim = true, $skipEmpty = false)
    {
        $result = explode($delimiter, $string);
        if ($trim !== false) {
            if ($trim === true) {
                $trim = 'trim';
            } elseif ( ! is_callable($trim)) {
                $trim = function ($v) use ($trim) {
                    return trim($v, $trim);
                };
            }
            $result = array_map($trim, $result);
        }
        if ($skipEmpty) {
            // Wrapped with array_values to make array keys sequential after empty values removing
            $result = array_values(array_filter($result, function ($value) {
                return $value !== '';
            }));
        }

        return $result;
    }
    /**
     * Counts words in a string.
     *
     * @param string $string the text to calculate
     *
     * @return int
     */
    public static function countWords($string)
    {
        return count(preg_split('/\s+/u', $string, 0, PREG_SPLIT_NO_EMPTY));
    }

    /**
     * Returns string representation of number value with replaced commas to dots, if decimal point
     * of current locale is comma.
     *
     * @param int|float|string $value the value to normalize.
     *
     * @return string
     */
    public static function normalizeNumber($value)
    {
        $value = (string) $value;
        $localeInfo = localeconv();
        $decimalSeparator = isset($localeInfo['decimal_point']) ? $localeInfo['decimal_point'] : null;
        if ($decimalSeparator !== null && $decimalSeparator !== '.') {
            $value = str_replace($decimalSeparator, '.', $value);
        }

        return $value;
    }
    /**
     * Encodes string into "Base 64 Encoding with URL and Filename Safe Alphabet" (RFC 4648).
     * > Note: Base 64 padding `=` may be at the end of the returned string.
     * > `=` is not transparent to URL encoding.
     *
     * @param string $input the string to encode.
     *
     * @return string encoded string.
     *
     * @see https://tools.ietf.org/html/rfc4648#page-7
     */
    public static function base64UrlEncode($input)
    {
        return strtr(base64_encode($input), '+/', '-_');
    }

    /**
     * Decodes "Base 64 Encoding with URL and Filename Safe Alphabet" (RFC 4648).
     *
     * @param string $input encoded string.
     *
     * @return string decoded string.
     *
     * @see https://tools.ietf.org/html/rfc4648#page-7
     */
    public static function base64UrlDecode($input)
    {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    /**
     * Checks if the passed string would match the given shell wildcard pattern.
     * This function emulates [[fnmatch()]], which may be unavailable at certain environment, using PCRE.
     *
     * @param string $pattern the shell wildcard pattern.
     * @param string $string the tested string.
     * @param array $options options for matching. Valid options are:
     * - caseSensitive: bool, whether pattern should be case sensitive. Defaults to `true`.
     * - escape: bool, whether backslash escaping is enabled. Defaults to `true`.
     * - filePath: bool, whether slashes in string only matches slashes in the given pattern. Defaults to `false`.
     *
     * @return bool whether the string matches pattern or not.
     */
    public static function matchWildcard($pattern, $string, $options = [])
    {
        if ($pattern === '*' && empty($options['filePath'])) {
            return true;
        }
        $replacements = [
            '\\\\\\\\' => '\\\\',
            '\\\\\\*' => '[*]',
            '\\\\\\?' => '[?]',
            '\*' => '.*',
            '\?' => '.',
            '\[\!' => '[^',
            '\[' => '[',
            '\]' => ']',
            '\-' => '-',
        ];
        if (isset($options['escape']) && ! $options['escape']) {
            unset($replacements['\\\\\\\\']);
            unset($replacements['\\\\\\*']);
            unset($replacements['\\\\\\?']);
        }
        if ( ! empty($options['filePath'])) {
            $replacements['\*'] = '[^/\\\\]*';
            $replacements['\?'] = '[^/\\\\]';
        }
        $pattern = strtr(preg_quote($pattern, '#'), $replacements);
        $pattern = '#^' . $pattern . '$#us';
        if (isset($options['caseSensitive']) && ! $options['caseSensitive']) {
            $pattern .= 'i';
        }

        return preg_match($pattern, (string) $string) === 1;
    }

    /**
     * This method provides a unicode-safe implementation of built-in PHP function `ucfirst()`.
     *
     * @param string $string the string to be proceeded
     * @param string $encoding Optional, defaults to "UTF-8"
     *
     * @return string
     *
     * @see https://www.php.net/manual/en/function.ucfirst.php
     */
    public static function mb_ucfirst($string, $encoding = 'UTF-8')
    {
        $firstChar = mb_substr((string) $string, 0, 1, $encoding);
        $rest = mb_substr((string) $string, 1, null, $encoding);

        return mb_strtoupper($firstChar, $encoding) . $rest;
    }
    /**
     * This method provides a unicode-safe implementation of built-in PHP function `ucwords()`.
     *
     * @param string $string the string to be proceeded
     * @param string $encoding Optional, defaults to "UTF-8"
     *
     * @return string
     *
     * @see https://www.php.net/manual/en/function.ucwords
     */
    public static function mb_ucwords($string, $encoding = 'UTF-8')
    {
        $string = (string) $string;
        if (empty($string)) {
            return $string;
        }
        $parts = preg_split('/(\s+\W+\s+|^\W+\s+|\s+)/u', $string, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $ucfirstEven = trim(mb_substr($parts[0], -1, 1, $encoding)) === '';
        foreach ($parts as $key => $value) {
            $isEven = (bool) ($key % 2);
            if ($ucfirstEven === $isEven) {
                $parts[$key] = static::mb_ucfirst($value, $encoding);
            }
        }

        return implode('', $parts);
    }
}
