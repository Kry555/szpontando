<?php
//musiałem jesdnak przerzucić sie na tabele users i ją zmodyfikować
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'nick',
        'email',
        'password',
        'czy_admin',
        'id_profil'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
//jak na pałe tworzyć usera
//php artisan tinker
// use App\Models\User;
// use Illuminate\Support\Facades\Hash;

// User::create([
//     'nick' => 'kryx',
//     'email' => 'kry@email.com',
//     'password' => Hash::make(''),
// ]);
//exit