<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Orchid\Screen\AsSource;

class AutumnFile extends Model
{
    use AsSource;

    public $connection = 'autumn';
    public $collection = 'attachments';

    public $fillable = [
        'reported',
        'deleted',
        'dmca'
    ];

    public function totalSize(): int
    {
        $files = $this::all();

        $totalSize = 0;
        foreach ($files as $file) {
            $totalSize += $file->size;
        }

        return $totalSize;
    }
}
