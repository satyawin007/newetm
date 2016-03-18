<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Top Menu Style - Ace Admin</title>

		<meta name="description" content="top menu &amp; navigation" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="../assets/css/bootstrap.css" />
		<link rel="stylesheet" href="../assets/css/font-awesome.css" />

		<!-- page specific plugin styles -->
		@yield('page_css')

		<!-- text fonts -->
		<link rel="stylesheet" href="../assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="../assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="../assets/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="../assets/css/ace-ie.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="../assets/js/ace-extra.js"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="../assets/js/html5shiv.js"></script>
		<script src="../assets/js/respond.js"></script>
		<![endif]-->
		@yield('inline_css')
		
	</head>

	<body class="no-skin">
		<!-- #section:basics/navbar.layout -->
		<div id="navbar" class="navbar navbar-default    navbar-collapse       h-navbar">
			<script type="text/javascript">
				try{ace.settings.check('navbar' , 'fixed')}catch(e){}
			</script>

			<div class="navbar-container" id="navbar-container">
				<div class="navbar-header pull-left">
					<!-- #section:basics/navbar.layout.brand -->
					<a href="#" class="navbar-brand">
						<small>
							<i class="fa fa-leaf"></i>
							Easy Travel Management
						</small>
					</a>

					<!-- /section:basics/navbar.layout.brand -->

					<!-- #section:basics/navbar.toggle -->
					<button class="pull-right navbar-toggle navbar-toggle-img collapsed" type="button" data-toggle="collapse" data-target=".navbar-buttons,.navbar-menu">
						<span class="sr-only">Toggle user menu</span>

						<img src="../assets/avatars/user.jpg" alt="Jason's Photo" />
					</button>

					<button class="pull-right navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#sidebar">
						<span class="sr-only">Toggle sidebar</span>

						<span class="icon-bar"></span>

						<span class="icon-bar"></span>

						<span class="icon-bar"></span>
					</button>

					<!-- /section:basics/navbar.toggle -->
				</div>

				<!-- #section:basics/navbar.dropdown -->
				<div class="navbar-buttons navbar-header pull-right  collapse navbar-collapse" role="navigation">
					<ul class="nav ace-nav">
						<li class="transparent"><span style="font-weight: bolder; color: white; margin-right: 10px;"><i class="fa fa-calendar bigger-110"></i> {{date("d-m-Y")}}</span><span style="font-weight: bolder; color: white; margin-right: 10px;"><i class="fa fa-clock-o bigger-110"></i> <span id="time">10:23:00</span></span></li>
						<li class="transparent">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="ace-icon fa fa-bell icon-animated-bell"></i>
							</a>

							<div class="dropdown-menu-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
								<div class="tabbable">
									<ul class="nav nav-tabs">
										<li class="active">
											<a data-toggle="tab" href="#navbar-tasks">
												Tasks
												<span class="badge badge-danger">4</span>
											</a>
										</li>

										<li>
											<a data-toggle="tab" href="#navbar-messages">
												Messages
												<span class="badge badge-danger">5</span>
											</a>
										</li>
									</ul><!-- .nav-tabs -->

									<div class="tab-content">
										<div id="navbar-tasks" class="tab-pane in active">
											<ul class="dropdown-menu-right dropdown-navbar dropdown-menu">
												<li class="dropdown-content">
													<ul class="dropdown-menu dropdown-navbar">
														<li>
															<a href="#">
																<div class="clearfix">
																	<span class="pull-left">Software Update</span>
																	<span class="pull-right">65%</span>
																</div>

																<div class="progress progress-mini">
																	<div style="width:65%" class="progress-bar"></div>
																</div>
															</a>
														</li>

														<li>
															<a href="#">
																<div class="clearfix">
																	<span class="pull-left">Hardware Upgrade</span>
																	<span class="pull-right">35%</span>
																</div>

																<div class="progress progress-mini">
																	<div style="width:35%" class="progress-bar progress-bar-danger"></div>
																</div>
															</a>
														</li>

														<li>
															<a href="#">
																<div class="clearfix">
																	<span class="pull-left">Unit Testing</span>
																	<span class="pull-right">15%</span>
																</div>

																<div class="progress progress-mini">
																	<div style="width:15%" class="progress-bar progress-bar-warning"></div>
																</div>
															</a>
														</li>

														<li>
															<a href="#">
																<div class="clearfix">
																	<span class="pull-left">Bug Fixes</span>
																	<span class="pull-right">90%</span>
																</div>

																<div class="progress progress-mini progress-striped active">
																	<div style="width:90%" class="progress-bar progress-bar-success"></div>
																</div>
															</a>
														</li>
													</ul>
												</li>

												<li class="dropdown-footer">
													<a href="#">
														See tasks with details
														<i class="ace-icon fa fa-arrow-right"></i>
													</a>
												</li>
											</ul>
										</div><!-- /.tab-pane -->

										<div id="navbar-messages" class="tab-pane">
											<ul class="dropdown-menu-right dropdown-navbar dropdown-menu">
												<li class="dropdown-content">
													<ul class="dropdown-menu dropdown-navbar">
														<li>
															<a href="#">
																<img src="../assets/avatars/avatar.png" class="msg-photo" alt="Alex's Avatar" />
																<span class="msg-body">
																	<span class="msg-title">
																		<span class="blue">Alex:</span>
																		Ciao sociis natoque penatibus et auctor ...
																	</span>

																	<span class="msg-time">
																		<i class="ace-icon fa fa-clock-o"></i>
																		<span>a moment ago</span>
																	</span>
																</span>
															</a>
														</li>

														<li>
															<a href="#">
																<img src="../assets/avatars/avatar3.png" class="msg-photo" alt="Susan's Avatar" />
																<span class="msg-body">
																	<span class="msg-title">
																		<span class="blue">Susan:</span>
																		Vestibulum id ligula porta felis euismod ...
																	</span>

																	<span class="msg-time">
																		<i class="ace-icon fa fa-clock-o"></i>
																		<span>20 minutes ago</span>
																	</span>
																</span>
															</a>
														</li>

														<li>
															<a href="#">
																<img src="../assets/avatars/avatar4.png" class="msg-photo" alt="Bob's Avatar" />
																<span class="msg-body">
																	<span class="msg-title">
																		<span class="blue">Bob:</span>
																		Nullam quis risus eget urna mollis ornare ...
																	</span>

																	<span class="msg-time">
																		<i class="ace-icon fa fa-clock-o"></i>
																		<span>3:15 pm</span>
																	</span>
																</span>
															</a>
														</li>

														<li>
															<a href="#">
																<img src="../assets/avatars/avatar2.png" class="msg-photo" alt="Kate's Avatar" />
																<span class="msg-body">
																	<span class="msg-title">
																		<span class="blue">Kate:</span>
																		Ciao sociis natoque eget urna mollis ornare ...
																	</span>

																	<span class="msg-time">
																		<i class="ace-icon fa fa-clock-o"></i>
																		<span>1:33 pm</span>
																	</span>
																</span>
															</a>
														</li>

														<li>
															<a href="#">
																<img src="../assets/avatars/avatar5.png" class="msg-photo" alt="Fred's Avatar" />
																<span class="msg-body">
																	<span class="msg-title">
																		<span class="blue">Fred:</span>
																		Vestibulum id penatibus et auctor  ...
																	</span>

																	<span class="msg-time">
																		<i class="ace-icon fa fa-clock-o"></i>
																		<span>10:09 am</span>
																	</span>
																</span>
															</a>
														</li>
													</ul>
												</li>

												<li class="dropdown-footer">
													<a href="inbox.html">
														See all messages
														<i class="ace-icon fa fa-arrow-right"></i>
													</a>
												</li>
											</ul>
										</div><!-- /.tab-pane -->
									</div><!-- /.tab-content -->
								</div><!-- /.tabbable -->
							</div><!-- /.dropdown-menu -->
						</li>

						<!-- #section:basics/navbar.user_menu -->
						<li class="light-blue user-min">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<img class="nav-user-photo" src="../assets/avatars/user.jpg" alt="Jason's Photo" />
								<span class="user-info">
									<small>Welcome,</small>
									{{Auth::user()->fullName}}
								</span>

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<li>
									<a href="#">
										<i class="ace-icon fa fa-cog"></i>
										Settings
									</a>
								</li>

								<li>
									<a href="profile.html">
										<i class="ace-icon fa fa-user"></i>
										Profile
									</a>
								</li>

								<li class="divider"></li>

								<li>
									<a href="logout">
										<i class="ace-icon fa fa-power-off"></i>
										Logout
									</a>
								</li>
							</ul>
						</li>

						<!-- /section:basics/navbar.user_menu -->
					</ul>
				</div>
			</div><!-- /.navbar-container -->
		</div>

		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<!-- #section:basics/sidebar.horizontal -->
			<div id="sidebar" class="sidebar      h-sidebar                navbar-collapse collapse">
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
				</script>

				<div class="sidebar-shortcuts" id="sidebar-shortcuts">
					<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
						<button class="btn btn-success">
							<i class="ace-icon fa fa-signal"></i>
						</button>

						<button class="btn btn-info">
							<i class="ace-icon fa fa-pencil"></i>
						</button>

						<!-- #section:basics/sidebar.layout.shortcuts -->
						<button class="btn btn-warning">
							<i class="ace-icon fa fa-users"></i>
						</button>

						<button class="btn btn-danger">
							<i class="ace-icon fa fa-cogs"></i>
						</button>

						<!-- /section:basics/sidebar.layout.shortcuts -->
					</div>

					<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
						<span class="btn btn-success"></span>

						<span class="btn btn-info"></span>

						<span class="btn btn-warning"></span>

						<span class="btn btn-danger"></span>
					</div>
				</div><!-- /.sidebar-shortcuts -->

				<ul class="nav nav-list">
					<li class="hover">
						<a href="#">
							<i class="menu-icon fa fa-tachometer"></i>
							<span class="menu-text"> DASHBOARD </span>
						</a>
						<b class="arrow"></b>
					</li>
					
					<li class=" hover">
						<?php $jobs = Session::get("jobs");?>
						<?php if(in_array(1, $jobs)){?>
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-desktop"></i>
							<span class="menu-text">
								ADMINISTRATION
							</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>
						<?php }?>

						<b class="arrow"></b>

						<ul class="submenu">
							<?php if(in_array(101, $jobs)){?>
							<li class="hover">
								<a href="masters">
									<i class="menu-icon fa fa-caret-right"></i>
									MASTERS
								</a>
								<b class="arrow"></b>
							</li>
							<?php } if(in_array(102, $jobs)){?>
							<li class="hover">
								<a href="report?reporttype=dailysettlement">
									<i class="menu-icon fa fa-caret-right"></i>
									VERIFY BRANCH DAILY SETLEMENTS
								</a>
								<b class="arrow"></b>
							</li>
							<?php } if(in_array(103, $jobs)){?>
							<li class="hover">
								<a href="transactionblocking">
									<i class="menu-icon fa fa-caret-right"></i>
									TRANSACTION BLOCKING
								</a>
								<b class="arrow"></b>
							</li>
							<?php } if(in_array(104, $jobs)){?>								
							<li class="hover">
								<a href="roles">
									<i class="menu-icon fa fa-caret-right"></i>
									MANAGE PREVILAGES
								</a>
								<b class="arrow"></b>
							</li>
							<?php }?>
						</ul>
					</li>
					
					<li class="hover">
						<?php if(in_array(2, $jobs)){?>
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-pencil-square-o"></i>
							<span class="menu-text">
								INCOME &amp; EXPENSES &nbsp;
							</span>
							<b class="arrow fa fa-angle-down"></b>
						</a>
						<?php }?>
						<b class="arrow"></b>
						<ul class="submenu">
							<?php if(in_array(105, $jobs)){?>
							<li class="hover">
								<a href="incometransactions">
									<i class="menu-icon fa fa-caret-right"></i>
									INCOME TRANSACTIONS
								</a>
							</li>
							<li class="hover">
								<a href="expensetransactions">
									<i class="menu-icon fa fa-caret-right"></i>
									EXPENSE TRANSACTIONS
								</a>
							</li>
							<li class="hover">
								<a href="fueltransactions">
									<i class="menu-icon fa fa-caret-right"></i>
									FUEL TRANSACTIONS
								</a>
							</li>
							<?php } if(in_array(106, $jobs)){?>
							<li class="hover">
								<a href="repairtransactions">
									<i class="menu-icon fa fa-caret-right"></i>
									REPAIR TRANSACTIONS
								</a>
							</li>
							<?php } if(in_array(107, $jobs)){?>								
							<li class="hover">
								<a href="payemployeesalary">
									<i class="menu-icon fa fa-caret-right"></i>
									EMPLOYEE SALARY
								</a>
							</li>
							<?php } ?>
						</ul>
					</li>
					
					<li class="open hover">
						<?php if(in_array(3, $jobs)){?>
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-list"></i>
							<span class="menu-text">
								TRIPS &amp; SERVICES &nbsp;
							</span>
							<b class="arrow fa fa-angle-down"></b>
						</a>
						<?php }?>
						<b class="arrow"></b>
						<ul class="submenu">
							<?php if(in_array(109, $jobs)){?>		
							<li class="hover">
								<a href="managetrips?triptype=LOCAL">
									<i class="menu-icon fa fa-caret-right"></i>
									LOCAL TRIPS
								</a>
							</li>
							<?php } if(in_array(110, $jobs)){?>		
							<li class="hover">
								<a href="dailytrips">
									<i class="menu-icon fa fa-caret-right"></i>
									DAILY TRIPS
								</a>
							</li>
							<?php } ?>
						</ul>
					</li>

					<li class="open hover">
						<?php if(in_array(4, $jobs)){?>
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-tag"></i>
							<span class="menu-text">
								OTHERS  &nbsp;
							</span>
							<b class="arrow fa fa-angle-down"></b>
						</a>
						<?php }?>
						<b class="arrow"></b>
						<ul class="submenu">
							<li class="hover">
								<a href="leaves">
									<i class="menu-icon fa fa-caret-right"></i>
									EMPLOYEE LEAVES
								</a>
							</li>
							<li class="hover">
								<a href="salaryadvances">
									<i class="menu-icon fa fa-caret-right"></i>
									SALARY ADVANCES
								</a>
							</li>
							<?php if(in_array(111, $jobs)){?>		
							<li class="hover">
								<a href="#">
									<i class="menu-icon fa fa-caret-right"></i>
									CLIENT &AMP; CONTRACT
								</a>
							</li>
							<?php } if(in_array(112, $jobs)){?>		
							<li class="hover">
								<a href="#">
									<i class="menu-icon fa fa-caret-right"></i>
									STOCK &AMP; INVENTORY
								</a>
							</li>
							<?php  } if(in_array(113, $jobs)){?>		
							<li class="hover">
								<a href="#">
									<i class="menu-icon fa fa-caret-right"></i>
									BILLS &AMP; VOUCHERS
								</a>
							</li>
							<?php } if(in_array(114, $jobs)){?>										
							<li class="hover">
								<a href="#">
									<i class="menu-icon fa fa-caret-right"></i>
									BANK TRANSACTIONS
								</a>
							</li>
							<?php } if(in_array(115, $jobs)){?>										
							<li class="hover">
								<a href="#">
									<i class="menu-icon fa fa-caret-right"></i>
									VEHICLE INSPECTION BY DRIVER
								</a>
							</li>
							<?php } if(in_array(116, $jobs)){?>										
							<li class="hover">
								<a href="#">
									<i class="menu-icon fa fa-caret-right"></i>
									VEHICLE INSPECTION BY EMPLOYEE
								</a>
							</li>
							<?php } ?>
						</ul>
					</li>
					
					<li class="open hover">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-list-alt"></i>
							<span class="menu-text">
								STOCK & INVENTORY &nbsp;
							</span>
							<b class="arrow fa fa-angle-down"></b>
						</a>
						<b class="arrow"></b>
						<ul class="submenu">
							<li class="hover">
								<a href="inventorylookupvalues">
									<i class="menu-icon fa fa-caret-right"></i>
									LOOKUP VALUES
								</a>
							</li>
							<li class="hover">
								<a href="manufacturers">
									<i class="menu-icon fa fa-caret-right"></i>
									MANUFACTURERS
								</a>
							</li>
							<li class="hover">
								<a href="itemcategories">
									<i class="menu-icon fa fa-caret-right"></i>
									ITEM CATEGORIES
								</a>
							</li>
							<li class="hover">
								<a href="itemtypes">
									<i class="menu-icon fa fa-caret-right"></i>
									ITEM TYPES
								</a>
							</li>
							<li class="hover">
								<a href="items">
									<i class="menu-icon fa fa-caret-right"></i>
									ITEMS
								</a>
							</li>
							<li class="hover">
								<a href="purchaseorder">
									<i class="menu-icon fa fa-caret-right"></i>
									PURCHASE ORDERS
								</a>
							</li>
							<li class="hover">
								<a href="useitems">
									<i class="menu-icon fa fa-caret-right"></i>
									USE STOCK ITEMS
								</a>
							</li>
						</ul>
					</li>
					
					<li class="hover">
						<?php if(in_array(6, $jobs)){?>	
						<a href="reports">
							<i class="menu-icon  fa fa-bar-chart-o"></i>
							<span class="menu-text"> REPORTS </span>
						</a>
						<?php } ?>
						<b class="arrow"></b>
					</li>
					
					<li class="hover">
						<?php if(in_array(6, $jobs)){?>	
						<a href="#">
							<i class="menu-icon fa fa-cog"></i>
							<span class="menu-text"> SETTINGS </span>
						</a>
						<?php } ?>
						<b class="arrow"></b>
					</li>
				</ul><!-- /.nav-list -->

				<!-- #section:basics/sidebar.layout.minimize -->

				<!-- /section:basics/sidebar.layout.minimize -->
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
				</script>
			</div>

			<!-- /section:basics/sidebar.horizontal -->
			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">
						<!-- #section:settings.box -->
						<!-- /section:settings.box -->
						<div class="page-header">
							<h1>
								@yield('bredcum')
							</h1>
						</div><!-- /.page-header -->

						<div class="row">							
								<!-- PAGE CONTENT BEGINS -->
								@yield('page_content')
								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='../assets/js/jquery.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='../assets/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='../assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
		<script src="../assets/js/bootstrap.js"></script>

		<!-- page specific plugin scripts -->

		<!-- ace scripts -->
		<script src="../assets/js/ace/elements.scroller.js"></script>
		<script src="../assets/js/ace/elements.colorpicker.js"></script>
		<script src="../assets/js/ace/elements.fileinput.js"></script>
		<script src="../assets/js/ace/elements.typeahead.js"></script>
		<script src="../assets/js/ace/elements.wysiwyg.js"></script>
		<script src="../assets/js/ace/elements.spinner.js"></script>
		<script src="../assets/js/ace/elements.treeview.js"></script>
		<script src="../assets/js/ace/elements.wizard.js"></script>
		<script src="../assets/js/ace/elements.aside.js"></script>
		<script src="../assets/js/ace/ace.js"></script>
		<script src="../assets/js/ace/ace.ajax-content.js"></script>
		<script src="../assets/js/ace/ace.touch-drag.js"></script>
		<script src="../assets/js/ace/ace.sidebar.js"></script>
		<script src="../assets/js/ace/ace.sidebar-scroll-1.js"></script>
		<script src="../assets/js/ace/ace.submenu-hover.js"></script>
		<script src="../assets/js/ace/ace.widget-box.js"></script>
		<script src="../assets/js/ace/ace.settings.js"></script>
		<script src="../assets/js/ace/ace.settings-rtl.js"></script>
		<script src="../assets/js/ace/ace.settings-skin.js"></script>
		<script src="../assets/js/ace/ace.widget-on-reload.js"></script>
		<script src="../assets/js/ace/ace.searchbox-autocomplete.js"></script>
		
		@yield('page_js')

		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			function updateTime() {
				var date = new Date();
				hrs = date.getHours();
				mins =date.getMinutes();
				secs = date.getSeconds();
				if(date.getHours()<10){
					hrs = "0"+date.getHours();
				}
				if(date.getMinutes()<10){
					mins = "0"+date.getMinutes();
				}
				if(date.getSeconds()<10){
					secs = "0"+date.getSeconds();
				}
			    $('#time').html(
			    		hrs + ":" + mins + ":" +secs
		        );
			}
	
			setInterval(updateTime, 1000); // 5 * 1000 miliseconds
		
			jQuery(function($) {
			 var $sidebar = $('.sidebar').eq(0);
			 if( !$sidebar.hasClass('h-sidebar') ) return;
			
			 $(document).on('settings.ace.top_menu' , function(ev, event_name, fixed) {
				if( event_name !== 'sidebar_fixed' ) return;
			
				var sidebar = $sidebar.get(0);
				var $window = $(window);
			
				//return if sidebar is not fixed or in mobile view mode
				var sidebar_vars = $sidebar.ace_sidebar('vars');
				if( !fixed || ( sidebar_vars['mobile_view'] || sidebar_vars['collapsible'] ) ) {
					$sidebar.removeClass('lower-highlight');
					//restore original, default marginTop
					sidebar.style.marginTop = '';
			
					$window.off('scroll.ace.top_menu')
					return;
				}
			
			
				 var done = false;
				 $window.on('scroll.ace.top_menu', function(e) {
			
					var scroll = $window.scrollTop();
					scroll = parseInt(scroll / 4);//move the menu up 1px for every 4px of document scrolling
					if (scroll > 17) scroll = 17;
			
			
					if (scroll > 16) {			
						if(!done) {
							$sidebar.addClass('lower-highlight');
							done = true;
						}
					}
					else {
						if(done) {
							$sidebar.removeClass('lower-highlight');
							done = false;
						}
					}
			
					sidebar.style['marginTop'] = (17-scroll)+'px';
				 }).triggerHandler('scroll.ace.top_menu');
			
			 }).triggerHandler('settings.ace.top_menu', ['sidebar_fixed' , $sidebar.hasClass('sidebar-fixed')]);
			
			 $(window).on('resize.ace.top_menu', function() {
				$(document).triggerHandler('settings.ace.top_menu', ['sidebar_fixed' , $sidebar.hasClass('sidebar-fixed')]);
			 });
			
			
			});
		</script>		
		@yield('inline_js')

		<!-- the following scripts are used in demo only for onpage help and you don't need them -->
		<link rel="stylesheet" href="../assets/css/ace.onpage-help.css" />
		<link rel="stylesheet" href="../docs/assets/js/themes/sunburst.css" />

		<script type="text/javascript"> ace.vars['base'] = '..'; </script>
		<script src="../assets/js/ace/elements.onpage-help.js"></script>
		<script src="../assets/js/ace/ace.onpage-help.js"></script>
		<script src="../docs/assets/js/rainbow.js"></script>
		<script src="../docs/assets/js/language/generic.js"></script>
		<script src="../docs/assets/js/language/html.js"></script>
		<script src="../docs/assets/js/language/css.js"></script>
		<script src="../docs/assets/js/language/javascript.js"></script>
	</body>
</html>
