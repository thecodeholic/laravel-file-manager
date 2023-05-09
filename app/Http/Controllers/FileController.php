<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilesActionRequest;
use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\StoreFolderRequest;
use App\Http\Requests\UpdateFileRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use ZipArchive;

class FileController extends Controller
{

    public function myFiles(Request $request, string $folder = null)
    {
        if ($folder) {
            $folder = File::where('created_by', '=', request()->user()->id)
                ->where('path', '=', $folder)
                ->firstOrFail();
        }

        if (!$folder) {
            $folder = $this->getRoot();
        }
        $files = FileResource::collection(
            File::query()
                ->where('parent_id', '=', $folder->id)
                ->where('created_by', '=', request()->user()->id)
                ->orderBy('is_folder', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(10)
        );

        if ($request->wantsJson()) {
            return $files;
        }

        $ancestors = FileResource::collection([...$folder->ancestors, $folder]);

        if ($folder) {
            $folder = new FileResource($folder);
        }

        return Inertia::render('File/MyFiles', compact('files', 'folder', 'ancestors'));
    }

    public function sharedWithMe()
    {
        return Inertia::render('File/MyFiles');
    }

    public function sharedByMe()
    {
        return Inertia::render('File/MyFiles');
    }

    public function trash()
    {
        return Inertia::render('File/MyFiles');
    }

    public function createFolder(StoreFolderRequest $request)
    {
        $data = $request->validated();

        $parent = $request->parent;

        if (!$parent) {
            $parent = $this->getRoot();
        }

        $folder = new File();
        $folder->is_folder = true;
        $folder->name = $data['name'];

        $parent->appendNode($folder);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFileRequest $request)
    {
        $data = $request->validated();
        $parent = $request->parent;
        $user = $request->user();
        $folderName = $data['folder_name'] ?? null;

        if (!$parent) {
            $parent = $this->getRoot();
        }

        if ($folderName) {
            $folder = new File();
            $folder->is_folder = true;
            $folder->name = $folderName;

            $parent->appendNode($folder);
            $parent = $folder;
        }

        foreach ($data['files'] as $file) {
            /** @var $file \Illuminate\Http\UploadedFile */

            $path = $file->store('/files/' . $user->id);
            $model = new File();
            $model->storage_path = $path;
            $model->is_folder = false;
            $model->name = $file->getClientOriginalName();
            $model->mime = $file->getMimeType();
            $model->size = $file->getSize();

            $parent->appendNode($model);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFileRequest $request, File $file)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FilesActionRequest $request)
    {
        $data = $request->validated();
        $parent = $request->parent;

        if ($data['all']) {
            $children = $parent->children;

            foreach ($children as $child) {
                $child->delete();
            }
        }
        foreach (( $data['ids'] ?? [] ) as $id) {
            $file = File::find($id);
            $file->delete();
        }

        return to_route('myFiles', ['folder' => $parent->path]);
    }

    public function download(FilesActionRequest $request)
    {
        $data = $request->validated();
        $parent = $request->parent;

        $all = $data['all'] ?? false;
        $ids = $data['ids'] ?? [];
        if (!$all & empty($ids)) {
            return [
                'message' => 'Please select files to download'
            ];
        }

        $url = null;
        $filename = null;
        if ($all) {
            $url = $this->createZip($parent->children);
            $filename = $parent->name . '.zip';
        } else {
            if (count($ids) === 1) {
                $file = File::find($ids[0]);
                if ($file->is_folder) {
                    if ($file->children->count() === 0) {
                        return [
                            'message' => 'The folder is empty'
                        ];
                    }
                    $url = $this->createZip($file->children);
                    $filename = $file->name . '.zip';
                } else {
                    $path = 'public/' . pathinfo($file->storage_path, PATHINFO_BASENAME);
                    $dest = $path;
                    Storage::copy($file->storage_path, $dest);

                    $url = asset(Storage::url($dest, $file->name));
                    $filename = $file->name;
                }
            } else {
                $files = File::query()->whereIn('id', $ids)->get();
                $url = $this->createZip($files);

                $filename = $parent->name . '.zip';
            }
        }

        return [
            'url' => $url,
            'filename' => $filename
        ];
    }

    /**
     *
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     */
    private function getRoot(): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
    {
        return File::query()->whereIsRoot()->where('created_by', '=', request()->user()->id)->firstOrFail();
    }

    private function createZip($files): string
    {
        // Path to the output ZIP file
        $zipPath = 'zip/' . Str::random() . '.zip';
        $publicPath = "public/$zipPath";

        if (!is_dir(dirname($publicPath))) {
            Storage::makeDirectory(dirname($publicPath));
        }
        $zipFile = Storage::path($publicPath);

        $zip = new ZipArchive();
        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $this->addFilesToZip($zip, $files);
        }

        // Close the ZIP archive
        $zip->close();

        return asset(Storage::url($zipPath));
    }

    private function addFilesToZip($zip, $files, $ancestors = '')
    {
        foreach ($files as $file) {
            if ($file->is_folder) {
                $this->addFilesToZip($zip, $file->children, $ancestors . $file->name . '/');
            } else {
                $zip->addFile(Storage::path($file->storage_path), $ancestors . $file->name);
            }
        }
    }
}
