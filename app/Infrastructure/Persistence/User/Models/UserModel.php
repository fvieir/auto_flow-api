<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\User\Models;

use App\Infrastructure\Persistence\Tenant\Models\TenantModel;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Model Eloquent da tabela `users` (camada de persistência). É o Authenticatable
 * usado pelos guards/Sanctum. Não é uma entidade de domínio.
 */
#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
final class UserModel extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * Tenants aos quais o usuário pertence (pivot tenant_users, com role).
     */
    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(TenantModel::class, 'tenant_users', 'user_id', 'tenant_id')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
