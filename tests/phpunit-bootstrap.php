<?php

// Custom PHPUnit bootstrap to alias Filament resources placed under
// App\Filament\App\Resources to App\Filament\Resources so tests
// expecting the latter namespace pass without changing many files.

require __DIR__ . '/../vendor/autoload.php';

$base = __DIR__ . '/../app/Filament/App/Resources';
if (is_dir($base)) {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base));
    foreach ($it as $file) {
        if (! $file->isFile() || $file->getExtension() !== 'php') {
            continue;
        }

        $relative = str_replace($base . DIRECTORY_SEPARATOR, '', $file->getPathname());
        $classFragment = str_replace(DIRECTORY_SEPARATOR, '\\', substr($relative, 0, -4));

        $sourceClass = 'App\\Filament\\App\\Resources\\' . $classFragment;
        $targetClass = 'App\\Filament\\Resources\\' . $classFragment;

        // Require the file to ensure the source class is defined
        if (! class_exists($sourceClass, false)) {
            require_once $file->getPathname();
        }

        if (class_exists($sourceClass) && ! class_exists($targetClass)) {
            class_alias($sourceClass, $targetClass);
        }
    }
}
