<?php

namespace App\Interfaces;

interface ReportInterface
{
    public function getCategoryViews(int $businessId): array;
    
    public function getCategoryViewStats(): array;
}
