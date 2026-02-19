<?php

namespace App\Enums;

enum MileageRange: int
{
    case FROM_0_TO_20000           = 1;
    case FROM_20000_TO_40000       = 2;
    case FROM_40000_TO_60000       = 3;
    case FROM_60000_TO_90000       = 4;
    case FROM_90000_TO_120000      = 5;
    case FROM_120000_TO_150000     = 6;
    case FROM_150000_TO_180000     = 7;
    case FROM_180000_TO_220000     = 8;
    case FROM_220000_TO_250000     = 9;
    case FROM_250000_TO_300000     = 10;
    case ABOVE_300000              = 11;

    public function label(): string
    {
        return match ($this) {
            self::FROM_0_TO_20000       => '0–20,000 kms',
            self::FROM_20000_TO_40000   => '20,000–40,000 kms',
            self::FROM_40000_TO_60000   => '40,000–60,000 kms',
            self::FROM_60000_TO_90000   => '60,000–90,000 kms',
            self::FROM_90000_TO_120000  => '90,000–120,000 kms',
            self::FROM_120000_TO_150000 => '120,000–150,000 kms',
            self::FROM_150000_TO_180000 => '150,000–180,000 kms',
            self::FROM_180000_TO_220000 => '180,000–220,000 kms',
            self::FROM_220000_TO_250000 => '220,000–250,000 kms',
            self::FROM_250000_TO_300000 => '250,000–300,000 kms',
            self::ABOVE_300000          => '300,000+ kms',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
            ->toArray();
    }
}
