<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $fillable = [
        'code',
        'type',
        'status',
        'ticket_date',
        'teller_id',
    ];

    protected $casts = [
        'ticket_date' => 'date',
    ];

    /**
     * Get today's date in Y-m-d format
     */
    public static function today(): string
    {
        return now()->format('Y-m-d');
    }

    /**
     * Scope a query to only include tickets for today
     */
    public function scopeForToday($query)
    {
        return $query->whereDate('ticket_date', self::today());
    }

    /**
     * Scope a query to only include waiting tickets
     */
    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }

    /**
     * Scope a query to only include serving tickets
     */
    public function scopeServing($query)
    {
        return $query->where('status', 'serving');
    }

    /**
     * Scope a query to only include done tickets
     */
    public function scopeDone($query)
    {
        return $query->where('status', 'done');
    }

    /**
     * Scope a query to only include tickets for a specific category
     */
    public function scopeForCategory($query, $categoryId)
    {
        return $query->where('type', $categoryId);
    }

    /**
     * Get the full ticket code with prefix (e.g., P001, D002)
     * Alias for getCodeAttribute() to maintain backward compatibility
     */
    public function getFullCodeAttribute(): string
    {
        return $this->code; // Delegate to getCodeAttribute() to avoid duplication
    }

    /**
     * Accessor to return full code when accessing $ticket->code
     * This maintains backward compatibility
     */
    public function getCodeAttribute($value): string
    {
        // If value already contains prefix (old data format), return as is
        if ($value && preg_match('/^[A-Z]+\d+$/', $value)) {
            return $value;
        }
        
        // Get the raw number from attributes
        $number = $this->attributes['code'] ?? $value ?? '001';
        
        // Only load category if not already loaded and type exists
        if (!$this->relationLoaded('category') && $this->type) {
            // Use select to only load needed fields
            $this->load('category:id,prefix');
        }
        
        $prefix = $this->category?->prefix ?? '';
        return $prefix . $number;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TellerCategory::class, 'type');
    }

    public function teller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teller_id');
    }

    public function servings(): HasMany
    {
        return $this->hasMany(Serving::class);
    }
}
