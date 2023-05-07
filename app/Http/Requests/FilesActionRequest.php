<?php

namespace App\Http\Requests;

use App\Models\File;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilesActionRequest extends ParentIdBaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $user = $this->user();

        return array_merge(
            parent::rules(),
            [
                'all' => 'nullable|bool',
                'ids.*' => Rule::exists('files', 'id')->where(function ($query) use ($user) {
                    $query->where('created_by', $user->id);
                }),
            ]
        );
    }
}
