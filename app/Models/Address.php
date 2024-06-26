<?php

namespace App\Models;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;
    
    public function contact() {
        return $this->belongsTo(Contact::class, 'contact_id', 'id');
    }

}
