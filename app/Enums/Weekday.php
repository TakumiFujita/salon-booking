<?php

namespace App\Enums;

enum Weekday: int
{
    case SUNDAY = 0;
    case MONDAY = 1;
    case TUESDAY = 2;
    case WEDNESDAY = 3;
    case THURSDAY = 4;
    case FRIDAY = 5;
    case SATURDAY = 6;

    public function label(): string
    {
        return match ($this) {
            self::SUNDAY => '日',
            self::MONDAY => '月',
            self::TUESDAY => '火',
            self::WEDNESDAY => '水',
            self::THURSDAY => '木',
            self::FRIDAY => '金',
            self::SATURDAY => '土',
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
