<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_barang';

    protected $table = 'barangs';

    protected $guarded = ['id_barang'];
    protected $fillable = [
        'name',
        'category',
        'image',
        'stock',
        'price',
        'note'
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'barang_tag', 'barang_id', 'tag_id');
    }
}
