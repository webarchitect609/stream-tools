<?php

namespace WebArch\StreamTools\Test\Filter;

use WebArch\StreamTools\Filter\MbConvertEncodingFilter;

final class MbConvertEncodingFilterTest extends FilterTestBase
{
    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        stream_filter_register(MbConvertEncodingFilter::class, MbConvertEncodingFilter::class);
    }

    public function testUtf8ToWin1251()
    {
        stream_filter_append(
            $this->stream,
            MbConvertEncodingFilter::class,
            STREAM_FILTER_WRITE,
            [
                MbConvertEncodingFilter::PARAM_FROM_ENCODING => 'utf8',
                MbConvertEncodingFilter::PARAM_TO_ENCODING   => 'WINDOWS-1251',
            ]
        );
        fputs($this->stream, 'съешь ещё этих мягких французских булок, да выпей чаю');
        $expected = file_get_contents(realpath(__DIR__ . '/../../..') . '/resources/test_phrase_cp1251.txt');
        self::assertEquals(
            $expected,
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
