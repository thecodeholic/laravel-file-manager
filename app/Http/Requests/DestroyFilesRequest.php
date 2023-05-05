<?php

namespace App\Http\Requests;

use App\Models\File;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DestroyFilesRequest extends StoreFolderRequest
{
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $user = $this->user();
        return [
            'parent_id' => [
                Rule::exists(File::class, 'id')->where(function (Builder $query) {
                    return $query->where('is_folder', '=', 1);
                }),
            ],
            'delete_all' => 'nullable|bool',
            'delete_ids.*' => Rule::exists('files', 'id')->where(function ($query) use ($user) {
                $query->where('created_by', $user->id);
            }),
        ];
    }
}
