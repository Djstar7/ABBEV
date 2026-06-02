<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    use HasFactory;

    protected $fillable = [
        'screening_id',
        'name',
        'price',
        'currency',
        'capacity',
        'sold_seats',
    ];

    protected function casts(): array
    {
        return [
            'price'      => 'decimal:2',
            'capacity'   => 'integer',
            'sold_seats' => 'integer',
        ];
    }

    public function screening()
    {
        return $this->belongsTo(Screening::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /** Places encore disponibles à la vente. */
    public function availableSeats(): int
    {
        return max(0, $this->capacity - $this->sold_seats);
    }
}
