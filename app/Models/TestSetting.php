<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestSetting extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    protected $attributes = [

        'settings' => '{
           "meto_cookie": 0,
           "show_sig_list": 1,
           "pay_gas": 1,
           "pay_gas_amount": 1000000}',
    ];
}
