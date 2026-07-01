<?php

namespace App\Providers;

use App\Database\PostgresGrammarNoTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        DB::connection()->setSchemaGrammar(app(PostgresGrammarNoTransactions::class));
    }
}
