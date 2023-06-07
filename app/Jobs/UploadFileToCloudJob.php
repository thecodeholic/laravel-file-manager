<?php

namespace App\Jobs;

use App\Models\File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UploadFileToCloudJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The user object to be processed.
     *
     * @var File
     */
    protected File $file;

    /**
     * Create a new job instance.
     *
     * @param File $file The user object to be processed.
     *
     * @return void
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $model = $this->file;

        if (!$model->uploaded_on_cloud) {
            $localPath = Storage::disk('local')->path($model->storage_path);
            Log::debug('Uploading on S3... ' . $localPath);
            try {
                $success = Storage::put($model->storage_path, Storage::disk('local')->get($model->storage_path));
                if ($success) {
                    Log::debug('Updating the database ');
                    $model->uploaded_on_cloud = 1;
                    $model->save();
                } else {
                    Log::error('Unable to upload files to S3');
                }
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }
        }
    }
}
