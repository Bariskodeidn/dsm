<?php
// application/third_party/autoload.php

spl_autoload_register(function ($class) {
    $prefix = 'PhpOffice\\PhpSpreadsheet\\';
    // CORRECTED BASE DIRECTORY: Removed '/src/'
    // This assumes your structure is like:
    // APPPATH . 'third_party/phpoffice/PhpSpreadsheet/Spreadsheet.php'
    // APPPATH . 'third_party/phpoffice/PhpSpreadsheet/Style/Alignment.php'
    $base_dir = APPPATH . 'third_party/phpoffice/PhpSpreadsheet/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // Temporary debug (remove after it works)
    // echo "Autoloader trying to load: " . $file . "<br>";
    // error_log("Autoloader trying to load: " . $file); // Good for non-web output

    if (file_exists($file)) {
        require $file;
    } else {
        // Temporary debug (remove after it works)
        // echo "File NOT found: " . $file . "<br>";
        // error_log("File NOT found: " . $file);
    }
});
