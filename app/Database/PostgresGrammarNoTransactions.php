<?php

namespace App\Database;

use Illuminate\Database\Schema\Grammars\PostgresGrammar;

class PostgresGrammarNoTransactions extends PostgresGrammar
{
    protected $transactions = false;
}
