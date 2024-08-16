<?php

class base64Helper
{

    /**
     * Decodes image to base64
     * 
     * @param string $image
     * @param string $filename
     * @param string $path
     * @return string|bool
     */
    public static function decodeImage(string $image, string $filename, string $path): string | bool
    {
        // Match and extract the image type from the base64 string
        if (preg_match('#^data:image/(\w+);base64,#i', $image, $matches)) {
            $extension = $matches[1]; // e.g., 'png', 'jpg', etc.
            $image = preg_replace('#^data:image/\w+;base64,#i', '', $image);

            // Decode the base64 string
            $tempData = base64_decode($image);
            if ($tempData === false) {
                error_log('Failed to decode base64 string.');
                return false;
            }

            // Ensure the path ends with a directory separator
            $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

            // Check if the directory exists, if not create it
            if (!is_dir($path) && !mkdir($path, 0777, true) && !is_dir($path)) {
                error_log('Failed to create directory: ' . $path);
                return false;
            }

            // Append the correct extension to the filename
            $fullPath = $path . $filename . '.' . $extension;

            // Save the decoded image to the specified path
            if (file_put_contents($fullPath, $tempData) === false) {
                error_log('Failed to write file: ' . $fullPath);
                return false;
            }

            return $fullPath;
        }

        // Return false if the image type could not be extracted
        error_log('Failed to extract image type from base64 string.');
        return false;
    }

    /**
     * Convert image to base64
     *
     * @param string $path
     *
     * @return string
     */
    public static function imageToBase64(?string $path): string
    {
        if (is_null($path) || !$path) {
            return '';
        }

        $imageData = file_get_contents($path);
        $type = pathinfo($path, PATHINFO_EXTENSION);
        return 'data:image/' . $type . ';base64,' . base64_encode($imageData);
    }
}
