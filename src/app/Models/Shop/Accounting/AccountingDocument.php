<?php

namespace App\Models\Shop\Accounting;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class AccountingDocument extends Model
{
    use AsSource;

    public $connection = 'shop';

    public $table = 'accounting_document';
}
