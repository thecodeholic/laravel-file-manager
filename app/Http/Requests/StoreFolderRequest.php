<?php

namespace App\Http\Requests;

use App\Models\File;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFolderRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'parent_id' => [
                Rule::exists(File::class, 'id')->where(function (Builder $query) {
                    return $query->where('is_folder', '=', 1);
                }),
            ]
        ];
    }
}
