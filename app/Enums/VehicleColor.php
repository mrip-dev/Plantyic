<?php

namespace App\Enums;

enum VehicleColor: string
{
    case BLACK   = 'black';
    case WHITE   = 'white';
    case SILVER  = 'silver';
    case GREY    = 'grey';
    case RED     = 'red';
    case MAROON  = 'maroon';
    case BLUE    = 'blue';
    case NAVY    = 'navy';
    case GREEN   = 'green';
    case LIME    = 'lime';
    case YELLOW  = 'yellow';
    case OLIVE   = 'olive';
    case ORANGE  = 'orange';
    case BROWN   = 'brown';
    case BEIGE   = 'beige';
    case GOLD    = 'gold';
    case PURPLE  = 'purple';
    case VIOLET  = 'violet';
    case PINK    = 'pink';
    case CYAN    = 'cyan';
    case TEAL    = 'teal';

    public function label(): string
    {
        return match ($this) {
            self::BLACK  => 'Black',
            self::WHITE  => 'White',
            self::SILVER => 'Silver',
            self::GREY   => 'Grey',
            self::RED    => 'Red',
            self::MAROON => 'Maroon',
            self::BLUE   => 'Blue',
            self::NAVY   => 'Navy',
            self::GREEN  => 'Green',
            self::LIME   => 'Lime',
            self::YELLOW => 'Yellow',
            self::OLIVE  => 'Olive',
            self::ORANGE => 'Orange',
            self::BROWN  => 'Brown',
            self::BEIGE  => 'Beige',
            self::GOLD   => 'Gold',
            self::PURPLE => 'Purple',
            self::VIOLET => 'Violet',
            self::PINK   => 'Pink',
            self::CYAN   => 'Cyan',
            self::TEAL   => 'Teal',
        };
    }

    /**
     * Return value => label pairs for selects.
     *
     * @return array<string,string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->label()])
            ->toArray();
    }

    /**
     * Optional: get enum from stored value (returns null if invalid)
     */
    public static function tryFromValue(string $value): ?self
    {
        try {
            return self::from($value);
        } catch (\ValueError $e) {
            return null;
        }
    }
}
