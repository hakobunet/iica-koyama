<?php

namespace App\Http\Resources;

use Illuminate\Http\Request; // 👈 ここが「Illuminate\Http\Request」になっているか確認
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'score'      => $this->score,
            'evaluation' => $this->score >= 80 ? 'A' : 'B',  // 計算値も追加できる
            'created_at' => $this->created_at->format('Y/m/d'),
            // 不要なフィールド（password など）は含めない
        ];
    }

}
