<?php

namespace WebArch\StreamTools\Filter;

use RuntimeException;
use WebArch\StreamTools\Enum\EndOfLine;

/**
 * Class EndOfLineFilter
 *
 * Replaces line endings.
 *
 * Usage:
 * ```php
 * use \WebArch\StreamTools\Filter\EndOfLineFilter;
 * use \WebArch\StreamTools\Enum\EndOfLine;
 *
 * stream_filter_register(EndOfLineFilter::class, EndOfLineFilter::class);
 * stream_filter_append(
 *     $stream,
 *     EndOfLineFilter::class,
 *     STREAM_FILTER_WRITE | STREAM_FILTER_ALL,
 *     EndOfLine::WINDOWS | UNIX | MAC
 * );
 * ```
 *
 * @package WebArch\StreamTools\Filter
 * @see \WebArch\StreamTools\Enum\EndOfLine
 */
class EndOfLineFilter extends FilterBase
{
    const SEARCH_PATTERN_UNIX = '/\n/m';

    const SEARCH_PATTERN_NON_UNIX = '/\r\n|\n/';

    /**
     * @var string
     */
    private $lineEnding;

    /**
     * @var string Pattern for line ending searching.
     */
    private static $searchPattern = self::SEARCH_PATTERN_UNIX;

    public function __construct()
    {
        /**
         * Even on Windows the fputcsv() function writes Unix line ending "\n", violating RFC 4180
         * ( https://tools.ietf.org/html/rfc4180#section-2 ). So this pattern is the 100% way for non-Unix systems.
         */
        if (PHP_EOL !== EndOfLine::UNIX) {
            self::$searchPattern = self::SEARCH_PATTERN_NON_UNIX;
        }
    }

    /**
     * @param string $lineEnding
     */
    protected function setEndOfLine(string $lineEnding): void
    {
        switch ($lineEnding) {
            case EndOfLine::WINDOWS:
            case EndOfLine::UNIX:
            case EndOfLine::MAC:
                $this->lineEnding = $lineEnding;
                break;
            default:
                throw new RuntimeException('Unsupported line ending.');
        }
    }

    /**
     * @inheritDoc
     */
    public function onCreate()
    {
        $this->setEndOfLine($this->params);
    }

    /**
     * @inheritDoc
     */
    protected function doFilter(string $string): string
    {
        return preg_replace(static::$searchPattern, $this->lineEnding, $string);
    }
}
