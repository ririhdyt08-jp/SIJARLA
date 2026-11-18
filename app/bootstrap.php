<?php
declare(strict_types=1);

const SITE = 'SIJARLA';
const SITE_FULL = 'Sistem Informasi Jaga Rimba dan Lahan';
const SNAPSHOT = '16 November 2026';

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function icon(string $name): string {
    $shape = match ($name) {
        'area' => '<rect x="4" y="4" width="16" height="16" rx="2"/><path d="m8 16 8-8m-4 0h4v4"/>',
        'pin' => '<path d="M20 10c0 6-8 11-8 11S4 16 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/>',
        'flame' => '<path d="M12 22c-4 0-7-3-7-7 0-3 2-5 3-6 0 2 1 3 2 3 0-4 2-7 4-10 0 4 1 6 3 8 1 1 2 3 2 5 0 4-3 7-7 7Z"/><path d="M9 19c0-2 2-3 3-5 1 2 3 3 3 5"/>',
        'phone' => '<path d="M5 3h3l2 5-2 2a14 14 0 0 0 6 6l2-2 5 2v3a2 2 0 0 1-2 2C10 21 3 14 3 5a2 2 0 0 1 2-2Z"/>',
        'tree' => '<path d="m12 3 5 6h-2l5 7H4l5-7H7l5-6Zm0 13v5"/>',
        'scales' => '<path d="M12 3v18m-5 0h10M4 7h16M4 7l-2 6h4L4 7Zm16 0-2 6h4l-2-6Z"/>',
        'home' => '<path d="m3 10 9-7 9 7v10H3V10Zm6 10v-6h6v6"/>',
        'laptop' => '<rect x="4" y="4" width="16" height="12" rx="2"/><path d="M4 16l-2 4h20l-2-4"/>',
        'file' => '<path d="M5 3h9l5 5v13H5V3Zm9 0v5h5M8 12h8m-8 4h8"/>',
        'book' => '<path d="M12 6c-2-2-6-3-10-2v14c4-1 8 0 10 2 2-2 6-3 10-2V4c-4-1-8 0-10 2Zm0 0v14"/>',
        'calendar' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M7 3v4m10-4v4M3 10h18"/>',
        'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'search' => '<circle cx="10.5" cy="10.5" r="6"/><path d="m15 15 5 5"/>',
        'globe' => '<circle cx="12" cy="12" r="9"/><ellipse cx="12" cy="12" rx="4" ry="9"/><path d="M3 12h18"/>',
    };

    return '<svg class="ui-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $shape . '</svg>';
}

function tanggal(string $date): string {
    $months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    [$year, $month, $day] = explode('-', $date);
    return (int) $day . ' ' . $months[(int) $month] . ' ' . $year;
}

function luas(float $area): string {
    return rtrim(rtrim(number_format($area, 3, ',', '.'), '0'), ',') . ' ha';
}

$incidents = json_decode(file_get_contents(__DIR__ . '/../data/incidents.json'), true, 512, JSON_THROW_ON_ERROR);
usort($incidents, static fn (array $a, array $b): int => strcmp($b['date'], $a['date']));
