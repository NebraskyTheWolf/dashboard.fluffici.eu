<?php


namespace App\Models;

use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;
use Illuminate\Database\Eloquent\Model;

class ShopProducts extends Model
{
    use AsSource, Chartable;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'price',
        'displayed',
        'image_path',
        'deleted_at'
    ];

    public function getImage(): string
    {
        if ($this->image_path !== null) {
            return env('AUTUMN_HOST', 'https://autumn.rsiniya.uk/attachments/') . $this->image_path;
        } else {
            return env('AUTUMN_HOST', 'https://autumn.rsiniya.uk/attachments/') . 'E1dC5nCVCCSnYwTmUTS7JMYAZiwOeb1xa8XCFPmu4j';
        }
    }
}
