<?php

namespace App\Models;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use HasFactory;
    protected $fillable = [
        'username',
        'password',
        'name'
    ];

    public function users() {
        return $this->hasMany(Contact::class, 'user_id', 'id');
    }

}
