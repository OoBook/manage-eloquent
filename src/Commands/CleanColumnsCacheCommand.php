<?php

namespace Oobook\Database\Eloquent\Commands;

use Illuminate\Console\Command;

class CleanColumnsCacheCommand extends Command
{
    protected $signature = 'manage-eloquent:clean-columns-cache';

    protected $description = 'Clean the manage eloquent relationships cache';

    public function handle()
    {
        $models = [];
        $basePath = base_path();
        
        // Function to check if a class uses the ManageEloquent trait
        $usesManageEloquent = function($class) {
            try {
                if (!class_exists($class, false)) {
                    return false;
                }
        
                $traits = class_uses_recursive($class);
                return isset($traits['Oobook\Database\Eloquent\Concerns\ManageEloquent']);
            } catch (\Throwable $th) {
                return false;
            }
        };
        
        // Recursively find all PHP files
        $directory = new \RecursiveDirectoryIterator($basePath);
        $iterator = new \RecursiveIteratorIterator($directory);
        $files = new \RegexIterator($iterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);
        
        foreach ($files as $file) {
            $file = $file[0];
        
            // Skip non-relevant directories
            if (strpos($file, '/vendor/') !== false ||
                strpos($file, '/storage/') !== false ||
                strpos($file, '/tests/') !== false ||
                strpos($file, '/config/') !== false ||
                strpos($file, '/database/') !== false ||
                strpos($file, '/resources/') !== false ||
                strpos($file, '/routes/') !== false) {
                continue;
            }
        
            try {
                $content = file_get_contents($file);
        
                // Extract the namespace
                if (preg_match('/namespace\s+([^;]+)/i', $content, $matches)) {
                    $namespace = $matches[1];
        
                    // Extract the class name
                    if (preg_match('/class\s+(\w+)/i', $content, $matches)) {
                        $className = $matches[1];
                        $fullClassName = $namespace . '\\' . $className;
        
                        // Check if class uses ManageEloquent trait
                        if ($usesManageEloquent($fullClassName)) {
                            $models[] = $fullClassName;
                        }
                    }
                }
            } catch (\Throwable $th) {
                continue;
            }
        }

        $cacheKey = config('manage-eloquent.cache.column_types.key', 'column_types');

        foreach ($models as $model) {
            $cacheKey = $model . '_column_types';
            $cacheValue = \Illuminate\Support\Facades\Cache::get($cacheKey);
            if ($cacheValue) {
                \Illuminate\Support\Facades\Cache::forget($cacheKey);
                if ( $this->verbose() ) {
                    $this->info('Cleaned the cache for ' . $model);
                }
            }
        }

        $this->info('Cleaned model columns cache');
    }
}
