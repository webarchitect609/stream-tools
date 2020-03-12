<?php

namespace WebArch\StreamTools\Filter;

use php_user_filter;

abstract class FilterBase extends php_user_filter
{
    /**
     * Performs required type of altering.
     *
     * @param string $string
     *
     * @return string
     */
    abstract protected function doFilter(string $string): string;

    /**
     * @inheritDoc
     */
    public function filter($in, $out, &$consumed, $closing)
    {
        while ($bucket = stream_bucket_make_writeable($in)) {
            $bucket->data = $this->doFilter($bucket->data);
            $consumed += $bucket->datalen;
            stream_bucket_append($out, $bucket);
        }

        return PSFS_PASS_ON;
    }
}
