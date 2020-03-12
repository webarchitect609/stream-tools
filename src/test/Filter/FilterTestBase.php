<?php

namespace WebArch\StreamTools\Test\Filter;

use PHPUnit\Framework\TestCase;
use RuntimeException;

abstract class FilterTestBase extends TestCase
{
    /**
     * @var string
     */
    protected $filename;

    /**
     * @var resource
     */
    protected $stream;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->filename = tempnam(sys_get_temp_dir(), 'EndOfLineFilterTest');
        if (false === $this->filename) {
            throw new RuntimeException(
                sprintf(
                    'Unable to create file with unique file name in %s',
                    sys_get_temp_dir()
                )
            );
        }
        $this->stream = fopen($this->filename, 'w');
        if (false === $this->stream) {
            throw new RuntimeException(
                sprintf(
                    'Unable to open %s for write.',
                    $this->filename
                )
            );
        }
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        unlink($this->filename);
    }
}
