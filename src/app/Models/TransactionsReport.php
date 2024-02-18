<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class TransactionsReport extends Model
{
    use AsSource;

    public $table = 'transactions_report';
    public $connection = 'shop';
    public $fillable = [
        'attachment_id',
        'report_id'
    ];
}
