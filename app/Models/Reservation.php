<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'user_id',
        'screening_id',
        'ticket_type_id',
        'transaction_id',
        'quantity',
        'unit_price',
        'total_amount',
        'currency',
        'status',
        'confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'quantity'     => 'integer',
            'unit_price'   => 'decimal:2',
            'total_amount' => 'decimal:2',
            'confirmed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function screening()
    {
        return $this->belongsTo(Screening::class);
    }

    public function ticketType()
    {
        return $this->belongsTo(TicketType::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
