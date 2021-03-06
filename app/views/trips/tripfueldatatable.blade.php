@extends('masters.master')
	@section('inline_css')
		<style>
			.pagination {
			    display: inline-block;
			    padding-left: 0;
			    padding-bottom:10px;
			    margin: 0px 0;
			    border-radius: 4px;
			}
			.dataTables_wrapper .row:last-child {
			    border-bottom: 0px solid #e0e0e0;
			    padding-top: 5px;
			    padding-bottom: 0px;
			    background-color: #EFF3F8;
			}
			th {
			    white-space: nowrap;
			}
			td {
			    white-space: nowrap;
			}
			panel-group .panel {
			    margin-bottom: 20px;
			    border-radius: 4px;
			}
			label{
				text-align: right;
				margin-top: 5px;
			}
			.chosen-container{
			  width: 100% !important;
			}
		</style>
	@section('page_css')
		<link rel="stylesheet" href="../assets/css/jquery-ui.custom.css" />
		<link rel="stylesheet" href="../assets/css/bootstrap-datepicker3.css"/>
		<link rel="stylesheet" href="../assets/css/chosen.css" />
	@stop
		
	@stop
	
	@section('bredcum')	
		<small>
			TRIPS & SERVICES
			<i class="ace-icon fa fa-angle-double-right"></i>
			{{$values['bredcum']}}
		</small>
	@stop

	@section('page_content')
		<div class="col-xs-offset-4 col-xs-8 ccordion-style1 panel-group">
			<a class="btn btn-sm btn-primary" href="dailytrips">CREATE/ADD SERVICES</a> &nbsp;&nbsp;
			<a class="btn btn-sm  btn-inverse" href="managetrips?triptype={{$values['transtype']}}">MANAGE TRIPS</a> &nbsp;&nbsp;
		</div>
		<div id="accordion1" class="col-xs-offset-0 col-xs-12 accordion-style1 panel-group">			
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#tripinfo">
							<i class="ace-icon fa fa-angle-down bigger-110" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
							&nbsp;TRIP INFORMATION
						</a>
					</h4>
				</div>
				<div class="panel-collapse collapse in" id="tripinfo">
					<div class="panel-body" style="padding: 0px">
					<div class="col-xs-offset-0 col-xs-12" style="margin-top: 1%; margin-bottom: 1%">
						<table id="simple-table" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>VEHICLE REG NO</th>
									<th>START DATE</th>
									<th>ROUTE INFORMATION</th>
									<th>DRIVER1</th>
									<th>DRIVER2</th>
									<th>HELPER</th>
									<th>CLOSE DATE</th>
									<th>ROUTES</th>
								</tr>
							</thead>
							<tbody>
								<tr>
							<?php 
								$select_args[] = "vehicle.veh_reg as vehicleId";
								$select_args[] = "tripdetails.tripStartDate as tripStartDate";
								$select_args[] = "tripdetails.id as routeInfo";
								$select_args[] = "tripdetails.tripCloseDate as tripCloseDate";
								$select_args[] = "tripdetails.routeCount as routes";
								$select_args[] = "tripdetails.id as id";
								
								if(isset($values["id"])){
									$entities = \TripDetails::where("tripdetails.id","=",$values["id"])->leftjoin("vehicle", "vehicle.id","=","tripdetails.vehicleId")->select($select_args)->get();
									foreach ($entities as $entity){
										$entity["tripStartDate"] = date("d-m-Y",strtotime($entity["tripStartDate"]));
										$entity["tripCloseDate"] = date("d-m-Y",strtotime($entity["tripCloseDate"]));
										if($entity["tripCloseDate"] == "01-01-1970"){
											$entity["tripCloseDate"] = "NOT CLOSED";
										}
										$entity["fuelamount"] = 0;
										$entity["routeInfo"] = "";
										$entity["totalAdvance"] = \TripAdvances::where("tripID","=",$entity->id)->where("deleted","=","No")->sum("amount");
										$routeInfo = "";
										$driver1 = "";
										$driver2 = "";
										$helper = "";
										$employees = \Employee::where("roleId","=","19")->orWhere("roleId","=","20")->get();
										$tripservices = \TripServiceDetails::where("tripId","=",$entity->id)->where("status","=","Running")->get();
										foreach($tripservices as $tripservice){
											$select_args = array();
											$select_args[] = "cities.name as sourceCity";
											$select_args[] = "cities1.name as destinationCity";
											$select_args[] = "servicedetails.serviceNo as serviceNo";
											$select_args[] = "servicedetails.active as active";
											$select_args[] = "servicedetails.serviceStatus as serviceStatus";
											$select_args[] = "servicedetails.id as id";
											$service = \ServiceDetails::where("servicedetails.id","=",$tripservice->serviceId)->join("cities","cities.id","=","servicedetails.sourceCity")->join("cities as cities1","cities1.id","=","servicedetails.destinationCity")->select($select_args)->get();
											if(count($service)>0){
												$service = $service[0];
												$routeInfo = $routeInfo."<span style='font-size:13px; font-weight:bold; color:red;'>".$service->serviceNo."</span> - &nbsp; ".$service->sourceCity." TO ".$service->destinationCity."<br/>";
											}
											foreach ($employees as $employee){
												if($employee->id == $tripservice->driver1){
													$driver1 = $employee->fullName;
												}
												else if($employee->id == $tripservice->driver2){
													$driver2 = $employee->fullName;
												}
												else if($employee->id == $tripservice->helper){
													$helper = $employee->fullName;
												}
											}
										}
										$entity["routeInfo"] = $routeInfo;
										echo "<td>".$entity->vehicleId."</td>";
										echo "<td>".$entity->tripStartDate."</td>";
										echo "<td>".$routeInfo."</td>";
										echo "<td>".$driver1."</td>";
										echo "<td>".$driver2."</td>";
										echo "<td>".$helper."</td>";
										echo "<td>".$entity->tripCloseDate."</td>";
										echo "<td>".$entity->routes."</td>";
									}
								}
							?>
								</tr>
							</tbody>
						</table>
					</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-offset-4 col-xs-8 ccordion-style1 panel-group">
			<a class="btn btn-sm  btn-purple" href="addtripparticular?id={{$values['id']}}&type=advances">ADD TRIP ADVANCE</a>&nbsp;&nbsp;
			<a class="btn btn-sm btn-purple" href="addtripparticular?id={{$values['id']}}&type=expenses_and_incomes">ADD TRIP EXPESENSES/INCOMES</a> &nbsp;&nbsp;
		</div>
		<div id="accordion1" class="col-xs-offset-0 col-xs-12 accordion-style1 panel-group">			
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#TEST">
							<i class="ace-icon fa fa-angle-down bigger-110" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
							&nbsp;ADD TRIP FUEL TRANSACTION
						</a>
					</h4>
				</div>
				<div class="panel-collapse collapse in" id="TEST">
					<div class="panel-body" style="padding: 0px">
						<?php $form_info = $values["form_info"]; ?>
						@include("trips.add3colform",$form_info)						
					</div>
				</div>
			</div>
		</div>	
		</div>		
		<div class="col-xs-12" >
			<h3 class="header smaller lighter blue" style="font-size: 15px; font-weight: bold;margin-bottom: -10px;">MANAGE TRANSACTIONS</h3>		
			<div class="col-xs-12" >
				<div>
					<div class="row" >
						<?php if(!isset($values['entries'])) $values['entries']=10; if(!isset($values['branch'])) $values['branch']=0; if(!isset($values['page'])) $values['page']=1; ?>
						<div class="table-header" style="margin-top: 10px;">
							Results for <?php if(isset($values['transtype'])){ echo '"'.strtoupper($values['transtype'])." TRANCTIONS".'"';} ?>				 
							<div style="float:right;padding-right: 15px;padding-top: 6px;"><a style="color: white;" href="{{$values['home_url']}}"><i class="ace-icon fa fa-home bigger-200"></i></a> &nbsp; &nbsp; &nbsp; <a style="color: white;"  href="{{$values['add_url']}}"><i class="ace-icon fa fa-plus-circle bigger-200"></i></a></div>				
						</div>
						<!-- div.table-responsive -->
						<!-- div.dataTables_borderWrap -->
						<div>
							<table id="dynamic-table" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<?php 
											$theads = $values['theads'];
											foreach($theads as $thead){
												echo "<th>".strtoupper($thead)."</th>";
											}
										?>
									</tr>
								</thead>
							</table>								
						</div>
					</div>					
				</div>
			</div>
		</div>

		<?php 
			if(isset($values['modals'])) {
				$modals = $values['modals'];
				foreach ($modals as $modal){
		?>
				@include('masters.layouts.modalform', $modal)
		<?php }} ?>
		
	@stop
	
	@section('page_js')
		<!-- page specific plugin scripts -->
		<script src="../assets/js/dataTables/jquery.dataTables.js"></script>
		<script src="../assets/js/dataTables/jquery.dataTables.bootstrap.js"></script>
		<script src="../assets/js/dataTables/extensions/buttons/dataTables.buttons.js"></script>
		<script src="../assets/js/dataTables/extensions/buttons/buttons.flash.js"></script>
		<script src="../assets/js/dataTables/extensions/buttons/buttons.html5.js"></script>
		<script src="../assets/js/dataTables/extensions/buttons/buttons.print.js"></script>
		<script src="../assets/js/dataTables/extensions/buttons/buttons.colVis.js"></script>
		<script src="../assets/js/dataTables/extensions/select/dataTables.select.js"></script>
		<script src="../assets/js/date-time/bootstrap-datepicker.js"></script>
		<script src="../assets/js/date-time/moment.js"></script>
		<script src="../assets/js/date-time/daterangepicker.js"></script>		
		<script src="../assets/js/bootbox.js"></script>
		<script src="../assets/js/chosen1.jquery.js"></script>
	@stop
	
	@section('inline_js')
		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			$("#entries").on("change",function(){paginate(1);});
			$("#branch").on("change",function(){$('#trantypebody').hide();  $("#transactionform").hide(); $('#incomebody').hide(); $('#expensebody').hide(); $('#verify').show();});
			$("#date").on("change",function(){$('#trantypebody').hide(); $("#transactionform").hide(); $('#incomebody').hide(); $('#expensebody').hide(); $('#verify').show();});
			
			transtype = "";

			showTranType("fuel");

			function test(){;
				paginate(1);
			}

			function setTranType1Value(val){
				transtype = val;
			}

			function paginate(page){
				if(transtype == ""){
					alert("select transaction type");
					return;
				}	
				branch = $("#branch1").val();
				if(branch == ""){
					alert("select branch");
					return;
				}				
				var myin = document.createElement("input"); 
				myin.type='hidden'; 
				myin.name='transtype'; 
				myin.value=transtype; 
				document.getElementById('paginate').appendChild(myin); 
				var myin = document.createElement("input"); 
				$("#page").val(page);
				$("#paginate").submit();				
			}

			function modalEditLookupValue(id, value){
				$("#value1").val(value);
				$("#id1").val(id);
				return;				
			}
			
			function verifyDate(){
				branch = $("#branch").val();
				dt = $("#date").val();
				if(branch == ""){
					alert("select branch office");
					return;
				}
				if(dt == ""){
					alert("select date");
					return;
				}
				$('#verify').hide();
				$('#trantypebody').show();
					
			}
			function showTranType(val){
				$("#formbody").hide();
				$("#addfields").hide();
				transtype = val;
				var myin = document.createElement("input"); 
				myin.type='hidden'; 
				myin.name='transtype'; 
				myin.value=val;
				document.getElementById('transactionform').appendChild(myin); 

				var myin = document.createElement("input"); 
				myin.type='hidden'; 
				myin.name='branch'; 
				myin.value=$("#branch").val();
				document.getElementById('transactionform').appendChild(myin); 
				
				var myin = document.createElement("input"); 
				myin.type='hidden'; 
				myin.name='date'; 
				myin.value=$("#date").val();
				document.getElementById('transactionform').appendChild(myin); 	
							
				if(val == "fuel"){	
					$('#transactionform').show();				
					$('#expensebody').hide();
					$('#incomebody').hide();
					$("#formbody").html('<div style="margin-left:600px; margin-top:100px;"><i class="ace-icon fa fa-spinner fa-spin orange bigger-125" style="font-size: 250% !important;"></i></div>');
					$("#formbody").show();					
					$.ajax({
				      url: "getfueltransactionfields?type=trips",
				      success: function(data) {
				    	  $("#formbody").html(data);
				    	  $('.date-picker').datepicker({
							autoclose: true,
							todayHighlight: true
						  });
				    	  $('.number').keydown(function(e) {
							 this.value = this.value.replace(/[^0-9.]/g, ''); 
							 this.value = this.value.replace(/(\..*)\./g, '$1');
						  });
						  $(".chosen-select").chosen();
						  $("#paymenttype").attr("disabled",true);
				    	  $("#formbody").show();
				    	  
				      },
				      type: 'GET'
				   });
				}	
			}
			function showForm(val){
				$('#addfields').hide(); 
				var myin = document.createElement("input"); 
				myin.type='hidden'; 
				myin.name='branch'; 
				myin.value=$("#branch").val(); 
				document.getElementById('transactionform').appendChild(myin);

				if(transtype == "income"){ 
					var myin = document.createElement("input"); 
					myin.type='hidden'; 
					myin.name='type'; 
					myin.value=$("#income").val(); 
					document.getElementById('transactionform').appendChild(myin);
				}
				if(transtype == "expense"){ 
					var myin = document.createElement("input"); 
					myin.type='hidden'; 
					myin.name='type'; 
					myin.value=$("#expense").val(); 
					document.getElementById('transactionform').appendChild(myin);
				} 
				if(transtype == "fuel"){ 
					var myin = document.createElement("input"); 
					myin.type='hidden'; 
					myin.name='type'; 
					myin.value="fuel"; 
					document.getElementById('transactionform').appendChild(myin);
				} 
				 
				var myin = document.createElement("input"); 
				myin.type='hidden'; 
				myin.name='date1'; 
				myin.value=$("#date").val();
				document.getElementById('transactionform').appendChild(myin);
				$('#transactionform').show();

				$("#formbody").html('<div style="margin-left:600px; margin-top:100px;"><i class="ace-icon fa fa-spinner fa-spin orange bigger-125" style="font-size: 250% !important;"></i></div>');
				$("#formbody").show();						
				
				$('#incomebody').show();
				$('#expensebody').hide();
				$.ajax({
			      url: "gettransactionfields?typeId="+val,
			      success: function(data) {
			    	  $("#formbody").html(data);
			    	  $('.date-picker').datepicker({
						autoclose: true,
						todayHighlight: true
					  });
			    	  $('.number').keydown(function(e) {
						 this.value = this.value.replace(/[^0-9.]/g, ''); 
						 this.value = this.value.replace(/(\..*)\./g, '$1');
					  });
			    	  $('.chosen-select').chosen();
			    	  $("#formbody").show();
			    	  
			      },
			      type: 'GET'
			   });	

			}
			function showPaymentFields(val){
				$("#addfields").html('<div style="margin-left:600px; margin-top:100px;"><i class="ace-icon fa fa-spinner fa-spin orange bigger-125" style="font-size: 250% !important;"></i></div>');
				$.ajax({
				      url: "getpaymentfields?paymenttype="+val,
				      success: function(data) {
				    	  $("#addfields").html(data);
				    	  $('.date-picker').datepicker({
							autoclose: true,
							todayHighlight: true
						  });
				    	  $("#addfields").show();
				      },
				      type: 'GET'
				   });
				
			}
