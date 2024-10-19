<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Budget;
use App\Models\Goal;
use App\Models\Report;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed user roles
        DB::table('user_roles')->insert([
            [
                'name' => 'Master',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'User',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);

        // Seed specific users
        DB::table('users')->insert([
            [
                'user_role_id' => 1,
                'name' => 'Lana Septiana',
                'email' => 'lana.septiana2@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password' => bcrypt('password'),
                'remember_token' => Str::random(10),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_role_id' => 2,
                'name' => 'Lana Septiana',
                'email' => 'lana.septiana1@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password' => bcrypt('password'),
                'remember_token' => Str::random(10),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);

        // Seed random users, accounts, transactions, budgets, goals, and reports
        User::factory(10)->create()->each(function ($user) {
            // Create accounts for each user
            $accounts = Account::factory(3)->create([
                'user_id' => $user->id,
            ]);

            // Assign transactions to user's accounts
            $accounts->each(function ($account) use ($user) {
                Transaction::factory(5)->create([
                    'user_id' => $user->id,
                    'account_id' => $account->id,
                ]);
            });

            // Create budgets, goals, and reports for each user
            $user->budgets()->saveMany(Budget::factory(2)->make());
            $user->goals()->saveMany(Goal::factory(2)->make());
            $user->reports()->saveMany(Report::factory(1)->make());
        });
    }
}