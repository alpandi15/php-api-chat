<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FriendResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $userId = auth()->id();
        $friend = null;
        if ($this->mee) {
            $friend = $this->mee;
        }
        if ($this->friendd) {
            $friend = $this->friendd;
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'updated_at' => $this->updated_at,
            'berteman' => $friend
        ];
    }
}
