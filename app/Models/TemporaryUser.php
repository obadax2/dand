<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'username', 'dob_day', 'dob_month', 'dob_year', 'email', 'verification_code'
    ];

    // You can add any relationships or custom methods here as needed
}