// 			$('#trantypebody').hide();
// 			$('#incomebody').hide();
// 			$('#expensebody').hide();
// 			$('#transactionform').hide();
			

			function modalEditServiceProvider(id, branchId, provider, name, number,companyName, configDetails, address, refName,refNumber){
				$("#provider1 option").each(function() { this.selected = (this.text == provider); });
				$("#branch1 option").each(function() { this.selected = (this.text == branchId); });
				$("#name1").val(name);				
				$("#number1").val(number);
				$("#companyname1").val(companyName);
				$("#configdetails1").val(configDetails);
				$("#address1").val(address);
				$("#referencename1").val(refName);
				$("#referencenumber1").val(refNumber);
				$("#id1").val(id);		
			}

			function changeState(val){
				$.ajax({
			      url: "getcitiesbystateid?id="+val,
			      success: function(data) {
			    	  $("#cityname").html(data);
			      },
			      type: 'GET'
			   });
			}

			function calcTotal(){
				ltrs = $("#litres").val();
				price = $("#priceperlitre").val();
				$("#totalamount").val(ltrs*price);
			}

			function enablePaymentType(val){
				if(val == "Yes"){
					$("#paymenttype").attr("disabled",false);
				}
				else{
					$("#paymenttype").attr("disabled",true);
				}
			}
			function enableIncharge(val){
				if(val == "Yes"){
					$("#incharge").attr("disabled",false);
				}
				else{
					$("#incharge").attr("disabled",true);
				}
			}

			$("#reset").on("click",function(){
				$("#{{$form_info['name']}}").reset();
			});

			$("#submit").on("click",function(){
				//$("#{{$form_info['name']}}").submit();
			});

			$("#provider").on("change",function(){
				val = $("#provider option:selected").html();
				window.location.replace('serviceproviders?provider='+val);
			});

			$('.number').keydown(function(e) {
				this.value = this.value.replace(/[^0-9.]/g, ''); 
				this.value = this.value.replace(/(\..*)\./g, '$1');
			});
		
			//datepicker plugin
			//link
			$('.date-picker').datepicker({
				autoclose: true,
				todayHighlight: true
			})
			//show datepicker when clicking on the icon
			.next().on(ace.click_event, function(){
				$(this).prev().focus();
			});

			//$('.input-mask-phone').mask('(999) 999-9999');
			
			

			
			<?php 
				if(Session::has('message')){
					echo "bootbox.confirm('".Session::pull('message')."', function(result) {});";
				}
			?>

			//to translate the daterange picker, please copy the "examples/daterange-fr.js" contents here before initialization
			$('.date-range-picker').daterangepicker({
				'applyClass' : 'btn-sm btn-success',
				'cancelClass' : 'btn-sm btn-default',	
				locale: {
					applyLabel: 'Apply',
					cancelLabel: 'Cancel',
				}
			})
			<?php 
				if(isset($values["daterange"])){
					echo "$('.date-range-picker').val('".$values["daterange"]."')";
				}
			?>
			

			if(!ace.vars['touch']) {
				$('.chosen-select').chosen({allow_single_deselect:true}); 
				//resize the chosen on window resize
		
				$(window)
				.off('resize.chosen')
				.on('resize.chosen', function() {
					$('.chosen-select').each(function() {
						 var $this = $(this);
						 $this.next().css({'width': $this.parent().width()});
					})
				}).trigger('resize.chosen');
				//resize chosen on sidebar collapse/expand
				$(document).on('settings.ace.chosen', function(e, event_name, event_val) {
					if(event_name != 'sidebar_collapsed') return;
					$('.chosen-select').each(function() {
						 var $this = $(this);
						 $this.next().css({'width': $this.parent().width()});
					})
				});
		
		
				$('#chosen-multiple-style .btn').on('click', function(e){
					var target = $(this).find('input[type=radio]');
					var which = parseInt(target.val());
					if(which == 2) $('#form-field-select-4').addClass('tag-input-style');
					 else $('#form-field-select-4').removeClass('tag-input-style');
				});
			}

					

			jQuery(function($) {
				//initiate dataTables plugin
				var myTable = 
				$('#dynamic-table')
				//.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)

				//.wrap("<div id='tableData' style='width:300px; overflow: auto;overflow-y: hidden;-ms-overflow-y: hidden; position:relative; margin-right:5px; padding-bottom: 15px;display:block;'/>"); 
		
				.DataTable( {
					bJQueryUI: true,
					"bPaginate": true,
					bInfo: true,
					"aoColumns": [
					  <?php $cnt=count($values["theads"]); for($i=0; $i<$cnt; $i++){ echo '{ "bSortable": false },'; }?>
					],
					"aaSorting": [],
					oLanguage: {
				        sProcessing: '<i class="ace-icon fa fa-spinner fa-spin orange bigger-250"></i>'
				    },
					"bProcessing": true,
			        "bServerSide": true,
					"ajax":{
		                url :"gettransactiondatatabledata?name=<?php echo $values["provider"] ?>", // json datasource
		                type: "post",  // method  , by default get
		                error: function(){  // error handling
		                    $(".employee-grid-error").html("");
		                    $("#dynamic-table").append('<tbody class="employee-grid-error"><tr>No data found in the server</tr></tbody>');
		                    $("#employee-grid_processing").css("display","none");
		 
		                }
		            },
			
					//"sScrollY": "500px",
					//"bPaginate": false,
					"sScrollX" : "true",
					//"sScrollX": "300px",
					//"sScrollXInner": "120%",
					"bScrollCollapse": true,
					//Note: if you are applying horizontal scrolling (sScrollX) on a ".table-bordered"
					//you may want to wrap the table inside a "div.dataTables_borderWrap" element
			
					//"iDisplayLength": 50			
			
					select: {
						style: 'multi'
					}
			    } );
			
				
				
				$.fn.dataTable.Buttons.swfPath = "../assets/js/dataTables/extensions/buttons/swf/flashExport.swf"; //in Ace demo ../assets will be replaced by correct assets path
				$.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';
				
				/*new $.fn.dataTable.Buttons( myTable, {
					buttons: [
					  {
						"extend": "colvis",
						"text": "<i class='fa fa-search bigger-110 blue'></i> <span class='hidden'>Show/hide columns</span>",
						"className": "btn btn-white btn-primary btn-bold",
						columns: ':not(:first):not(:last)'
					  },
					  {
						"extend": "copy",
						"text": "<i class='fa fa-copy bigger-110 pink'></i> <span class='hidden'>Copy to clipboard</span>",
						"className": "btn btn-white btn-primary btn-bold"
					  },
					  {
						"extend": "csv",
						"text": "<i class='fa fa-database bigger-110 orange'></i> <span class='hidden'>Export to CSV</span>",
						"className": "btn btn-white btn-primary btn-bold"
					  },
					  {
						"extend": "excel",
						"text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to Excel</span>",
						"className": "btn btn-white btn-primary btn-bold"
					  },
					  {
						"extend": "pdf",
						"text": "<i class='fa fa-file-pdf-o bigger-110 red'></i> <span class='hidden'>Export to PDF</span>",
						"className": "btn btn-white btn-primary btn-bold"
					  },
					  {
						"extend": "print",
						"text": "<i class='fa fa-print bigger-110 grey'></i> <span class='hidden'>Print</span>",
						"className": "btn btn-white btn-primary btn-bold",
						autoPrint: false,
						message: 'This print was produced using the Print button for DataTables'
					  }		  
					]
				} );
				myTable.buttons().container().appendTo( $('.tableTools-container') );
				*/
				
				//style the message box
				var defaultCopyAction = myTable.button(1).action();
				myTable.button(1).action(function (e, dt, button, config) {
					defaultCopyAction(e, dt, button, config);
					$('.dt-button-info').addClass('gritter-item-wrapper gritter-info gritter-center white');
				});
				
				
				var defaultColvisAction = myTable.button(0).action();
				myTable.button(0).action(function (e, dt, button, config) {
					
					defaultColvisAction(e, dt, button, config);
					
					
					if($('.dt-button-collection > .dropdown-menu').length == 0) {
						$('.dt-button-collection')
						.wrapInner('<ul class="dropdown-menu dropdown-light dropdown-caret dropdown-caret" />')
						.find('a').attr('href', '#').wrap("<li />")
					}
					$('.dt-button-collection').appendTo('.tableTools-container .dt-buttons')
				});
			
				////
			
				setTimeout(function() {
					$($('.tableTools-container')).find('a.dt-button').each(function() {
						var div = $(this).find(' > div').first();
						if(div.length == 1) div.tooltip({container: 'body', title: div.parent().text()});
						else $(this).tooltip({container: 'body', title: $(this).text()});
					});
				}, 500);
				
				
				
				
				
				myTable.on( 'select', function ( e, dt, type, index ) {
					if ( type === 'row' ) {
						$( myTable.row( index ).node() ).find('input:checkbox').prop('checked', true);
					}
				} );
				myTable.on( 'deselect', function ( e, dt, type, index ) {
					if ( type === 'row' ) {
						$( myTable.row( index ).node() ).find('input:checkbox').prop('checked', false);
					}
				} );
			
			
			
			
				/////////////////////////////////
				//table checkboxes
				$('th input[type=checkbox], td input[type=checkbox]').prop('checked', false);
				
				//select/deselect all rows according to table header checkbox
				$('#dynamic-table > thead > tr > th input[type=checkbox], #dynamic-table_wrapper input[type=checkbox]').eq(0).on('click', function(){
					var th_checked = this.checked;//checkbox inside "TH" table header
					
					$('#dynamic-table').find('tbody > tr').each(function(){
						var row = this;
						if(th_checked) myTable.row(row).select();
						else  myTable.row(row).deselect();
					});
				});
				
				//select/deselect a row when the checkbox is checked/unchecked
				$('#dynamic-table').on('click', 'td input[type=checkbox]' , function(){
					var row = $(this).closest('tr').get(0);
					if(!this.checked) myTable.row(row).deselect();
					else myTable.row(row).select();
				});
			
			
			
				$(document).on('click', '#dynamic-table .dropdown-toggle', function(e) {
					e.stopImmediatePropagation();
					e.stopPropagation();
					e.preventDefault();
				});
				
				
				
				//And for the first simple table, which doesn't have TableTools or dataTables
				//select/deselect all rows according to table header checkbox
				var active_class = 'active';
				$('#simple-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function(){
					var th_checked = this.checked;//checkbox inside "TH" table header
					
					$(this).closest('table').find('tbody > tr').each(function(){
						var row = this;
						if(th_checked) $(row).addClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', true);
						else $(row).removeClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', false);
					});
				});
				
				//select/deselect a row when the checkbox is checked/unchecked
				$('#simple-table').on('click', 'td input[type=checkbox]' , function(){
					var $row = $(this).closest('tr');
					if(this.checked) $row.addClass(active_class);
					else $row.removeClass(active_class);
				});
			
				
			
				/********************************/
				//add tooltip for small view action buttons in dropdown menu
				$('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});
				
				//tooltip placement on right or left
				function tooltip_placement(context, source) {
					var $source = $(source);
					var $parent = $source.closest('table')
					var off1 = $parent.offset();
					var w1 = $parent.width();
			
					var off2 = $source.offset();
					//var w2 = $source.width();
			
					if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
					return 'left';
				}
				$('<button style="margin-top:-5px;" class="btn btn-minier btn-primary" id="refresh"><i style="margin-top:-2px; padding:6px; padding-right:5px;" class="ace-icon fa fa-refresh bigger-110"></i></button>').appendTo('div.dataTables_filter');
				$("#refresh").on("click",function(){ myTable.search( '', true ).draw(); });
			});
			
		</script>
	@stop