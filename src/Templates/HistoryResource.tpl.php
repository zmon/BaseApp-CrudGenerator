<?php

namespace App\Http\Resources;

use App\Models\History;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class [[model_uc]]HistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        if (is_array($this->resource) || !is_object($this->resource) || get_class($this->resource) !== History::class) {
            return [];
        }

        // get previous history if exists
        $previous = History::where('historyable_type', $this->historyable_type)
            ->where('historyable_id', $this->historyable_id)
            ->where('created_at', '<', $this->created_at)
            ->orderBy('created_at', 'desc')
            ->first();
        $previous_reason_for_change = $previous? $previous->reason_for_change: null;
        return [

            'diff' => $this->diff ?? null,
            'id' => $this->id,
            'old' => [
                'user_name' => $this->old['modified_by'] ?? null ? User::find($this->old['modified_by'])->name : "N/A",
                'created_at' => $this->old['created_at'] ?? null,
            ],
            'new' => [
                'user_name' => $this->user->name ?? "",
                'created_at' => $this->new['created_at'] ?? $this->created_at,
            ],
            'reason_for_change' => $this->reason_for_change,
            'previous_reason_for_change' => $previous_reason_for_change

        ];
    }
}
