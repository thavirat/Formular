<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\File;
use Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function moveFileToTempOrak($files , $folder){
        foreach ($files as $key => $val) {
            if (Storage::disk("uploads")->exists($folder."/" . $val)) {
                if(Storage::disk("uploads")->exists("temp/" . $val)){
                    Storage::disk("uploads")->delete("temp/" . $val);
                }
                Storage::disk("uploads")->put("temp/" . $val, Storage::disk('uploads')->get($folder . '/' . $val));
            }
        }
    }

    public function removeFileOrak($files , $folder){
        foreach ($files as $key => $val) {
            if (Storage::disk("uploads")->exists($folder."/" . $val)) {
                Storage::disk("uploads")->delete($folder."/" . $val);
            }
        }
    }

    public function removeFile($files, $folder){
        foreach($files as $file){
            $path = 'files/'.$folder.'/'.$file;
            if (Storage::disk("uploads")->exists($path)) {
                Storage::disk("uploads")->delete($path);
            }
        }
    }

    public function createUploadsFolderIfNotExist()
    {
        if ( !File::exists(public_path('uploads')) && !File::exists(public_path('uploads\temp')) ) {
            File::makeDirectory(public_path('uploads'));
            File::makeDirectory(public_path('uploads\temp'));
        }
    }

}
