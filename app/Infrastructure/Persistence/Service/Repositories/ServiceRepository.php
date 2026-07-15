<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Service\Repositories;

use App\Domain\Service\Entities\Service;
use App\Domain\Service\Repositories\ServiceRepositoryInterface;
use App\Infrastructure\Persistence\Service\Mappers\ServiceMapper;
use App\Infrastructure\Persistence\Service\Models\ServiceModel;

final class ServiceRepository implements ServiceRepositoryInterface
{
    public function __construct(private readonly ServiceMapper $mapper)
    {
    }

    public function findById(int $id): ?Service
    {
        $model = ServiceModel::find($id);

        return $model !== null ? $this->mapper->toDomain($model) : null;
    }

    public function all(): array
    {
        return ServiceModel::orderBy('name')
            ->get()
            ->map(fn (ServiceModel $model) => $this->mapper->toDomain($model))
            ->all();
    }

    public function create(Service $service): Service
    {
        $model = ServiceModel::create([
            'name' => $service->name(),
            'duration_minutes' => $service->durationMinutes(),
            'price' => $service->price(),
        ]);

        return $this->mapper->toDomain($model);
    }

    public function update(Service $service): Service
    {
        $model = ServiceModel::findOrFail($service->id());

        $model->update([
            'name' => $service->name(),
            'duration_minutes' => $service->durationMinutes(),
            'price' => $service->price(),
        ]);

        return $this->mapper->toDomain($model);
    }

    public function delete(int $id): void
    {
        ServiceModel::findOrFail($id)->delete();
    }
}
