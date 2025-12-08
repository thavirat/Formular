
            if(${{ $model_name }}->{{ $name }}){
                $this->removeFileOrak(json_decode(${{$model_name}}->{{$name}}), ${{$model_name}});
            }