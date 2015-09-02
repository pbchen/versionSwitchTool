<!-- header -->
<header>
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container-fluid">
                <a class="brand" href="dashboard.php"><i class="icon-home icon-white"></i> 控制台</a>
                <ul class="nav user_menu pull-right">                 
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $_SESSION['username'];?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
							<li><a href="index.php?action=logout">登出</a></li>
                        </ul>
                    </li>
                </ul>
				<a data-target=".nav-collapse" data-toggle="collapse" class="btn_menu">
					<span class="icon-align-justify icon-white"></span>
				</a>
                
            </div>
        </div>
    </div>
</header>