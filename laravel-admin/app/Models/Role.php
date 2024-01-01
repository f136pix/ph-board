<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Role
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\RoleFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
 * @mixin \Eloquent
 */
class Role extends Model
{
    use HasFactory;

    protected int $id;
    protected string $name;
    protected string $email;
    protected string $password;

    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
    ];
    public $timestamps = false;

    // many to one relationship
    public function users(){
        return $this->hasMany(User::class);
    }

    // many to many
    public function permissions(){
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }
}
