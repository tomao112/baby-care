<?php

namespace App\Repositories;

use App\Models\Child;
use Illuminate\Support\Facades\DB;

class ChildRepository
{
    public function update(Child $child, array $data)
    {
        $child->update($data);
        return $child->fresh();
    }
}
