<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'preference_id',
        'payment_id',
        'external_reference',
        'title',
        'amount',
        'currency',
        'status',
        'payment_details',
        'user_id',
        'related_id',
        'related_type'
    ];
    
    protected $casts = [
        'payment_details' => 'array',
        'amount' => 'decimal:2'
    ];
    
    /**
     * Obtener el usuario asociado al pago
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Obtener el modelo relacionado con el pago (polimórfico)
     */
    public function related()
    {
        return $this->morphTo();
    }
    
    /**
     * Determinar si el pago fue aprobado
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }
    
    /**
     * Determinar si el pago está pendiente
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }
    
    /**
     * Determinar si el pago fue rechazado
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }
    
    /**
     * Formato de fecha amigable
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d/m/Y H:i');
    }
    
    /**
     * Formato de monto con símbolo de moneda
     */
    public function getFormattedAmountAttribute()
    {
        $symbols = [
            'ARS' => '$',
            'MXN' => '$',
            'COP' => '$',
            'CLP' => '$',
            'UYU' => '$U',
            'PEN' => 'S/',
            'BRL' => 'R$',
            'USD' => 'US$'
        ];
        
        $symbol = $symbols[$this->currency] ?? '';
        return $symbol . number_format($this->amount, 2, ',', '.');
    }
    
    /**
     * Obtener clase CSS según el estado del pago
     */
    public function getStatusClassAttribute()
    {
        $classes = [
            'approved' => 'bg-success',
            'pending' => 'bg-warning',
            'rejected' => 'bg-danger',
            'in_process' => 'bg-info',
            'cancelled' => 'bg-secondary'
        ];
        
        return $classes[$this->status] ?? 'bg-secondary';
    }
}