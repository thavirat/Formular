${{$name}} = [];
            ${{$name}}s = $request->input('{{$name}}');
            if(${{$name}}s){
                foreach(${{$name}}s as $file_name){
                    Storage::disk('uploads')->move('files/temp/'.$file_name, 'files/{{$model_name}}/'.$file_name);
                    ${{$name}}[] = $file_name;
                }
            }
            
            ${{$name}} = json_encode(${{$name}});
            
            
