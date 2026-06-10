<?php

declare(strict_types=1);

namespace App\Enums;

enum ReportStatus: string
{
    case Pending = 'pendente';
    case Reviewed = 'revisado';
    case Dismissed = 'descartado';
    case ActionTaken = 'acao_tomada';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pendente',
            self::Reviewed => 'Revisado',
            self::Dismissed => 'Descartado',
            self::ActionTaken => 'Ação Tomada',
        };
    }

    public static function default(): self
    {
        return self::Pending;
    }
}
