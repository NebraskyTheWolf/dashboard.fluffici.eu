<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class AccountingDocument extends Model
{
    use AsSource;

    public $connection = 'shop';

    public $table = 'accounting_document';
}
