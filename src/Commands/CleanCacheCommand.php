<?php

namespace Oobook\Database\Eloquent\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CacheCleanCommand extends Command
{
    protected $signature = 'manage-eloquent:cache:clean';

    protected $description = 'Clean the manage eloquent cache';

    public function handle()
    {
        Artisan::call('manage-eloquent:clean-columns-cache');
        
        $this->info('Cleaned the manage eloquent cache');
    }
    
}