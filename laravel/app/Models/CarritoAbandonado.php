<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarritoAbandonado extends Model
{
    protected $table = 'carritos_abandonados';

    protected $fillable = [
        'cliente_id',
        'email',
        'items',
        'items_count',
        'total_estimado',
        'last_activity_at',
        'reminder_sent_at',
        'completed_at',
    ];

    protected $casts = [
        'items' => 'array',
        'items_count' => 'integer',
        'total_estimado' => 'decimal:2',
        'last_activity_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
}
