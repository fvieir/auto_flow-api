<?php

declare(strict_types=1);

namespace App\Domain\Appointment\Exceptions;

use App\Domain\Shared\Exceptions\BusinessRuleException;

final class ServiceNotOfferedException extends BusinessRuleException
{
    public function __construct()
    {
        parent::__construct('Este professional não executa o serviço selecionado.');
    }
}
