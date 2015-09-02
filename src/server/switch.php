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
                        <div class="span12">
                            <h3 class="heading">Web站点选择</h3>
                            <div class="">
                                
                                <div class="span4">
                                <span class="label label-inverse">选择节点</span>                                    
                                    <select class="uni_style" id="groupSites">                                        
                                    </select>            
                                </div>
                                
                                <div class="span4">  
                                <span class="label label-inverse">选择站点</span>                                  
                                    <span id='siteItemWait' style='display:none;'><img src="img/ajax_loader.gif" alt=""></span>                                 
                                    <select class="uni_style" id="siteItem">                                        
                                    </select>            
                                </div>
                                <div style="clear:both;"></div>
                            </div>
                            
                            <h3 class="heading">版本切换</h3>
                            <div class="">
                                <!--button data-toggle="dropdown" class="btn dropdown-toggle">Action <span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li><a href="#" class="delete_rows_simple" data-tableid="smpl_tbl"><i class="icon-trash"></i> 删除</a></li>
                                    <li><a href="javascript:void(0)">Lorem ipsum</a></li>
                                    <li><a href="javascript:void(0)">Lorem ipsum</a></li>
                                </ul-->
                            </div>
                            <div></div>
                            <div class="alert">
                                <strong>请注意</strong>
                                <div>请确认新版测试无误后再进行切换！</div>
                            </div>
                            <div class="alert alert-success">
                                <strong>当前版本</strong>
                                <div id="current_version">未知uni_style</div>
                            </div>
                            <table class="table table-bordered table-striped" id="versions">
                                <thead>
                                    <tr>
                                        <th class="table_checkbox"></th>
                                        <th>版本</th>
                                        <th>更新日期</th>
                                        <th>版本说明</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    /*
                                    <tr>
                                        <td></td>
                                        <td>134</td>
                                        <td>23/04/2012</td>
                                        <td>Pending</td>
                                        <td><span class="splashy-error_x"></span> <span class="splashy-okay"></span></td>
                                    </tr>
                                    <tr>
                                        <td><span class="splashy-arrow_large_right ttip_r" title="正在运行版本" placeholder="right center"></span></td>
                                        <td>6</td>
                                        <td>23/04/2012</td>
                                        <td>balabala</td>
                                        <td><span class="splashy-information ttip_r" title="正在运行版本" placeholder="right center"></span></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>132</td>
                                        <td>23/04/2012</td>
                                        <td>Pending</td>
                                        <td><span class="splashy-error_x"></span> <span class="splashy-okay"></span></td>
                                    </tr>
                                    */?>
                                </tbody>
                            </table>
                            <!--h3 class="heading">其它配置</h3>
                            <div>
                                <div>
                                    <label class="uni-checkbox">
                                    <input type="checkbox" value="enabled" name="enableCookie" class="uni_style" />
                                    启用来访者IP绑定
                                </label>
                                </div>  
                                <div>
                                    <div class="span12">
                                        <label class="uni-checkbox">
                                            <input type="checkbox" value="enabled" name="enableCookie" class="uni_style" />
                                            启用访问域名绑定
                                        </label>
                                    </div>
                                    <div class="span8" id="domain_bind">
                                        <span class="span4">请输入版本号之后的域名路径</span>
                                        <input type="text" value=".is.mobisage.cn" /> <span>例如：http://1.is.mobisage.cn</span>
                                    </div>
                                    <div style="clear:both;"></div>
                                </div>  
                                <div>
                                    <div class="span12">
                                        <label class="uni-checkbox">
                                            <input type="checkbox" value="enabled" name="enableCookie" class="uni_style" />
                                            启用Cookie
                                        </label>
                                    </div>                                    
                                    <div class="span8" id="cookie_bind">
                                        <span  class="span4">请输入Cookie版本名称</span>
                                        <input type="text" value="ver" />
                                    </div>                               
                                </div> 
                                <div style="clear:both;"></div>
                                <div id="saveExtPanel"> <button class="btn btn-success" id="smoke_confirm">保存</button> <button class="btn">取消</button></div>
                            </div-->   
                        </div>
					</div>
				</div>                        
            </div>

            
			<?php 
            $sub_menu='switch';
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
            <script src="js/jquery.wookmark.js"></script>-->
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


            <!-- styled form elements
            <script src="lib/uniform/jquery.uniform.min.js"></script> -->
			
            <!-- smoke_js -->
            <script src="lib/smoke/smoke.js"></script>
            <!-- sticky messages -->
            <script src="lib/sticky/sticky.min.js"></script>
            <script src="js/version_switch.js"></script>
			<!-- ajax loading waiting -->
			<script src="js/ajax_loading.js"></script>

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
                smoke.confirm('确认要切换版本?',function(e){
                    if (e){
                        smoke.alert('版本切换成功', {ok:"close"});
                    }else{
                        //smoke.alert('', {ok:"close"});
                    }
                }, {ok:"确定，请继续", cancel:"取消"});
            }
			</script>
		
		</div>
	</body>
</html>