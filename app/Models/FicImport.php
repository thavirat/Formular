<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FicImport extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'applied_at' => 'datetime',
    ];

    public function lines(): HasMany
    {
        return $this->hasMany(FicImportLine::class, 'fic_import_id')->orderBy('row_no')->orderBy('id');
    }

    public function importedBy(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'imported_by');
    }
}
