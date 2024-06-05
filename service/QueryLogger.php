<?php

/**
 * QueryLogger class
 *
 * This class is used to log queries made to a database.
 *
 * @author Sridharan sridharan01234@gmail.com
 * Last Modified : 03-06-2024
 */

class QueryLogger
{
    private const LOG_FILE= __DIR__ . '/custom_queries.log';

    public function logQuery(string $query)
    {
        // Open the log file in append mode
        $fp = fopen(self::LOG_FILE, 'a');

        // Get additional details
        $request_method = $_SERVER['REQUEST_METHOD'];
        $request_uri = $_SERVER['REQUEST_URI'];
        $client_ip = $_SERVER['REMOTE_ADDR'];

        // Log the query with additional details
        fwrite(
            $fp,
            date('Y-m-d H:i:s') . ' - ' .
            "Method: $request_method" . PHP_EOL .
            "URI: $request_uri" . PHP_EOL .
            "Client IP: $client_ip" . PHP_EOL .
            "Query: $query" . PHP_EOL .
            PHP_EOL
        );

        // Close the log file
        fclose($fp);
    }
}
