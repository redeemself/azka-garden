<?php
/**
 * Merge Conflict Fixer
 * - Automatically resolves Git merge conflicts by choosing the HEAD (current branch) version
 * - Updated: 2025-07-30 03:44:59 by mulyadafa
 */

// Configuration
$rootDir = __DIR__;
$extensions = ['php', 'blade.php'];
$excludeDirs = ['vendor/symfony', 'vendor/phpunit', 'vendor/monolog', 'node_modules', 'storage'];
$fixedCount = 0;
$errorCount = 0;

echo "🔍 Starting merge conflict resolution...\n";

// Find all files with conflicts
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootDir, RecursiveDirectoryIterator::SKIP_DOTS)
);

foreach ($iterator as $file) {
    // Skip directories in exclude list
    foreach ($excludeDirs as $excludeDir) {
        if (strpos($file->getPathname(), $excludeDir) !== false) {
            continue 2;
        }
    }

    // Check for valid extensions
    $extension = pathinfo($file->getPathname(), PATHINFO_EXTENSION);
    if (!in_array($extension, $extensions) && 
        !(strpos($file->getPathname(), '.blade.php') !== false && $extension === 'php')) {
        continue;
    }

    // Skip directories
    if (!$file->isFile()) {
        continue;
    }

    // Check file for conflicts
    $contents = file_get_contents($file->getPathname());
    
    if (strpos($contents, '<<<<<<< HEAD') !== false) {
        echo "Found conflict in: {$file->getPathname()}\n";
        
        try {
            // Resolve conflict by keeping the HEAD version
            $newContents = preg_replace(
                '/<<<<<<< HEAD(.*?)=======(.*?)>>>>>>> .*/s',
                '$1',
                $contents
            );
            
            // Add a comment at the top that this file was auto-fixed
            $phpOpen = '<?php';
            if (strpos($newContents, $phpOpen) === 0) {
                $newContents = $phpOpen . "\n// Auto-fixed merge conflict - 2025-07-30 03:44:59 by mulyadafa\n" . 
                               substr($newContents, strlen($phpOpen));
            }
            
            // Write fixed content back to file
            file_put_contents($file->getPathname(), $newContents);
            echo "✅ Fixed conflict in: {$file->getPathname()}\n";
            $fixedCount++;
        } catch (Exception $e) {
            echo "❌ Error fixing: {$file->getPathname()} - {$e->getMessage()}\n";
            $errorCount++;
        }
    }
}

// Final report
echo "===========================================\n";
echo "📊 Conflict Resolution Report:\n";
echo "- ✅ Fixed files: {$fixedCount}\n";
echo "- ❌ Error count: {$errorCount}\n";
echo "- 🕒 Completed at: " . date('Y-m-d H:i:s') . "\n";
echo "===========================================\n\n";

echo "🚨 IMPORTANT: After running this script, please run:\n";
echo "composer dump-autoload\n";
echo "php artisan config:clear\n";
echo "php artisan view:clear\n";
echo "php artisan cache:clear\n";