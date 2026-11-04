<?php

namespace App\Services;

use App\Models\MasterDivision;
use App\Models\MasterOffice;
use App\Models\User;
use Illuminate\Support\Collection;

class DivisionService
{
    public function getDivisions(): Collection
    {
        return MasterDivision::with(['gm:id,name', 'office:id,name'])
            ->orderBy('name')
            ->get();
    }

    public function getFormOptions(): array
    {
        return [
            'generalManagers' => User::select('id', 'name')->orderBy('name')->get(),
            'offices' => MasterOffice::select('id', 'name')->orderBy('name')->get(),
        ];
    }

    public function create(array $attributes): MasterDivision
    {
        return MasterDivision::create($attributes)->load(['gm:id,name', 'office:id,name']);
    }

    public function update(MasterDivision $division, array $attributes): MasterDivision
    {
        $division->update($attributes);

        return $division->refresh()->load(['gm:id,name', 'office:id,name']);
    }

    public function delete(MasterDivision $division): bool
    {
        if ($division->departments()->exists()) {
            return false;
        }

        return (bool) $division->delete();
    }
}
