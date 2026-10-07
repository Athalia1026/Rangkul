<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Donation extends Model
{
    use LogsActivity;

    protected string $activityModule = 'donation';

    protected $table = 'donations';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'id_campaign', 'id_donatur', 'nominal',
        'note', 'status', 'anonim', 'transaction_id', 'payment_type', 'paid_at', 'snap_token', 'payment_url', 'payment_fee'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn ($model) => $model->id = (string) Str::uuid());
    }

    public function donor()
    {
        return $this->belongsTo(Donor::class, 'id_donatur');
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'id_campaign');
    }

    /** Nama metode pembayaran yang mudah dibaca, dari payment_type Midtrans. */
    public function paymentMethodLabel(): string
    {
        if (!$this->payment_type) {
            return '-';
        }

        return [
            'qris' => 'QRIS',
            'gopay' => 'GoPay',
            'shopeepay' => 'ShopeePay',
            'bank_transfer' => 'Transfer Bank',
            'echannel' => 'Transfer Bank',
            'permata' => 'Transfer Bank',
            'credit_card' => 'Kartu Kredit',
            'cstore' => 'Gerai Retail',
        ][$this->payment_type] ?? ucwords(str_replace('_', ' ', $this->payment_type));
    }
}
