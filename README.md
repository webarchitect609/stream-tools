Stream Tools
===============

**Be careful**: it's the early alpha-version so far! 

[![Build Status](https://travis-ci.org/webarchitect609/stream-tools.svg?branch=master)](https://travis-ci.org/webarchitect609/stream-tools)

Useful filters and other tools to be used with [PHP Stream Functions](https://www.php.net/manual/en/ref.stream.php).

How to use
----------
1) Install via [composer](https://getcomposer.org/)
    ```bash
    composer require webarchitect609/stream-tools
    ```

2) Register the desired filter.
    ```php
    use WebArch\StreamTools\Filter\EndOfLineFilter;
   
    stream_filter_register(EndOfLineFilter::class, EndOfLineFilter::class);
    ```
3) See the filter's phpDocs for help on usage and params. Attach the filter to the opened file resource or other stream.
    ```php
    use WebArch\StreamTools\Enum\EndOfLine;
    use WebArch\StreamTools\Filter\EndOfLineFilter;
    /** @var resource $stream */
    stream_filter_append(
        $stream,
        EndOfLineFilter::class,
        STREAM_FILTER_WRITE,
        EndOfLine::WINDOWS
    );
    ```
4) Enjoy!
    ```php
    /** @var resource $stream */
    fputs($stream, "All LF symbols\nwill be replaced by CRLF symbols.\nThat's it!");
    ```
