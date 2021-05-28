<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'number',
        'transaction_id',
        'amount',
        'method',
        'status',
        'token',
        'payloads',
        'payment_types',

    ];


    public const PAYMENT_CHANNELS = [
        'bca_va', 'bni_va', 'other_va', 'gopay', 'indomaret', 'akulaku'
    ];

    public const EXPIRY_DURATION = '1';
    public const EXPIRY_UNIT = 'days';

    public const CHALLENGE = 'challenge';
    public const SUCCESS = 'success';
    public const SETTLEMENT = 'settlement';
    public const PENDING = 'pending';
    public const DENY = 'deny';
    public const EXPIRE = 'expire';
    public const CANCEL = 'cancel';

    public const PAYMENTCODE = 'PAY';

    public static function generateCode()
    {
        $dateCode = self::PAYMENTCODE . '/' . date('Ymd') . '/' . \General::integerToRoman(date('m')) . '/' . \General::integerToRoman(date('d')) . '/';

        $lastOrder = self::select([\DB::raw('MAX(payments.number) AS last_code')])
            ->where('number', 'like', $dateCode . '%')
            ->first();

        $lastOrderCode = !empty($lastOrder) ? $lastOrder['last_code'] : null;

        $orderCode = $dateCode . '00001';
        if ($lastOrderCode) {
            $lastOrderNumber = str_replace($dateCode, '', $lastOrderCode);
            $nextOrderNumber = sprintf('%05d', (int)$lastOrderNumber + 1);

            $orderCode = $dateCode . $nextOrderNumber;
        }

        if (self::_isOrderCodeExists($orderCode)) {
            return generateOrderCode();
        }

        return $orderCode;
    }

    /**
     * Check if the generated order code is exists
     *
     * @param string $orderCode order code
     *
     * @return void
     */
    private static function _isOrderCodeExists($orderCode)
    {
        return self::where('number', '=', $orderCode)->exists();
    }
}
