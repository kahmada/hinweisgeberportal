<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AnonymousCredentialsService
{
    /**
     * Generate anonymous credentials for a whistleblower
     * 
     * @return array{username: string, password: string, password_hash: string, token: string}
     */
    public function generate(): array
    {
        // Generate unique username: format "WB-XXXXX" (WhistleBlower)
        $username = $this->generateUniqueUsername();
        
        // Generate secure password (12 characters)
        $password = $this->generateSecurePassword();
        
        // Generate unique token for URL access
        $token = Str::random(32);
        
        return [
            'username' => $username,
            'password' => $password, // Plain text (to display once)
            'password_hash' => Hash::make($password),
            'token' => $token
        ];
    }
    
    /**
     * Generate a unique username with format WB-XXXXXXXX
     * 
     * @return string
     */
    private function generateUniqueUsername(): string
    {
        do {
            $username = 'WB-' . strtoupper(Str::random(8));
            
            // Check if username already exists
            $exists = \App\Models\Report::where('anonymous_username', $username)->exists();
        } while ($exists);
        
        return $username;
    }
    
    /**
     * Generate a secure password with mixed characters
     * 
     * @param int $length
     * @return string
     */
    private function generateSecurePassword(int $length = 12): string
    {
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $special = '!@#$%';
        
        $allChars = $lowercase . $uppercase . $numbers . $special;
        
        // Ensure at least one of each type
        $password = '';
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $special[random_int(0, strlen($special) - 1)];
        
        // Fill the rest randomly
        for ($i = 4; $i < $length; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }
        
        // Shuffle the password
        return str_shuffle($password);
    }
}
