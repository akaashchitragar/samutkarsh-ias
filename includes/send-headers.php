<?php
/**
 * Send crawler-friendly HTTP headers.
 * Include this before any output (e.g. before HTML) on every public page.
 */
if (!headers_sent()) {
    header('X-Robots-Tag: index, follow', true);
}
