
            if(${{ $model_name }}->{{ $name }}){
                $this->removeFile(json_decode(${{$model_name}}->{{$name}}), ${{$model_name}});
            }