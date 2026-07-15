<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Professional;

use App\Application\Professional\UseCases\ListProfessionalsUseCase;
use App\Presentation\Http\Resources\Professional\ProfessionalResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class ListProfessionalsController
{
    public function __construct(private readonly ListProfessionalsUseCase $useCase)
    {
    }

    public function __invoke(): AnonymousResourceCollection
    {
        return ProfessionalResource::collection($this->useCase->handle());
    }
}
