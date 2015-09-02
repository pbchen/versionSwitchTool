<?php include_once("config/config.php"); ?>
<?php include_once("common.php"); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Web项目版本切换工具</title>
    
        <!-- Bootstrap framework -->
            <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
            <link rel="stylesheet" href="bootstrap/css/bootstrap-responsive.min.css" />
        <!-- gebo blue theme-->
            <link rel="stylesheet" href="css/blue.css" id="link_theme" />
        <!-- breadcrumbs-->
            <link rel="stylesheet" href="lib/jBreadcrumbs/css/BreadCrumb.css" />
        <!-- tooltips-->
            <link rel="stylesheet" href="lib/qtip2/jquery.qtip.min.css" />
        <!-- colorbox -->
            <link rel="stylesheet" href="lib/colorbox/colorbox.css" />    
        <!-- notifications -->
            <link rel="stylesheet" href="lib/sticky/sticky.css" />    
        <!-- splashy icons -->
            <link rel="stylesheet" href="img/splashy/splashy.css" />
            
        <!-- main styles -->
            <link rel="stylesheet" href="css/style.css" />
			
        
        <!-- Favicon -->
            <link rel="shortcut icon" href="favicon.ico" />
		
        <!--[if lte IE 8]>
            <link rel="stylesheet" href="css/ie.css" />
            <script src="js/ie/html5.js"></script>
			<script src="js/ie/respond.min.js"></script>
			<script src="lib/flot/excanvas.min.js"></script>
        <![endif]-->
        
        <!-- nice form elements -->
        <link rel="stylesheet" href="lib/uniform/Aristo/uniform.aristo.css" />

        <!-- smoke_js -->
        <link rel="stylesheet" href="lib/smoke/themes/gebo.css" />

		<script>
			//* hide all elements & show preloader
			document.documentElement.className += 'js';
		</script>
    </head>
    <body>
		<div id="loading_layer" style="display:none"><img src="img/ajax_loader.gif" alt="" /></div>		
		
		<div id="maincontainer" class="clearfix">
			<?php include("views/header.php"); ?>
            
            <!-- main content -->
            <div id="contentwrapper">
                <div class="main_content">
                    <div class="row-fluid">
                        <form id="publish_form" name="publish_form" class="form_validation_ttip" action="./client/services.php?action=publish" method="POST" enctype="multipart/form-data">
    						<div class="span12">
                                <h3 class="heading">发布新版本</h3>
                                <div class="formSep">                                    
                                    <div class="span4">
                                    <span class="label label-inverse">选择节点</span>                                    
                                        <select class="uni_style" id="groupSites" name="groupSites">                                        
                                        </select>            
                                    </div>
                                    
                                    <div class="span4">  
                                    <span class="label label-inverse">选择站点</span> 
                                        <span id='siteItemWait' style='display:none;'><img src="img/ajax_loader.gif" alt=""></span>                                 
                                        <select class="uni_style" id="siteItem" name="siteItem">                                        
                                        </select>            
                                    </div>
                                    <div style="clear:both;"></div>
                                </div>


    							
    							<div  class="formSep">
                                    <div class="alert alert-info"><strong>路径</strong> 
                                        当前站点根目录: <strong id="root"></strong> 仅支持zip发布包，文件必须包含<strong class="f_req">deploy</strong>目录！
                                        <br />浏览：<a href="" id="url" target="_blank"></a>
                                    </div>
                                    <label>来源<span class="f_req"></span></label>
                                    <div class="span3" style="margin-left:0;width:80px;">
                                        <select class="uni_style" style="width:70px;" name="publish_func" id="publish_func">
                                            <option value="upload">上传</option>
                                            <!--option>下载</option>
                                            <option>SVN</option-->
                                        </select>
                                    </div>
                                    <div class="span3" style="width:180px;">
        								<select class="uni_style" style="width:170px;" name="publish_type" id="publish_type">
                                            <option value="copyreplace">复制当前版本并覆盖</option>
                                            <option value="new">全新发布</option>
                                        </select>
                                    </div>
                                    <div class="span3">
                                        <input type="file" name="publish_file" id="publish_file"  class="uni_style" />
                                    </div>
                                    <div style="clear:both;"></div>
    							</div>
                                
                               
                                <div class="formSep">
                                     <label>MD5校验码<span class="f_req">*</span></label>
                                     <input name="md5" id="md5" class="span4" placeholder="文件32位MD5校验码" />
                                </div>  
    							
    							<div class="formSep">
                                    <label>版本说明<span class="f_req">*</span></label>
    								 <textarea name="description" id="description" cols="30" rows="4" class="input-xxlarge" 
                                     placeholder="请输入发布描述，不可为空" ></textarea>
    							</div>							
                                
                                <div class="formSep" style="">
                                    <label>目标版本<span class="f_req"></span></label>
                                    <div class="span4" style="margin-left:0;width:210px;">
                                        <select class="uni_style" style="" id="version_list" name="version_list">
                                        </select>
                                    </div>
                                    <div class="span4" id="version_new">
                                        新版本号：<input id="version_new_value" name="version_new_value" disabled="disabled"  type="text" style="width:60px;"/>
                                        <input  id="version_new_hide" name="version_new_hide" type='hidden' />
                                    </div>
                                     <div style="clear:both;"></div>
                                </div>
                                
                                <div class="form-actions">
                                    <input type="hidden" name="purl" id="purl" value=""/>
                                    <input type="hidden" name="pdomain" id="pdomain" value=""/>
                                    <button id="btnsubmit" class="btn btn-success" type="submit">发布</button>
                                    <div id="output" style="display:none;"><img src="img/waiting.gif" alt="" /></div>
                                </div>

    						</div>
                        </form>
					</div>                        
                </div>
            </div>
            
			<?php 
            $sub_menu='publish';
            include("views/slider.php"); 
            ?>
            
            <script src="js/jquery.min.js"></script>
			<!-- smart resize event -->
			<script src="js/jquery.debouncedresize.min.js"></script>
			<!-- hidden elements width/height -->
			<script src="js/jquery.actual.min.js"></script>
			<!-- js cookie plugin -->
			<script src="js/jquery.cookie.min.js"></script>
			<!-- main bootstrap js -->
			<script src="bootstrap/js/bootstrap.min.js"></script>
			<!-- tooltips -->
			<script src="lib/qtip2/jquery.qtip.min.js"></script>
			<!-- jBreadcrumbs -->
			<script src="lib/jBreadcrumbs/js/jquery.jBreadCrumb.1.1.min.js"></script>
			<!-- lightbox -->
            <script src="lib/colorbox/jquery.colorbox.min.js"></script>
            <!-- fix for ios orientation change -->
			<script src="js/ios-orientationchange-fix.js"></script>
			<!-- scrollbar -->
			<script src="lib/antiscroll/antiscroll.js"></script>
			<script src="lib/antiscroll/jquery-mousewheel.js"></script>
			<!-- common functions -->
			<script src="js/gebo_common.js"></script>
			
			<script src="lib/jquery-ui/jquery-ui-1.8.20.custom.min.js"></script>
            <!-- touch events for jquery ui-->
            <script src="js/forms/jquery.ui.touch-punch.min.js"></script>
            <!-- multi-column layout
            <script src="js/jquery.imagesloaded.min.js"></script>
            <script src="js/jquery.wookmark.js"></script> -->
            <!-- responsive table 
            <script src="js/jquery.mediaTable.min.js"></script>-->
            <!-- small charts 
            <script src="js/jquery.peity.min.js"></script>-->
            <!-- charts 
            <script src="lib/flot/jquery.flot.min.js"></script>
            <script src="lib/flot/jquery.flot.resize.min.js"></script>
            <script src="lib/flot/jquery.flot.pie.min.js"></script>-->
            <!-- calendar
            <script src="lib/fullcalendar/fullcalendar.min.js"></script> -->
            <!-- sortable/filterable list 
            <script src="lib/list_js/list.min.js"></script>
            <script src="lib/list_js/plugins/paging/list.paging.min.js"></script>-->
            <!-- dashboard functions 
            <script src="js/gebo_dashboard.js"></script>-->
            
            <!-- styled form elements
            <script src="lib/uniform/jquery.uniform.min.js"></script> -->

            <!-- smoke_js -->
            <script src="lib/smoke/smoke.js"></script>
            <!-- sticky messages -->
            <script src="lib/sticky/sticky.min.js"></script>
            <!-- validation -->
            <script src="lib/validation/jquery.validate.min.js"></script>
            <script src="lib/jquery.form.js"></script>

            <script src="js/version_publish.js"></script>

			<script>
				$(document).ready(function() {
					//* show all elements & remove preloader
					setTimeout('$("html").removeClass("js")',1000);
                    //$(".uni_style").uniform();
                    $('#smoke_confirm').click(function(e){
                        tstconfirm();
                        e.preventDefault();
                    });
				});

                function tstconfirm(){
                    smoke.confirm('确认要发布新版本?',function(e){
                        if (e){
                            smoke.alert('版本发布成功', {ok:"close"});
                        }else{
                            //smoke.alert('', {ok:"close"});
                        }
                    }, {ok:"确定，请继续", cancel:"取消"});
                }
			</script>
		
		</div>
	</body>
</html>