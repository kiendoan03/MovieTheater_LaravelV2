<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $account = \App\Models\Account::find(1); // admin
        if (!$account) {
            $this->error('No account 1');
            return;
        }

        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($account);
        $this->info("Token: " . $token);

        \Tymon\JWTAuth\Facades\JWTAuth::setToken($token);

        try {
            $user = auth()->guard('api')->setToken($token)->user();
            if ($user) {
                $this->info("Success! User ID: " . $user->id);
                $this->info("Role: " . strtolower($user->role->name ?? $user->role));
            } else {
                $this->error("Failed to authenticate with token.");
            }
        } catch (\Exception $e) {
            $this->error("Exception: " . $e->getMessage());
        }
    }
}
