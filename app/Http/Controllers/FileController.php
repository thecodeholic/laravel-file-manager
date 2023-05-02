<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\StoreFolderRequest;
use App\Http\Requests\UpdateFileRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
     * Display the specified resource.
     */
    public function show(File $file)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(File $file)
    {
        //
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
    public function destroy(File $file)
    {
        //
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
}
