<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\StoreFolderRequest;
use App\Http\Requests\UpdateFileRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use Inertia\Inertia;

class FileController extends Controller
{

    public function myFiles()
    {
        $files = FileResource::collection(
            File::query()->where('_lft', '>', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(50)
        );

        return Inertia::render('File/MyFiles', compact('files'));
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

        $root = File::query()->where('_lft', '=', 1)->firstOrFail();

        $folder = new File();
        $folder->is_folder = true;
        $folder->name = $data['name'];

        $root->appendNode($folder);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFileRequest $request)
    {
        //
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
}
