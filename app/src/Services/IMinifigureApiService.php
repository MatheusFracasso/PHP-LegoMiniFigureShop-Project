<?php

namespace App\Services;

interface IMinifigureApiService
{
    /**
     * Get all minifigures formatted for API response.
     *
     * @return array Array of minifigures with formatted fields for JSON
     */
    public function getMinifiguresForApi(): array;
}
