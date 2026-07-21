<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'group_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
        ];
    }

    // ─── Relationships ───────────────────────────────────────────────────────
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    // ─── RBAC Helper Methods ─────────────────────────────────────────────────
    public function hasAccessToMenu(string $menuKey): bool
    {
        // Super Admin (role === superadmin ATAU memiliki group yang punya hak akses penuh)
        if ($this->role === 'superadmin') {
            return true;
        }

        // Jika user memiliki kelompok menu (group)
        if ($this->group) {
            $menus = $this->group->accessible_menus ?? [];
            return in_array($menuKey, $menus);
        }

        return false;
    }

    public function hasRole(array|string $roles): bool
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles);
        }
        return $this->role === $roles;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isStafLayanan(): bool
    {
        return $this->role === 'staf_layanan';
    }

    public function isStafHumas(): bool
    {
        return $this->role === 'staf_humas';
    }
}
