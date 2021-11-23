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

        $old_user_name = 'N/A';
        $modified_by_id = data_get($this->old,'modified_by', 'false');
        if ($modified_by_id && $modified_by_id != -1) {
            $old_user_name = data_get(User::find($modified_by_id), 'name', 'n/a');
        }

        return [

            'diff' => $this->diff ?? null,
            'id' => $this->id,
            'old' => [
                'user_name' => $old_user_name,
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
