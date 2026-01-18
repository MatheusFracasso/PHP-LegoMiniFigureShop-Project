<?php

namespace App\Services;

// API response formatting for minifigures
interface IMinifigureApiService
{
    // Get minifigures formatted for JSON API
    public function getMinifiguresForApi(): array;
}
