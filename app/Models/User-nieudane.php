<?php
//jest to plik który jest dostosowany do naszej tabeli z userami bo nie zrobiłem ją ze standardem falafela User.php jest do domyślnej ale to kurwa jest trudne

//do czego musi mieć dostęp 
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    //tabela z bazy
    protected $table = 'user';
    // nie ma kiedy stworzono wienc wywalm to 
    public $timestamps = false;
    //nie da sie przesłać innych wartosci niż to 
    protected $fillable = ['nick', 'email', 'haslo', 'id_profil'];

    //haslo chronione
    protected $hidden = ['haslo'];

    // która kolumna to haslo
    public function getAuthPassword()
    {
        return $this->haslo;
    }
}
