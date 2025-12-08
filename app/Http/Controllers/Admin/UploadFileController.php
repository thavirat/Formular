<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Storage;

class UploadFileController extends Controller
{
    public function index(Request $request){

        $this->createUploadsFolderIfNotExist();

        $type = $request->file->extension();
        $return['path'] = $request->file->storeAs('files\temp',time().'-'.str_random(5).'.'.$type,'uploads');
        $return['extension'] = $type;
        switch ($return['extension']) {
            case 'png':
                $return['link_preview'] = asset('uploads/'.$return['path']);
            break;
            case 'jpg':
                $return['link_preview'] = asset('uploads/'.$return['path']);
            break;
            case 'jpeg':
                $return['link_preview'] = asset('uploads/'.$return['path']);
            break;
            case 'gif':
                $return['link_preview'] = asset('uploads/'.$return['path']);
            break;
            case 'pdf':
                $return['link_preview'] = asset('uploads/'.$return['path']);
            break;
            case 'doc':
                $return['link_preview'] = asset('images/word.png');
            break;
            case 'docx':
                $return['link_preview'] = asset('images/word.png');
            break;
            case 'xls':
                $return['link_preview'] = asset('images/excel.png');
            break;
            case 'xlsx':
                $return['link_preview'] = asset('images/excel.png');
            break;
            default:
                $return['link_preview'] = asset('images/file.png');
            break;
        }
        return $return;
    }

    public function store(Request $request){
        $path = $request->file('file')->store('temp','public');
        $name = $request->file->getClientOriginalName();
        $data['org_name'] = $name;
        $data['file_name'] = $path;
        $data['url'] = asset('/storage/'.$path);
        $data['status'] = 1;
        $data['content'] = __('messages.upload_file_success');
        $data['title'] = __('messages.upload_file');
        return $data;
    }

    public function destroy(Request $request){
        $path = $request->input('file');
        if(Storage::disk('uploads')->exists($path)){
            Storage::disk('uploads')->delete($path);
        }
        $return['status'] = 1;
        $return['title'] = __('messages.save');
        $return['content'] = __('messages.success');
        return $return;
    }
}
