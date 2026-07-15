<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Professional\Repositories;

use App\Domain\Professional\Entities\Professional;
use App\Domain\Professional\Repositories\ProfessionalRepositoryInterface;
use App\Infrastructure\Persistence\Professional\Mappers\ProfessionalMapper;
use App\Infrastructure\Persistence\Professional\Models\ProfessionalModel;

final class ProfessionalRepository implements ProfessionalRepositoryInterface
{
    public function __construct(private readonly ProfessionalMapper $mapper)
    {
    }

    public function findById(int $id): ?Professional
    {
        $model = ProfessionalModel::find($id);

        return $model !== null ? $this->mapper->toDomain($model) : null;
    }

    public function all(): array
    {
        return ProfessionalModel::orderBy('name')
            ->get()
            ->map(fn (ProfessionalModel $model) => $this->mapper->toDomain($model))
            ->all();
    }

    public function listByService(int $serviceId): array
    {
        return ProfessionalModel::whereHas('services', fn ($query) => $query->where('services.id', $serviceId))
            ->orderBy('name')
            ->get()
            ->map(fn (ProfessionalModel $model) => $this->mapper->toDomain($model))
            ->all();
    }

    public function offersService(int $professionalId, int $serviceId): bool
    {
        return ProfessionalModel::where('id', $professionalId)
            ->whereHas('services', fn ($query) => $query->where('services.id', $serviceId))
            ->exists();
    }

    public function create(Professional $professional, array $serviceIds): Professional
    {
        $model = ProfessionalModel::create([
            'name' => $professional->name(),
            'phone' => $professional->phone(),
            'email' => $professional->email(),
        ]);

        $model->services()->sync($serviceIds);

        return $this->mapper->toDomain($model);
    }

    public function update(Professional $professional, array $serviceIds): Professional
    {
        $model = ProfessionalModel::findOrFail($professional->id());

        $model->update([
            'name' => $professional->name(),
            'phone' => $professional->phone(),
            'email' => $professional->email(),
        ]);

        $model->services()->sync($serviceIds);

        return $this->mapper->toDomain($model);
    }

    public function delete(int $id): void
    {
        ProfessionalModel::findOrFail($id)->delete();
    }
}
