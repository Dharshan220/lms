<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class Helper
{
    public static function generateCertificateNumber(): string
    {
        return 'NS-' . strtoupper(Str::random(4)) . '-' . date('Ymd') . '-' . strtoupper(Str::random(4));
    }

    public static function formatDuration(int $minutes): string
    {
        if ($minutes < 60) return $minutes . ' min';
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        return $hours . 'h ' . ($mins > 0 ? $mins . 'm' : '');
    }

    public static function getLevelFromXp(int $xp): int
    {
        return floor(sqrt($xp / 100)) + 1;
    }

    public static function getXpForNextLevel(int $level): int
    {
        return pow($level - 1, 2) * 100;
    }

    public static function timeAgo($timestamp): string
    {
        $diff = \Carbon\Carbon::parse($timestamp)->diffForHumans();
        return $diff;
    }

    public static function getGradeColor($grade): string
    {
        if ($grade >= 90) return 'success';
        if ($grade >= 70) return 'primary';
        if ($grade >= 50) return 'warning';
        return 'danger';
    }
}
