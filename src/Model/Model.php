<?php
declare(strict_types=1);
namespace App\Model;

interface Model
{
    public static function getModelName(): string;
    public function toArray(): array;
}