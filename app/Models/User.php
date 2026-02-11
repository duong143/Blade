<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'is_admin',
        'roles',
        'admin_news',
        'admin_banner',
        'admin_footer',

    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'roles' => 'array',
            'is_admin' => 'boolean',
        ];
    }



    public function hasRole(string $role): bool
    {
        // Chưa có roles
        if (empty($this->roles)) {
            return false;
        }

        // Admin tổng → có mọi quyền
        if (in_array('admin', $this->roles)) {
            return true;
        }

        // Kiểm tra quyền cụ thể
        return in_array($role, $this->roles);
    }
}
