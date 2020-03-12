<?php

namespace WebArch\StreamTools\Filter;

use InvalidArgumentException;

/**
 * Class MbConvertEncodingFilter
 *
 * Converts character encoding, using mb_convert_encoding function.
 *
 * Usage:
 * ```php
 * use \WebArch\StreamTools\Filter\MbConvertEncodingFilter;
 *
 * stream_filter_register(MbConvertEncodingFilter::class, MbConvertEncodingFilter::class);
 * stream_filter_append(
 *     $stream,
 *     MbConvertEncodingFilter::class,
 *     STREAM_FILTER_WRITE | STREAM_FILTER_ALL,
 *     [
 *         MbConvertEncodingFilter::PARAM_FROM_ENCODING => 'uft8',
 *         MbConvertEncodingFilter::PARAM_TO_ENCODING => 'WINDOWS-1251',
 *     ]
 * );
 * ```
 *
 * If 'fromEncoding' is null, mb_internal_encoding() will be used internally by mb_convert_encoding function.
 *
 * @package WebArch\StreamTools\Filter
 */
class MbConvertEncodingFilter extends FilterBase
{
    const PARAM_FROM_ENCODING = 'fromEncoding';

    const PARAM_TO_ENCODING = 'toEncoding';

    /**
     * @var null|string
     */
    protected $fromEncoding;

    /**
     * @var string
     */
    protected $toEncoding;

    /**
     * @inheritDoc
     */
    public function onCreate()
    {
        if (!is_array($this->params)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Expect params as array with `%s` and `%s`, but got %s.',
                    self::PARAM_FROM_ENCODING,
                    self::PARAM_TO_ENCODING,
                    gettype($this->params)
                )
            );
        }
        if (array_key_exists(self::PARAM_FROM_ENCODING, $this->params)) {
            $this->fromEncoding = trim($this->params[self::PARAM_FROM_ENCODING]);
        }

        if (!array_key_exists(self::PARAM_TO_ENCODING, $this->params)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Missing `%s` in params.',
                    self::PARAM_TO_ENCODING
                )
            );
        }
        $this->toEncoding = trim($this->params[self::PARAM_TO_ENCODING]);
    }

    /**
     * @inheritDoc
     */
    protected function doFilter(string $string): string
    {
        return mb_convert_encoding($string, $this->toEncoding, $this->fromEncoding);
    }
}
