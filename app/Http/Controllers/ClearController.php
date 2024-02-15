<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ClearController extends Controller
{
    public function clearOptimize() {
        Artisan::call('optimize:clear');
        return redirect('/');
    }

    public function clearConfig() {
        Artisan::call('config:clear');
        return redirect('/');
    }

    public function clearCache() {
        Artisan::call('cache:clear');
        return redirect('/');
    }

    public function migrate() {
        Artisan::call('migrate');
        return redirect('/');
    }

    public function migrateFresh() {
        Artisan::call('migrate:fresh');
        return redirect('/');
    }

    public function seeder() {
        Artisan::call('db:seed');
        return redirect('/');
    }

    public function storageLink() {
        Artisan::call('storage:link');
        return redirect('/');
    }

    public function seedPermissions()
    {
        // Run the seed command with the specified seeder class
        Artisan::call('db:seed', [
            '--class' => 'PermissionsSeeder',
        ]);

        // Clear the cache
        Artisan::call('cache:clear');

        // Redirect to the home page or any other desired page
        return redirect('/');
    }
}
