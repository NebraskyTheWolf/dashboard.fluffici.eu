<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class ReportReview extends Model
{
    use AsSource;

    public $table = 'report_review';
    public $fillable = [
        'attachment_id',
        'type',
        'message'
    ];
}
