<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {email} {password} {name=Admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        $name = $this->argument('name');

        // Check if user already exists
        $existingUser = User::where('email', $email)->first();
        
        if ($existingUser) {
            // Update existing user to admin
            $existingUser->name = $name;
            $existingUser->password = Hash::make($password);
            $existingUser->role = 2;
            $existingUser->status = 1;
            $existingUser->save();
            
            $this->info("Existing user updated to admin!");
            $this->info("Email: {$email}");
            $this->info("Password: {$password}");
            $this->info("Role: 2 (Admin)");
        } else {
            // Create new admin user
            $user = new User();
            $user->name = $name;
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->role = 2; // Admin role
            $user->status = 1; // Active
            $user->save();
            
            $this->info("Admin user created successfully!");
            $this->info("Email: {$email}");
            $this->info("Password: {$password}");
            $this->info("Role: 2 (Admin)");
        }
        
        return 0;
    }
}




