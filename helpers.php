<?php

if (!function_exists('tag_replace')) {
    /**
     * @param  string|mixed|T  $text
     * @param  array|object  $materials
     * @param  string  $pattern
     * @return array|string|mixed|T
     * @template T
     */
    function tag_replace(mixed $text, array|object $materials, string $pattern = "{*}"): array|string|null
    {
        if (!is_string($text)) {
            return $text;
        }
        $pattern = preg_quote($pattern);
        $pattern = str_replace('\*', '([a-zA-Z0-9\_\-\.]+)', $pattern);

        return preg_replace_callback("/{$pattern}/", function ($m) use ($materials) {
            return multi_dot_call($materials, $m[1]);
        }, $text);
    }
}

if (!function_exists('file_lines_get_contents')) {
    /**
     * Get data from file by lines
     *
     * @param  string  $file
     * @param  int  $to
     * @param  int  $from
     * @return string|null
     */
    function file_lines_get_contents(string $file, int $to = -1, int $from = 0): ?string
    {
        if ($file = fopen($file, "r")) {
            $line = 1;
            $lines = [];
            while(!feof($file)) {
                if ($line >= $from && ($line <= $to || $to < 0)) {
                    $lines[] = fgets($file);
                } else {
                    break;
                }
                $line++;
            }
            fclose($file);

            return implode("", $lines);
        }

        return null;
    }
}
