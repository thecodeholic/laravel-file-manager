<?php

namespace App\Http\Requests;

use App\Models\File;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFileRequest extends StoreFolderRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'files.*' => ['required', 'file'],
            'folder_name' => ['string'],
            'parent_id' => [
                Rule::exists(File::class, 'id')->where(function (Builder $query) {
                    return $query->where('is_folder', '=', 1);
                }),
            ]
        ];
    }
}
