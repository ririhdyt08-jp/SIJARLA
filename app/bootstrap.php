<?php
declare(strict_types=1);

const SITE = 'SIJARLA';
const SITE_FULL = 'Sistem Informasi Jaga Rimba dan Lahan';
const SNAPSHOT = '16 November 2026';

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
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
