<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'filename',
        'file_path',
        'status',
    ];

    /**
     * Get the user who requested the report.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
