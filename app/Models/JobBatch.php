<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobBatch extends Model
{
    protected $table = 'job_batches';

    public $incrementing = false;

    protected $keyType = 'string';

    public function newsExports(): HasMany
    {
        return $this->hasOne(NewsExport::class, 'job_batch_id');
    }
}
