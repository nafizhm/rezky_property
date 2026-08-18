<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konten extends Model
{
    use HasFactory;

    protected $table = 'content';

    protected $fillable = [
        'jenis_content',
        'judul',
        'url_item',
        'nama_file',
        'artikel',
        'icon',
    ];

    public $timestamps = false;

}
