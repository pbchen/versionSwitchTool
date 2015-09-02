<!-- sidebar -->
<a href="javascript:void(0)" class="sidebar_switch on_switch ttip_r" title="Hide Sidebar">隐藏/显示</a>
<div class="sidebar">
	
	<div class="antiScroll">
		<div class="antiscroll-inner">
			<div class="antiscroll-content">
		
				<div class="sidebar_inner">
					<form action="index.php?uid=1&amp;page=search_page" class="input-append" method="post" >
						<input autocomplete="off" name="query" class="search_query input-medium" size="16" type="text" placeholder="Search..." style="display:none;"/>
						<button type="submit" class="btn" style="display:none;"><i class="icon-search" ></i></button>
					</form>
					<div id="side_accordion" class="accordion">
						
						<div class="accordion-group">
							<div class="accordion-heading">
								<a href="#collapseOne" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
									<i class="icon-folder-close"></i> 操作
								</a>
							</div>
							<div class="accordion-body" id="collapseOne">
								<div class="accordion-inner">
									<ul class="nav nav-list">
										<li<?php if($sub_menu=='dashboard')echo ' class="active" ';?>><a href="./dashboard.php">首页</a></li>
										<li<?php if($sub_menu=='publish')echo ' class="active" ';?>><a href="./publish.php">发布新版</a></li>
										<li<?php if($sub_menu=='switch')echo ' class="active" ';?>><a href="./switch.php">版本切换</a></li>
										<!--li><a href="/filemanager.php">文件管理</a></li-->
									</ul>
								</div>
							</div>
						</div>
						<div class="accordion-group">
							<div class="accordion-heading">
								<a href="#collapseThree" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
									<i class="icon-user"></i> 用户
								</a>
							</div>
							<div class="accordion-body collapse" id="collapseThree">
								<div class="accordion-inner">
									<ul class="nav nav-list">
										<li><a href="javascript:void(0)">修改密码</a></li>
									</ul>
									
								</div>
							</div>
						</div>
					</div>
					
					<div class="push"></div>
				</div>
			
			
			</div>
		</div>
	</div>

</div>