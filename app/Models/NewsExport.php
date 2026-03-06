<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsExport extends Model
{
    protected $fillable = [
        'export_file',
        'job_batch_id',
    ];

    public function jobBatch(): BelongsTo
    {
        return $this->belongsTo(JobBatch::class, 'job_batch_id');
    }
}
