<?php

namespace App\Http\Requests;

use App\Models\File;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFolderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
