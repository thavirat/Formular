$data = {{$model_name}}::find($id);
            if($data->{{$name}}){
                $this->removeFileOrak(json_decode($data->{{$name}}) , '{{$model_name}}');
            }
            ${{$name}} = $request->input('{{$name}}');
            if(!empty($request->input('{{$name}}'))){
                ${{$name}} = json_encode(${{$name}});
                foreach ($request->input('{{$name}}') as $key => $val) {
                    if (Storage::disk("uploads")->exists("temp/" . $val) && !Storage::disk("uploads")->exists("{{$model_name}}/" . $val)) {
                        Storage::disk("uploads")->copy("temp/" . $val, "{{$model_name}}/" . $val);
                        Storage::disk("uploads")->delete("temp/" . $val);
                    }
                }                        
            }