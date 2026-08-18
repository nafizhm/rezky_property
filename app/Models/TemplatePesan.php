<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplatePesan extends Model
{
    use HasFactory;

    protected $table = 'template_pesan';

    protected $fillable = [
        'nama_template',
        'isi_template',
        'jenis_pesan',
    ];

    public $timestamps = false;
}
