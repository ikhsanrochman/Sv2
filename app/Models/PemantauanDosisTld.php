<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PemantauanDosisTld extends Model
{
    use HasFactory;

    protected $table = 'pemantauan_dosis_tld';

    protected $fillable = [
        'user_id',
        'dosis_pertahun',
        'tahun',
    ];

    /**
     * Get the user that owns the pemantauan dosis record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
