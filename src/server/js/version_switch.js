/* [ ---- Gebo Admin Panel - extended form elements ---- ] */

	$(document).ready(function() {
		version_groups.init();
		//$('#saveExtPanel').hide();

	});
	
	var siteList;
	var errs = {
		'-7': '备份目录创建失败',
		'-6': '删除失败',
		'-5': 'delete 参数错误',
		'-4': 'delete 目标版本不存在',
		'-3': '当前版本处于运行状态',
		'-2': 'reload nginx失败',
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
				if(data.value!=undefined){
					window.siteList=data.value;
					$.each(data.value, function(index) {
				    	$('#siteItem').append($('<option value="'+index+'">'+ index   //data.value[index].name
				    		+'('+data.value[index].description+')</option>'));
					});
					if(def_val==undefined){
						def_val=$('#siteItem option').val()
					} else {
						$('#siteItem').val(def_val);
					}
					$('#uniform-siteItem span').html(def_val);
					$('#siteItemWait').hide();
					$('#siteItem').show(500);
					
					get_site(def_val);
				} else {
					$('#uniform-siteItem span').html('missing');
				}					
			});
	}

	function get_site(name){
		$('#versions tbody').empty();
		var site=siteList[name];
		//console.dir(site);
		//alert(JSON.stringify(site.versions));
		$('#current_version').html('浏览 <a href="'+site.url+'" target="_blank">'+site.url+'</a><br />版本号：[<strong>'
			+ site['current_version'] + "</strong>] 最后更新: [" 
			+ site['last_update'] + "] 切换说明: [" 
			+ site['switch_description'] + ']');
		
		$.each(site.versions, function(index) {
			var html = ["<tr>"];
			var o = site.versions[index];
			var cur = index==site['current_version']; //o.version==site['current_version']
			if(cur){
				html.push('<td><span class="splashy-arrow_large_right ttip_r" title="正在运行版本" placeholder="right center"></span></td>');
			} else {
				html.push("<td></td>");
			}
			html.push("<td>"+index+"</td>");
			html.push("<td>"+o.date+"</td>");
            html.push("<td>"+o.description+"</td>");                          

            if(cur){
            	html.push(' <td><span class="splashy-information ttip_r" title="正在运行版本" placeholder="right center"></span>');
            } else {
            	//<span class="splashy-error_x"></span> 
            	html.push('<td><span class="splashy-okay ttip_r" title="点击切换版本" style="cursor:pointer" onclick="switch_version('+index+')"></span>');    	
            }
            html.push(' <a href="http://');
            html.push(site.domain.replace('{ver}',index));
            html.push('" class="splashy-application_windows ttip_r" title="浏览这个版本" target="_blank"></a>');
			if(!cur){
				html.push(' <span class="splashy-remove ttip_r" title="删除这个版本" style="cursor:pointer" onclick="delete_version('+index+')"></span>');
			}
			html.push("</td></tr>");
			//$('#versions tbody').append($(html.join("")));
			$(html.join("")).prependTo('#versions tbody');

		});
		gebo_tips.init();

	}

	function switch_version(ver){

		smoke.prompt('请输入切换描述(不可为空)',function(e){
			if (e){
				var gp = $('#groupSites').val();
				var idx = $('#siteItem').val();
				var st=idx ; //siteList[idx].name;
				Load.blockUI(); //加载锁屏动画
				$.getJSON('./client/services.php?action=switch&gp='+gp+'&site='+st+'&ver='+ver
					+ '&reason='+encodeURIComponent(e)
					+ '&purl=' + encodeURIComponent(siteList[idx].url)
					+ '&pdomain=' + encodeURIComponent(siteList[idx].domain)
					+ '&_='+ Math.random(), 
					function(data){
						Load.unblockUI(); //取消加载动画
						if(data!=undefined&&data.result==true){
							//完整刷新
							loading_sites(gp, idx);
							 $.sticky('成功切换到版本'+ver+'', {autoclose : 5000, position: "top-center", type: "st-success" });
						} else {
							 $.sticky("切换失败，错误代码:"+ data.error + ", 原因:" + errs[data.error], {autoclose : 5000, position: "top-center", type: "st-error" });
							//smoke.alert('切换失败，错误代码:' + data.error);
						}
						
					});
				
			}else{				
				$.sticky("已经取消切换(用户选择取消或者切换描述为空)", {autoclose : 5000, position: "top-center", type: "st-error" });			
			}
		},{ok:"确定", cancel:"取消", value:""});

	}

	function delete_version(ver){
		
		smoke.confirm('确定要删除号版本'+ver+'吗?', function(e) {
            if (e) {
				var gp = $('#groupSites').val();
				var idx = $('#siteItem').val();
				var st=idx;
				Load.blockUI(); //加载锁屏动画
				$.getJSON('./client/services.php?action=delete&gp='+gp+'&site='+st+'&ver='+ver
					+ '&reason='+encodeURIComponent(e)
					+ '&purl=' + encodeURIComponent(siteList[idx].url)
					+ '&pdomain=' + encodeURIComponent(siteList[idx].domain)
					+ '&_='+ Math.random(),
					function(data){
						Load.unblockUI(); //取消加载动画
						if(data!=undefined&&data.result==true){
							//完整刷新
							 loading_sites(gp, idx);
							 $.sticky('成功删除版本'+ver+'', {autoclose : 5000, position: "top-center", type: "st-success" });
						} else {
							 $.sticky("删除失败，错误代码:"+ data.error + ", 原因:" + errs[data.error], {autoclose : 5000, position: "top-center", type: "st-error" });
							//smoke.alert('切换失败，错误代码:' + data.error);
						}
						
					});
            }
            else {
                return false;
            }
       }, {ok: "确定", cancel: "取消"});
	
	}
	

    
	
	
	
	