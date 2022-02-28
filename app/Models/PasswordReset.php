<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PasswordReset extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'token_signature',
        'expires_at',
        'used_token'
    ];

    /**
     * Get the user associated with the token.
     */
    public function user()
    {
       return $this->belongsTo(User::class,'user_id');
    }
}
