${{$name}} = $request->input('{{$name}}');
            if(${{$name}}){
                ${{$name}} = Help::convertDateThaiToDbFormat(${{$name}} , '/');
            }
