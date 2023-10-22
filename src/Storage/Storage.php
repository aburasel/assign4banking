<?php

declare(strict_types=1);

namespace App\Storage;

interface Storage
{
    public function save(string $model, array $data): bool;    

    public function loadAll(string $model): array;

    public function loadWhere($model, array $where): ?array;
    
    public function loadJoinWhere($model1,$model2,array $where,array $joinBetween,array $select): ?array;

}
