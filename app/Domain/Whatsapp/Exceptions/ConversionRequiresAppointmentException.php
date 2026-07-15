<?php

declare(strict_types=1);

namespace App\Domain\Whatsapp\Exceptions;

use App\Domain\Shared\Exceptions\BusinessRuleException;

final class ConversionRequiresAppointmentException extends BusinessRuleException
{
    public function __construct()
    {
        parent::__construct('Não é possível mover a conversa para "convertido" sem um agendamento vinculado a este lead — informe appointment_id ou crie o agendamento antes.');
    }
}
