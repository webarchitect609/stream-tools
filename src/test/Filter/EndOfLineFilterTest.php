<?php

namespace WebArch\StreamTools\Test\Filter;

use ReflectionException;
use ReflectionProperty;
use WebArch\StreamTools\Enum\EndOfLine;
use WebArch\StreamTools\Filter\EndOfLineFilter;

final class EndOfLineFilterTest extends FilterTestBase
{
    /**
     * @var ReflectionProperty
     */
    private $searchPatternProperty;

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    protected function setUp(): void
    {
        parent::setUp();
        stream_filter_register(EndOfLineFilter::class, EndOfLineFilter::class);
        $this->searchPatternProperty = new ReflectionProperty(EndOfLineFilter::class, 'searchPattern');
        $this->searchPatternProperty->setAccessible(true);
    }

    public function testUnixToWindows()
    {
        stream_filter_append(
            $this->stream,
            EndOfLineFilter::class,
            STREAM_FILTER_WRITE,
            EndOfLine::WINDOWS
        );
        /**
         * Switch to certain mode.
         */
        $this->searchPatternProperty->setValue(EndOfLineFilter::SEARCH_PATTERN_UNIX);
        fputs($this->stream, "First line\nSecond line\nThird line\nEnd of file.");
        self::assertEquals(
            "First line\r\nSecond line\r\nThird line\r\nEnd of file.",
            file_get_contents($this->filename)
        );
    }

    public function testWindowsToUnix()
    {
        stream_filter_append(
            $this->stream,
            EndOfLineFilter::class,
            STREAM_FILTER_WRITE,
            EndOfLine::UNIX
        );
        /**
         * Switch to certain mode.
         */
        $this->searchPatternProperty->setValue(EndOfLineFilter::SEARCH_PATTERN_NON_UNIX);
        fputs($this->stream, "First line\r\nSecond line\r\nThird line\r\nEnd of file.");
        self::assertEquals(
            "First line\nSecond line\nThird line\nEnd of file.",
            file_get_contents($this->filename)
        );
    }

    public function testWindowsToUnixWithFPutCsvFunction()
    {
        stream_filter_append(
            $this->stream,
            EndOfLineFilter::class,
            STREAM_FILTER_WRITE,
            EndOfLine::UNIX
        );
        /**
         * Switch to certain mode.
         */
        $this->searchPatternProperty->setValue(EndOfLineFilter::SEARCH_PATTERN_NON_UNIX);
        fputcsv($this->stream, ['First','Second']);
        fputcsv($this->stream, ['Third','Fourth']);
        fputcsv($this->stream, ['Fifth']);
        self::assertEquals(
            "First,Second\nThird,Fourth\nFifth\n",
            file_get_contents($this->filename)
        );
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
