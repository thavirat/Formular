function validate_highlight(e){
    //$(e).closest('.form-group').removeClass('has-success').addClass('has-error');
    $(e).addClass('is-invalid');
	var id = $(e).closest('.modal').attr("id");
	ShowModal(id);
}

function validate_success(e){
	$(e).parent().find('.form-control').removeClass('is-invalid');
	$(e).parent().find('.is-invalid').removeClass('is-invalid');
    //$(e).closest('.form-group').removeClass('has-error').addClass('has-success');
    //$(e).closest('div').find('.is-invalid').removeClass('is-invalid');
    //$(e).remove();
}

function validate_errorplacement(error,element){
	// console.log(element);
	// element.closest('.form-control').addClass('.has-error');
    if(element.is(':checkbox') || element.is(':radio')) {
        //var controls = element.closest('div[class*="col-"]');
        var controls = element.closest('.form-group');
        if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
        else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
    }
    else if(element.is('.select2')) {
        error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
    }
    else if(element.is('.select2-hidden-accessible')) {
        error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
        error.closest('.form-group').find('.select2-selection').addClass('is-invalid');
    }
    else if(element.is('.chosen-select')) {
        error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
    }
	else if(element.parent('.input-group').length) {
        error.insertAfter(element.parent());
    }
    else error.insertAfter(element);
}


function resetFormCustom(ele){
	$(ele)[0].reset();
	$(ele).validate().resetForm();
	$(ele).find('.form-group').removeClass('has-error');
	$(ele).find('.form-group').removeClass('has-success');

	//CkEditor
	if(typeof CKEDITOR!=='undefined'){
		for (instance in CKEDITOR.instances) {
			CKEDITOR.instances[instance].setData('');
		}
	}

	//Dropdown Chosen
	$(".chosen-select").val('0').trigger("chosen:updated");

	//Tag Input
	$.each($(ele).find('[data-role="tagsinput"]'),function(key,val){
		var $tag_obj = $(val).data('tag');
		$.each($tag_obj.values,function(key,val){
			$tag_obj.remove(0);
			$tag_obj.remove(key);
		});
	});

	//Orakupload
	$(ele).find(".picture_delete").click();
	$(ele).find('select').trigger('change');
	console.log($(ele).find('select').length);
}