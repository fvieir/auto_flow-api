<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Professional;

use App\Application\Professional\UseCases\DeleteProfessionalUseCase;
use Illuminate\Http\Response;

final class DeleteProfessionalController
{
    public function __construct(private readonly DeleteProfessionalUseCase $useCase)
    {
    }

    public function __invoke(int $professional): Response
    {
        $this->useCase->handle($professional);

        return response()->noContent();
    }
}
