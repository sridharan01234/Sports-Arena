<?php

/**
 *
 */

class QueryLogger
{
    private const LOG_FILE= __DIR__ . '/custom_queries.log';

    public function logQuery(string $query)
    {
        // Check if the file exists
        if (!file_exists(self::LOG_FILE)) {
            // Create the file if it doesn't exist
            $fp = fopen(self::LOG_FILE, 'w');
            fclose($fp);
        }

        // Open the log file in append mode
        $fp = fopen(self::LOG_FILE, 'a');

        // Log the query
        fwrite($fp, date('Y-m-d H:i:s') . ' - ' . $query . PHP_EOL);

        // Close the log file
        fclose($fp);
    }
}
