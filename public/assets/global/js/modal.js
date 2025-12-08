$(document).ready(function(){
	if(navigator.userAgent.match(/iPhone|iPad|iPod/i) ) {
			$('html').addClass('on-mobile');
			$('body').addClass('on-mobile');
			$('html').addClass('modal_openoverflow');
		} else {
			$('.modal').on('show.bs.modal', function () {
				centerModals();
			});
			$(window).on('resize', centerModals);
			$('.modal').on('hidden.bs.modal', function () {
					clearmaxheight();
			});
		}
});

function centerModals() {
		  $('.modal').each(function(i){
				var $clone = $(this).clone().css('display', 'block').appendTo('body');
				var top = Math.round(($clone.height() - $clone.find('.modal-content').height()) / 2);
				top = top > 0 ? top : 0;
				$clone.remove();
				if($(window).width()>=768){
					$(this).find('.modal-content').css("margin-top", top);
				} else {
					$(this).find('.modal-content').css("margin-top", 0);
				}				
		  });
	}
			
function clearmaxheight() {
		if($(window).width()>=768){
			$(".modal>.modal-dialog").find(".modal-content>.modal-body").css("max-height","");
		}
	}
	
function ShowModal(id){
			$('#'+id).on('shown.bs.modal',function(){
			var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
			$(".modal-footer").css({"text-align":"right"});
			$(".modal-body").css("overflow-y","auto");
			
					$("#"+id+">.modal-dialog").find(".modal-content>.modal-body").css("height","auto");
					h1=$("#"+id+">.modal-dialog").height();
					h2=$(window).height();
					h3=$("#"+id+">.modal-dialog").find(".modal-content>.modal-body").height();
					h4=h2-(h1-h3);	
					h5=$("#"+id+">.modal-dialog").find(".modal-content>.modal-header").height();
					h6=$("#"+id+">.modal-dialog").find(".modal-content>.modal-footer").height();
					if($(window).width()>=768){
						if(h1>h2){
								if( isSafari == true ) {
									$("#"+id+">.modal-dialog").find(".modal-content>.modal-body").height(h4);
								} else {
									$("#"+id+">.modal-dialog").find(".modal-content>.modal-body").height(h4);
								}
								if(	(id == "Mail_Purchase" || id == "Mail_Sale" || id == "Mail_Quotation" || id == "deliverydetailModalMap") ) {
									$("#"+id+">.modal-dialog").find(".modal-content>.modal-body").css("max-height",h4);
								} else {
									if( isSafari == true ) {
										$("#"+id+">.modal-dialog").find(".modal-content>.modal-body").css("max-height",h2-110);
									} 
								}
						} else {
								if(	(id == "Mail_Purchase" || id == "Mail_Sale" || id == "Mail_Quotation" || id == "deliverydetailModalMap") ) {
									$("#"+id+">.modal-dialog").find(".modal-content>.modal-body").css("max-height",h1);
								} else {
									if( isSafari == true ) {
										$("#"+id+">.modal-dialog").find(".modal-content>.modal-body").css("max-height",h2-110);
									} 
								}
						}
						$("#"+id+">.modal-dialog").css("margin","30px auto");
						$("#"+id+">.modal-dialog").find(".modal-content").css("border",0);			
						if($("#"+id+">.modal-dialog").height()+30>h2){
							$("#"+id+">.modal-dialog").css("margin-top","0px");
							$("#"+id+">.modal-dialog").css("margin-bottom","0px");
						}
						$(".modal-footer").css({"text-align":"right","border-bottom":"none"});
					}
					else{
						$("#"+id+">.modal-dialog").find(".modal-content>.modal-body").height(h4);
						$("#"+id+">.modal-dialog").find(".modal-content>.modal-body").css("max-height",h4 + 40);
						$("#"+id+">.modal-dialog").css("margin",0);
						$("#"+id+">.modal-dialog").find(".modal-content").css("border",0);
						$("#"+id+">.modal-dialog").find(".modal-content").css("border-radius",0);	
						//if( navigator.userAgent.match(/iPhone|iPad|iPod/i) ) {
									scrollLocation = $(window).scrollTop();
											$('#'+id)
												.css({
													position: 'absolute',
													marginTop: $(window).scrollTop() + 'px',
													bottom: 'auto'
												});
												$('body').on('blur', 'input select', function(){
													setTimeout(function() {
													  var $focused = $(':focus');
													  if(!$focused.is('input')) {
														   $('#'+id)
															.css({
																top: 0
															});
													  }
													}, 0);
												});
												$('body').on('focus', 'input select', function(){
													setTimeout(function() {
													  var $focused = $(':focus');
													  if(!$focused.is('input')) {
														   $(window).trigger('resize'); 
													  }
													}, 0);
												});
											setTimeout( function() {
												$('.modal-backdrop').css({
													position: 'absolute', 
													top: 0, 
													left: 0,
													width: '100%',
													height: Math.max(
														document.body.scrollHeight, document.documentElement.scrollHeight,
														document.body.offsetHeight, document.documentElement.offsetHeight,
														document.body.clientHeight, document.documentElement.clientHeight
													) + 'px'
												});
											}, 0);
							//}
					} 
				window.onresize=function(){
					$("#"+id+">.modal-dialog").find(".modal-content>.modal-body").css("height","auto");
					h1=$("#"+id+">.modal-dialog").height();
					h2=$(window).height();
					h3=$("#"+id+">.modal-dialog").find(".modal-content>.modal-body").height();
					h4=h2-(h1-h3);
					if($(window).width()>=768){
							if(h1>h2){
								$("#"+id+">.modal-dialog").find(".modal-content>.modal-body").height(h4);
									if(	(id == "Mail_Purchase" || id == "Mail_Sale" || id == "Mail_Quotation" || id == "deliverydetailModalMap") ) {
										$("#"+id+">.modal-dialog").find(".modal-content>.modal-body").css("max-height",h4);
									}
							} else {
									if(	(id == "Mail_Purchase" || id == "Mail_Sale" || id == "Mail_Quotation" || id == "deliverydetailModalMap") ) {
										$("#"+id+">.modal-dialog").find(".modal-content>.modal-body").css("max-height",h1);
									}
							}
						$("#"+id+">.modal-dialog").css("margin","30px auto");
						$("#"+id+">.modal-dialog").find(".modal-content").css("border",0);				
						if($("#"+id+">.modal-dialog").height()+30>h2){
							$("#"+id+">.modal-dialog").css("margin-top","0px");
							$("#"+id+">.modal-dialog").css("margin-bottom","0px");
						}
						$(".modal-footer").css({"text-align":"right","border-bottom":"none"});
					}
					else{
						$("#"+id+">.modal-dialog").find(".modal-content>.modal-body").height(h4);
						$("#"+id+">.modal-dialog").find(".modal-content>.modal-body").css("max-height",h4 + 40);
						$("#"+id+">.modal-dialog").css("margin",0);
						$("#"+id+">.modal-dialog").find(".modal-content").css("border",0);
						$("#"+id+">.modal-dialog").find(".modal-content").css("border-radius",0);	
						if( navigator.userAgent.match(/iPhone|iPad|iPod/i) ) {
									scrollLocation = $(window).scrollTop();
											$('#'+id)
												.css({
													position: 'absolute',
													marginTop: $(window).scrollTop() + 'px',
													bottom: 'auto'
												});
												$('body').on('blur', 'input select', function(){
													setTimeout(function() {
													  var $focused = $(':focus');
													  if(!$focused.is('input')) {
														   $('#'+id)
															.css({
																top: 0
															});
													  }
													}, 0);
												});
												$('body').on('focus', 'input select', function(){
													setTimeout(function() {
													  var $focused = $(':focus');
													  if(!$focused.is('input')) {
														   $(window).trigger('resize'); 
													  }
													}, 0);
												});
											setTimeout( function() {
												$('.modal-backdrop').css({
													position: 'absolute', 
													top: 0, 
													left: 0,
													width: '100%',
													height: Math.max(
														document.body.scrollHeight, document.documentElement.scrollHeight,
														document.body.offsetHeight, document.documentElement.offsetHeight,
														document.body.clientHeight, document.documentElement.clientHeight
													) + 'px'
												});
											}, 0);
							}
					}
				};
			});  
			$('#'+id).on('hide.bs.modal',function(){
				window.onresize=function(){};
			});  
				if($(window).width()<=991){
					$('html, body').scrollTop(0);
				}
				setTimeout(function(){
					if (!$("#"+id).hasClass("enabled_click")){
						$("#"+id).modal({
							backdrop: 'static',
							keyboard: false
						});
					}
					$("#"+id).modal("show");
				}, 100);
		}