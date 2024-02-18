<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentReports extends Model
{
    use HasFactory;

    public $table = 'current_reports';
    public $connection = 'shop';

}
