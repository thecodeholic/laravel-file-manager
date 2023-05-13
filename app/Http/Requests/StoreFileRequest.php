<?php

namespace App\Http\Requests;

use App\Models\File;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFileRequest extends ParentIdBaseRequest
{

    protected function prepareForValidation()
    {
        $paths = array_filter($this->relative_paths ?? [], fn($f) => $f != null);

        $this->merge([
            'file_paths' => $paths,
            'folder_name' => $this->detectFolderName($paths),
        ]);
    }

    protected function passedValidation()
    {
        $data = $this->validated();

        $this->replace([
            'file_tree' => $this->buildFileTree($this->file_paths, $data['files']),
        ]);
    }

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
                'files.*' => [
                    'required',
                    'file',
                    function ($attribute, $value, $fail) use ($user) {
                        // If we are uploading files not a folder
                        if (!$this->folder_name) {
                            // Check if the file's original name is unique in the database
                            $file = File::query()->where('name', $value->getClientOriginalName())
                                ->where('created_by', $user->id)
                                ->where('parent_id', $this->parent_id)
                                ->whereNull('deleted_at')
                                ->exists();
                            if ($file) {
                                $fail('File "' . $value->getClientOriginalName() . '" already exists.');
                            }
                        }
                    },
                ],
                'folder_name' => [
                    'nullable',
                    'string',
                    function ($attribute, $value, $fail) use ($user) {
                        // If we are uploading files not a folder
                        if ($this->folder_name) {
                            // Check if the file's original name is unique in the database
                            $file = File::query()->where('name', $value)
                                ->where('created_by', $user->id)
                                ->where('parent_id', $this->parent_id)
                                ->whereNull('deleted_at')
                                ->exists();
                            if ($file) {
                                $fail('Folder "' . $value . '" already exists.');
                            }
                        }
                    },
                ],
            ]
        );
    }

    private function detectFolderName($filePaths)
    {
        if (!$filePaths) {
            return null;
        }
        $parts = explode('/', $filePaths[0]);
        return $parts[0];
    }

    private function buildFileTree($filePaths, $files)
    {
        $filePaths = array_slice($filePaths, 0, count($files));
        $filePaths = array_filter($filePaths, fn($f) => $f != '');
        $tree = [];
        foreach ($filePaths as $ind => $filePath) {
            $parts = explode('/', $filePath);

            $currentNode = &$tree;

            foreach ($parts as $i => $part) {
                if (!isset($currentNode[$part])) {
                    $currentNode[$part] = [];
                }
                if ($i === count($parts) - 1) {
                    $currentNode[$part] = $files[$ind];
                } else {
                    $currentNode = &$currentNode[$part];
                }
            }
        }

        return $tree;
    }
}
