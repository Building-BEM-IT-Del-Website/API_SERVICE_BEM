<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrmawaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
              'jenis_ormawa' => [
                'id' => $this->jenisOrmawa->id ?? null,
                'nama' => $this->jenisOrmawa->nama ?? null,
            ],
            'deskripsi' => $this->deskripsi,
            'logo' => asset('storage/' . $this->logo),
            'visi' => $this->visi,
            'misi' => $this->misi,
            'status' => $this->status,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deleted_at,
        ];
    }
}
