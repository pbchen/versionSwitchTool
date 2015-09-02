/* [ ---- Gebo Admin Panel - extended form elements ---- ] */

	$(document).ready(function() {
		version_groups.init();
		version_publish.init();
		gebo_validation.ttip();
		version_form.init();
	});
	
	var siteList;
	var new_version=1;
	var errs = {
		'-1': 'KEY设定错误',
		'0': '无错误',
		'1': '没有service参数',
		'2': 'Service内部错误',
		'3': 'switch 参数错误',
		'4': 'switch 当前版本号等于目标版本号',
		'5': 'switch 目标版本不存在',
		'6': 'switch htaccess 文件读写错误',
		'7': 'publish upload 文件上传错误',
		'8': 'publish upload md5 校验失败',
		'9': 'publish 目录创建失败, 请检查目录权限',
		'10': 'publish 发布版本和线上版本一致，无法覆盖',
		'11': '压缩包内未找到deploy目录'
	};
	var last_version=1;
	
	version_groups = {
		init: function(){
			init_sites();

			$('#uniform-groupSites span').html('<img src="img/ajax_loader.gif" alt="">');
			$.getJSON('./client/services.php?action=groups&_='+ Math.random(), 
				function(data){
					$('#groupSites').empty();
					if(data!=undefined && data.value!=undefined){
						$.each(data.value, function(index) {
					    	$('#groupSites').append($('<option value='+data.value[index].name+'>'+data.value[index].name+'('+data.value[index].description+')</option>'));
						});
						$('#uniform-groupSites span').html(data.value[0].name);
						loading_sites(data.value[0].name);
					} else {

					}					
				});
			$('#groupSites').bind('change', function(){
				loading_sites($('#groupSites').val());
			});

			$('#siteItem').bind('change',function(){
				get_site($(this).val());
			});
		}
	};

	function init_sites(){
		$('#siteItem').empty();
		$('#siteItem').hide();
		$('#siteItemWait').show();
	}

	function loading_sites(gp, def_val){
		init_sites();

		$.getJSON('./client/services.php?action=site&gp='+gp+'&_='+ Math.random(), 
			function(data){
				if(data!=undefined && data.value!=undefined){
					window.siteList=data.value;
					$.each(data.value, function(index) {
				    	$('#siteItem').append($('<option value="'+index+'">'+index
				    		+'('+data.value[index].description+')</option>'));
					});
					if(def_val==undefined){
						def_val= $('#siteItem option').val();
					} else {
						$('#siteItem').val(def_val);
					}
					$('#uniform-siteItem span').html(def_val);
					$('#siteItemWait').hide();
					$('#siteItem').show(500);				
	
					get_site(def_val);
				} else {
					$('#uniform-siteItem span').html('missing');
					$('#siteItemWait').html('无站点');
				}					
			});
	}

	function get_site(name){
		$('#version_list').empty();
		var site=siteList[name];
		if(site==null){
			$.each(siteList, function(index) {
			    if(index==name){
			    	site=siteList[index];
			    }
			});
		}

		$('#root').html(site.root);
		$('#url').html(site.url).attr('href', site.url);
		$('#purl').val(site.url);
		$('#pdomain').val(site.domain);
		
		var count = 0;
		var max = 2;
		$.each(site.versions, function(index) {
			var html = ["<option"];
			var cur = index==site['current_version']
			if(cur){
				html.push(' value="skiped" ');
			} else {
				html.push(' value="'+index+'" ');
			}
			html.push(">");html.push(index);
			if(cur){
				html.push('(线上版本，无法覆盖!)');
			} else {
				html.push("(");
				html.push(site.versions[index]['description']);                          
				html.push(")");
			}           
			html.push("</option>");                              
			$('#version_list').append($(html.join("")));
			count++;
			max=index;
		});
		last_version = $('#version_list option:first-child').val();
		window.new_version = parseInt(max)+1;
		if(site.publish && site.publish.toString().toLowerCase()=="true"){
			$('#version_list').append($('<option selected value="-1">新建</option>'));
			select_new();			
		} else {
			$('#version_new').hide();
		}
		gebo_tips.init();

	}

	function select_new(){	
		//$('#uniform-version_list span').html("新建");
		$('#version_list option:last-child').attr("selected","selected");
		$('#version_new_value').val(new_version);
		$('#version_new_hide').val(new_version);
		$('#version_new').show();
		last_version = -1;
	}

	version_publish = {
		init: function(){
			$('#version_list').bind('change', function(){
				var val = $('#version_list').val();
				if(val=='skiped'){
					smoke.alert('正在运行版本无法覆盖！',{ok:"确定"});
					$('#version_list option[value="'+last_version+'"]').attr("selected","selected");
					//select_new();
				} else if(val=='-1') {
					alert(val);
					$('#version_new').show();
				} else {
					$('#version_new').hide();
				}
				last_version=$('#version_list').val();
			});
		}
	};
	
