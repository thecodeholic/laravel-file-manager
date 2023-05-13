<?php

namespace App\Http\Requests;

use App\Models\File;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFolderRequest extends ParentIdBaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $user = $this->user();

        return array_merge(
            parent::rules(),
            [
                'name' => [
                    'required',
                    Rule::unique('files', 'name')
                        ->where('created_by', $user->id)
                        ->where('parent_id', $this->parent_id)
                        ->whereNull('deleted_at')
                ],
            ]
        );
    }

    public function messages()
    {
        return [
            'name.unique' => 'Folder ":input" already exists'
        ];
    }
}
