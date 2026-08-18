<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UploudFile extends Model
{
    use HasFactory;

    protected $table = 'upload_file';

    protected $fillable = [
        'tanggal',
        'id_customer',
        'nama_file',
        'keterangan',
        'lampiran',
    ];

    public $timestamps = false;

}