//* validation
	gebo_validation = {
		ttip: function() {
			var ttip_validator = $('.form_validation_ttip').validate({
				onkeyup: false,
				errorClass: 'error',
				validClass: 'valid',
				highlight: function(element) {
					$(element).closest('div').addClass("f_error");
				},
				unhighlight: function(element) {
					$(element).closest('div').removeClass("f_error");
				},
                rules: {
					md5: { required: true, minlength: 32, maxlength: 32 },
					description: { required: true, minlength: 3 },
					publish_file: { required: true}
				},
				invalidHandler: function(form, validator) {
					$.sticky("发布失败，请仔细检查.", {autoclose : 5000, position: "top-center", type: "st-error" });
				},
				errorPlacement: function(error, element) {
					// Set positioning based on the elements position in the form
					var elem = $(element);
					
					// Check we have a valid error message
					if(!error.is(':empty')) {
						if( (elem.is(':checkbox')) || (elem.is(':radio')) ) {
							// Apply the tooltip only if it isn't valid
							elem.filter(':not(.valid)').parent('label').parent('div').find('.error_placement').qtip({
								overwrite: false,
								content: error,
								position: {
									my: 'left bottom',
									at: 'center right',
									viewport: $(window),
									adjust: {
										x: 6
									}
								},
								show: {
									event: false,
									ready: true
								},
								hide: false,
								style: {
									classes: 'ui-tooltip-red ui-tooltip-rounded' // Make it red... the classic error colour!
								}
							})
							// If we have a tooltip on this element already, just update its content
							.qtip('option', 'content.text', error);
						} else {
							// Apply the tooltip only if it isn't valid
							elem.filter(':not(.valid)').qtip({
								overwrite: false,
								content: error,
								position: {
									my: 'bottom left',
									at: 'top right',
									viewport: $(window),
                                    adjust: { x: -8, y: 6 }
								},
								show: {
									event: false,
									ready: true
								},
								hide: false,
								style: {
									classes: 'ui-tooltip-red ui-tooltip-rounded' // Make it red... the classic error colour!
								}
							})
							// If we have a tooltip on this element already, just update its content
							.qtip('option', 'content.text', error);
						};
                        
					}
					// If the error is empty, remove the qTip
					else {
						if( (elem.is(':checkbox')) || (elem.is(':radio')) ) {
							elem.parent('label').parent('div').find('.error_placement').qtip('destroy');
						} else {
							elem.qtip('destroy');
						}
					}
				},
				success: $.noop // Odd workaround for errorPlacement not firing!
			})
		}

	};
    
	
	
	
	version_form = {
		init: function(){
			$('#version_new_value').bind('change', function(){
				$('version_new_hide').val($('#version_new_value').val());
			});

			$('#publish_form').ajaxForm({
				target:        '#output',   // target element(s) to be updated with server response 
		        beforeSubmit:  function(){
		        	$('#output').show();
		        	$('#btnsubmit').hide();
		        },  // pre-submit callback 
		        error:   function(){
		        	$('#output').hide();
		        	$('#btnsubmit').show();
		        	$.sticky("发布失败，请仔细检查.", {autoclose : 5000, position: "top-center", type: "st-error" });
		        },
		        success:  function(r){
		        	//alert(resp);
		        	$('#output').hide();
		        	$('#btnsubmit').show();
		        	//var r = jQuery.parseJSON(resp);
			        if(r&&r.result!=undefined&&r.error!=undefined){
			        	if(r.result){
			        		var v=$('#version_list').val();
			        		if(parseInt(v)==-1){v=$('#version_new_value').val();}
			        		$.sticky('版本['+v+']发布成功!', {autoclose : 5000, position: "top-center", type: "st-success" });						
			        		document.forms["publish_form"].reset();
			        		loading_sites($('#groupSites').val(), $('#siteItem').val());
			        	} else {
			        		$.sticky("发布失败，错误代码 " + r.error + ' ' + errs[r.error] , {autoclose : 5000, position: "top-center", type: "st-error" });
			        
			        	}
		        	} else {
		        		$.sticky("发布失败，联系管理员.", {autoclose : 5000, position: "top-center", type: "st-error" });
		        
		        	}
		        }  // post-submit callback 

			});
		}
	};