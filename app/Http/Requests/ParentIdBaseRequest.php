<?php

namespace App\Http\Requests;

use App\Models\File;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ParentIdBaseRequest extends FormRequest
{
    public ?File $parent = null;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $this->parent = File::where('id', $this->input('parent_id'))->first();
        if ($this->parent && !$this->parent->isRoot() && !$this->parent->isOwnedBy($this->user()->id)) {
            return false;
        }

        return true;
    }

    public function rules(): array
    {
        $user = $this->user();

        return [
            'parent_id' => [
                Rule::exists(File::class, 'id')
                    ->where(function (Builder $query) use ($user) {
                        return $query->where('is_folder', '=', 1)->where('created_by', '=', $user->id);
                    }),
            ]
        ];
    }
}
