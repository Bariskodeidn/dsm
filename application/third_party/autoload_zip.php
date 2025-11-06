<?php
spl_autoload_register(function ($class) {
    // Namespace prefix for ZipStream
    $prefix = 'ZipStream\\';

    // Base directory for ZipStream (adjust path to where you have ZipStream installed)
    $base_dir = APPPATH . 'third_party/ZipStream/src/';

    // Check if the class is part of the ZipStream namespace
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Get the relative class name
    $relative_class = substr($class, $len);

    // Replace namespace separators with directory separators
    // and append '.php' to the class file name
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // If the file exists, include it
    if (file_exists($file)) {
        require $file;
    }
});
