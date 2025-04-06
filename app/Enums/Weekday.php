<?php

namespace App\Enums;

enum Weekday: string
{
    case MONDAY = 'monday';
    case TUESDAY = 'tuesday';
    case WEDNESDAY = 'wednesday';
    case THURSDAY = 'thursday';
    case FRIDAY = 'friday';
    case SATURDAY = 'saturday';
    case SUNDAY = 'sunday';

    public function label(): string
    {
        return match ($this) {
            self::MONDAY => '月',
            self::TUESDAY => '火',
            self::WEDNESDAY => '水',
            self::THURSDAY => '木',
            self::FRIDAY => '金',
            self::SATURDAY => '土',
            self::SUNDAY => '日',
        };
    }

    public static function toArray(): array
    {
        return array_combine(
            array_map(fn($day) => $day->value, self::cases()),
            array_map(fn($day) => $day->label(), self::cases())
        );
    }
}
