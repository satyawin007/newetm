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
				margin-top: 3px;
			}
			.ace-file-input {
			    text-align: left !important;
			}
			.chosen-container{
			  width: 100% !important;
			}
			#loading {
			  position: absolute;
			  top: 50%;
			  left: 50%;
			  width: 32px;
			  height: 32px;
			  /* 1/2 of the height and width of the actual gif */
			  margin: -16px 0 0 -16px;
			  z-index: 100;
			}
			.dt-buttons{
			  margin-top: 5px;
			}
		</style>
	@section('page_css')
		<link rel="stylesheet" href="../assets/css/jquery-ui.custom.css" />
		<link rel="stylesheet" href="../assets/css/chosen1.css" />
		<link rel="stylesheet" href="../assets/css/bootstrap-datepicker3.css"/>
		<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.1.1/css/buttons.dataTables.min.css"/>
	@stop
		
	@stop
	
	@section('bredcum')	
		<small>
			REPORTS
			<i class="ace-icon fa fa-angle-double-right"></i>
			{{$values['bredcum']}}
		</small>
	@stop

	@section('page_content')
		<div class="row" style="text-align: center; font-weight: bold;float:right;">
			<a href="report?reporttype=dailysettlementreport" target="_blank"><button class="btn btn-minier btn-success" style="font-size:13px; margin-right: 40px; margin-bottom: 10px;">CLICK HERE TO VIEW REPORTS </button> </a>
		</div>
		<div id="accordion1" class="col-xs-offset-0 col-xs-12 accordion-style1 panel-group" style="width: 99%;">			
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#TEST">
							<i class="ace-icon fa fa-angle-down bigger-110" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
							&nbsp;SEARCH BY
						</a>
					</h4>
				</div>
				<div class="panel-collapse collapse in" id="TEST">
					<div class="panel-body" style="padding: 0px">
						<?php $form_info = $values["form_info"]; ?>
						@include("reports.add3colform",$form_info)						
					</div>
				</div>
			</div>
		</div>	
		</div>
		
		<div id="reportbody">
			<div class="row" style="text-align: center; font-weight: bold;">
				<button class="btn btn-success" style="font-size:16px;">CARRY FORWARD FROM PREVIOUS DAY: <span id="cffromprev"></span></button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<button class="btn btn-danger" style="font-size:16px;">CARRY FORWARD TO NEXT DAY: <span id="cftonext"></span></button>
			</div>
			<form class="form-horizontal form-bordered form-validate" name="suspenseform" id="suspenseform" action="processbranchsuspense" method="post" novalidate="novalidate">
				<div class="row" style="margin-top: 10px; max-width: 101%">
					<div class="col-sm-6">
						<div class="widget-box">
							<div id="error_message_display" name="error_message_display" class="alert alert-info" style="display:none;"></div>			
							<div class="widget-header">
								<h4><i class="fa fa-th-list"></i>&nbsp;&nbsp;SOFTWARE DATA ENTRY</h4>
							</div>
							<div class="widget-body" style="padding:20px; padding-bottom: 5px;">
								<div class="form-group">
									<label for="fullName" class="control-label col-sm-4" style="background:#FEFAFA;color:green;font-weight:bold;">BOOKINGS INCOME</label>
									<div class="col-sm-8">
										<input type="text" data-rule-number="true" data-rule-required="true" disabled="disabled" id="swbookingincome" name="swbookingincome"   class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label for="fullName" class="control-label col-sm-4" style="background:#FEFAFA;color:red;font-weight:bold;">BOOKINGS CANCEL</label>
									<div class="col-sm-8">
										<input type="text" data-rule-number="true" data-rule-required="true" disabled="disabled" id="swbookingscancel" name="swbookingscancel"  class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label for="fullName" class="control-label col-sm-4" style="background:#FEFAFA;color:green;font-weight:bold;">CARGOS SIMPLY INCOME</label>
									<div class="col-sm-8">
										<input type="text" data-rule-number="true" data-rule-required="true" disabled="disabled" id="swcargossimplyincome" name="swcargossimplyincome"  class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label for="fullName" class="control-label col-sm-4" style="background:#FEFAFA;color:red;font-weight:bold;">CARGOS SIMPLY CANCEL</label>
									<div class="col-sm-8">
										<input type="text" data-rule-number="true" data-rule-required="true" disabled="disabled" id="swcargossimplycancel" name="swcargossimplycancel"  class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label for="fullName" class="control-label col-sm-4" style="background:#FEFAFA;color:green;font-weight:bold;">OTHER INCOME</label>
									<div class="col-sm-8">
										<input type="text" data-rule-number="true" data-rule-required="true" disabled="disabled" id="swotherincome" name="swotherincome" class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label for="fullName" class="control-label col-sm-4" style="background:#FEFAFA;color:green;font-weight:bold;">TOTAL INCOME</label>
									<div class="col-sm-8">
										<input type="text" data-rule-number="true" data-rule-required="true" disabled="disabled" id="swtotalincome" name="swtotalincome"class="form-control">
									</div>
								</div>
								
								<div class="form-group">
									<label for="fullName" class="control-label col-sm-4" style="background:#FEFAFA;color:red;font-weight:bold;">TOTAL EXPENSE</label>
									<div class="col-sm-8">
										<input type="text" data-rule-number="true" data-rule-required="true" disabled="disabled" id="swtotalexpense" name="swtotalexpense"  class="form-control">
									</div>
								</div>
								
								<div class="form-group">
									<label for="distance" class="control-label col-sm-4" style="background:#FEFAFA;color:blue;font-weight:bold;">BRANCH BALANCE </label>
									<div class="col-sm-8">
										<input type="text" data-rule-number="true" data-rule-required="true" disabled="disabled" id="swbranchbalance" name="swbranchbalance" class="form-control">
									</div>
								</div>
								
								<div class="form-group">
									<label for="anotherelem" class="control-label col-sm-4" style="background:#FEFAFA;color:brown;font-weight:bold;">BANK DEPOSITS</label>
									<div class="col-sm-8">
										<input type="text" data-rule-number="true" data-rule-required="true" disabled="disabled" id="swdepositamount" name="swdepositamount"  class="form-control">
									</div>
								</div>
			
								<div class="form-group">
									<label for="anotherelem" class="control-label col-sm-4" style="background:#FEFAFA;color:brown;font-weight:bold;">BRANCH DEPOSITS</label>
									<div class="col-sm-8">
										<input type="text" data-rule-number="true" data-rule-required="true" disabled="disabled" id="swbranchdeposit" name="swbranchdeposit"  class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label for="anotherelem" class="control-label col-sm-4" style="background:#FEFAFA;color:brown;font-weight:bold;">CARRY FORWARD AMOUNT</label>
									<div class="col-sm-4">
										<input type="text" disabled="" name="sbranchdeposited" disabled="disabled" id="sbranchdeposited"  class="form-control">
									</div>
									<div class="col-sm-4">
										<a onclick="createCarryForward()" class="btn btn-sm btn-primary" style="width:100%;">CREATE / EDIT</a>
									</div>
			
								</div>
								<div class="form-group">
									<label for="autocom" class="btn btn-sm btn-primary col-sm-4" style="background:#F5A9A9;font-weight:bold;">TODAY BRANCH BALANCE</label>
									<div class="col-sm-8">
										<input type="text" data-rule-number="true" data-rule-required="true" disabled="disabled" id="swtodaysuspense" name="swtodaysuspense" value="11525" class="form-control">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="widget-box">
							<div id="error_message_display" name="error_message_display" class="alert alert-info" style="display:none;"></div>			
							<div class="widget-header">
								<h4><i class="fa fa-th-list"></i>&nbsp;&nbsp;ACCOUNTS VERIFICATION</h4>
							</div>
							<div class="widget-body" style="padding:20px; padding-bottom: 5px;">
								<div class="form-group">
									<label for="fullName" class="control-label col-sm-4" style="background:#FEFAFA;color:green;font-weight:bold;">BOOKINGS INCOME</label>
									<div class="col-sm-8">
										<input type="text" data-rule-number="true" data-rule-required="true" id="acbookingincome" name="acbookingincome"   class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label for="fullName" class="control-label col-sm-4" style="background:#FEFAFA;color:red;font-weight:bold;">BOOKINGS CANCEL</label>
									<div class="col-sm-8">
										<input type="text" data-rule-number="true" data-rule-required="true" id="acbookingscancel" name="acbookingscancel"  class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label for="fullName" class="control-label col-sm-4" style="background:#FEFAFA;color:green;font-weight:bold;">CARGOS SIMPLY INCOME</label>
									<div class="col-sm-8">
										<input type="text" data-rule-number="true" data-rule-required="true" id="accargossimplyincome" name="accargossimplyincome"  class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label for="fullName" class="control-label col-sm-4" style="background:#FEFAFA;color:red;font-weight:bold;">CARGOS SIMPLY CANCEL</label>
									<div class="col-sm-8">
										<input type="text" data-rule-number="true" data-rule-required="true" id="accargossimplycancel" name="accargossimplycancel"  class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label for="fullName" class="control-label col-sm-4" style="background:#FEFAFA;color:green;font-weight:bold;">OTHER INCOME</label>
									<div class="col-sm-8">
										<input type="text" data-rule-number="true" data-rule-required="true" id="acotherincome" name="acotherincome"  class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label for="fullName" class="control-label col-sm-4" style="background:#FEFAFA;color:green;font-weight:bold;">TOTAL INCOME</label>
									<div class="col-sm-8">
										<input type="text" data-rule-number="true" data-rule-required="true" id="actotalincome" name="actotalincome" class="form-control">
									</div>
								</div>
								
								<div class="form-group">
									<label for="fullName" class="control-label col-sm-4" style="background:#FEFAFA;color:red;font-weight:bold;">TOTAL EXPENSE</label>
									<div class="col-sm-8">
										<input type="text" data-rule-number="true" data-rule-required="true" id="actotalexpense" name="actotalexpense" class="form-control">
									</div>
								</div>
								
								<div class="form-group">
									<label for="distance" class="control-label col-sm-4" style="background:#FEFAFA;color:blue;font-weight:bold;">BRANCH BALANCE </label>
									<div class="col-sm-8">
										<input type="text" data-rule-number="true" data-rule-required="true" id="acbranchbalance" name="acbranchbalance" class="form-control">
									</div>
								</div>
								
								<div class="form-group">
									<label for="anotherelem" class="control-label col-sm-4" style="background:#FEFAFA;color:brown;font-weight:bold;">BANK DEPOSITS</label>
									<div class="col-sm-8">
										<input type="text" data-rule-number="true" data-rule-required="true" id="acdepositamount" name="acdepositamount" class="form-control">
									</div>
								</div>
			
								<div class="form-group">
									<label for="anotherelem" class="control-label col-sm-4" style="background:#FEFAFA;color:brown;font-weight:bold;">BRANCH DEPOSITS</label>
									<div class="col-sm-8">
										<input type="text" data-rule-number="true" data-rule-required="true" id="acbranchdeposit" name="acbranchdeposit"  class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label for="anotherelem" class="control-label col-sm-4" style="background:#FEFAFA;color:brown;font-weight:bold;">CARRY FORWARD AMOUNT</label>
									<div class="col-sm-4">
										<input type="text" disabled="" name="sbranchdeposited" id="sbranchdeposited"  class="form-control">
									</div>
									<div class="col-sm-4">
										<a  class="btn btn-sm btn-primary" style="width:100%;">CREATE / EDIT</a>
									</div>
			
								</div>
								<div class="form-group">
									<label for="autocom" class="btn btn-sm btn-primary col-sm-4" style="background:#F5A9A9;font-weight:bold;">TODAY BRANCH BALANCE</label>
									<div class="col-sm-8">
										<input type="text" data-rule-number="true" data-rule-required="true" id="actodaysuspense" name="actodaysuspense" value="11525" class="form-control">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row" style="max-width: 101%; margin-top: 10px;">
					<div class="col-sm-12">
						<div class="widget-box">
							<div class="widget-header">
								<h4 style="float:left"><i class="fa fa-th-list"></i>&nbsp;&nbsp;OVERRALL VERIFICATION STATUS</h4>
								<h4 style="float:right;padding-right:10px;"><i class="fa fa-bars"></i>&nbsp;&nbsp;TOTAL BRANCH BALANCE: <span id="totalbalance"></span></h4>
							</div>
							<div class="widget-body" style="padding:10px;padding-bottom:0px;">
								<div class="form-group">
									<label for="text" class="control-label col-sm-3 right">OVERALL VERIFICATION STATUS</label>
									<div class="col-sm-3">
										<select name="verstatus" id="verstatus" data-rule-required="true" class="form-control" style="width:100%;">
											<option value="FULL AMOUNT RECIEVED">FULL AMOUNT RECIEVED</option>
											<option value="PARTIAL AMOUNT RECIEVED">PARTIAL AMOUNT RECIEVED</option>
											<option value="DATA MISMATCH">DATA MISMATCH</option>
										</select>
									</div>
									<label for="text" class="control-label col-sm-3 right">IT/NONIT REPORT DATE</label>
									<div class="col-sm-3">
										<input type="text"  name="itreportdate" id="itreportdate" class="form-control date-picker" >
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-12">
										<input type="hidden" name="reportbranchid" id="reportbranchid" >
										<input type="hidden" name="reportdate" id="reportdate" >
										<textarea name="vercomments" rows="3" data-rule-required="true" id="vercomments" rel="tooltip" title="" placeholder="Write the Verification Comments" class="form-control" style="width:100%;" data-original-title="Write the Verification Comments"></textarea>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-12" align="center">
									<input name="submit" class="btn btn-inverse" value="&nbsp;CLICK HERE TO SUBMIT THE VERIFICATION REPORT&nbsp;" type="submit">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-1"></div>
				</div>
			</form>
		</div>
		<div id="processing" class="modal-backdrop fade in"><div id = "loading" > <i  class="ace-icon fa fa-spinner fa-spin orange bigger-250"></i>	</div></div>
		<div class="row" >
			<div id="table1">
				<div class="row col-xs-12" style="padding-left:2%; padding-top: 1%">
					<?php if(!isset($values['entries'])) $values['entries']=10; if(!isset($values['branch'])) $values['branch']=0; if(!isset($values['page'])) $values['page']=1; ?>
					<div class="clearfix">
						<div id="tableTools-container1" class="pull-right tableTools-container"></div>
					</div>
					<div class="table-header" style="margin-top: 10px;">
						Results for <?php if(isset($values['reporttype'])){ echo '"'.strtoupper($values['reporttype'])." REPORT".'"';} ?>				 
					</div>
					<!-- div.table-responsive -->
					<!-- div.dataTables_borderWrap -->
					<div>
						<table id="dynamic-table1" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<td>BRANCH</td>
									<td>TRANSACTION TYPE</td>
									<td>DATE</td>
									<td>AMOUNT</td>
									<td>PURPOSE</td>
									<td>PAID TO/REC. FROM</td>
									<td>COMMENTS</td>
									<td>CREATED BY</td>
								</tr>
							</thead>
							<tbody id="tbody1">
							</tbody>
						</table>								
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
		
		<div id="edit" class="modal" tabindex="-1">
			<div class="modal-dialog" style="width: 80%">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="blue bigger">Please fill the following form fields</h4>
					</div>
	
					<div class="modal-body" id="modal_body">
					</div>
	
					<div class="modal-footer">
						<button class="btn btn-sm" data-dismiss="modal">
							<i class="ace-icon fa fa-times"></i>
							Close
						</button>
					</div>
				</div>
			</div>
		</div><!-- PAGE CONTENT ENDS -->
		
	@stop
	
	@section('page_js')
		<!-- page specific plugin scripts -->
		<script src="../assets/js/dataTables/jquery.dataTables.js"></script>
		<script src="../assets/js/dataTables/jquery.dataTables.bootstrap.js"></script>
		<script src="../assets/js/dataTables/extensions/buttons/dataTables.buttons.js"></script>
		<script src="../assets/js/dataTables/extensions/buttons/buttons.print.js"></script>
		<script type="text/javascript" src="../assets/js/dataTables/js/jszip.min.js"></script>
		<script type="text/javascript" src="../assets/js/dataTables/js/pdfmake.min.js"></script>
		<script type="text/javascript" src="../assets/js/dataTables/js/vfs_fonts.js"></script>
		<script type="text/javascript" src="../assets/js/dataTables/js/buttons.html5.min.js"></script>
		<script type="text/javascript" src="../assets/js/dataTables/js/buttons.colVis.min.js"></script>
		<script src="../assets/js/date-time/moment.js"></script>
		<script src="../assets/js/date-time/daterangepicker.js"></script>		
		<script src="../assets/js/date-time/bootstrap-datepicker.js"></script>
		<script src="../assets/js/bootbox.js"></script>
		<script src="../assets/js/chosen.jquery.js"></script>
		<script src="../assets/js/autosize.js"></script>
	@stop
	
	@section('inline_js')
		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			$("#processing").hide();
			reporttype = "";
			var rbranch = "";
			var rdate = "";

			function generateReport(){
				reporttype = "ticket_corgos_summery";
				paginate(1);
			}

			function showSelectionType(val){
				if(val=="balanceSheetNoDt" || val=="payment" || val=="balanceSheet" || val=="tracking"){
					$("#fuelstationid").show();
					$("#vehicleid").hide();
					$("#driverid").hide();
				}
				else if(val=="vehicleReport"){
					$("#fuelstationid").hide();
					$("#vehicleid").show();
					$("#driverid").hide();
				}
				else if(val=="employeeReport"){
					$("#fuelstationid").hide();
					$("#vehicleid").hide();
					$("#driverid").show();
				}
			}

			function paginate(page){
				reporttype = $("#branch").val();
				if(reporttype == ""){
					alert("select branch");
					return;
				}
				fdt = $("#date").val();
				if(fdt == ""){
					alert("select date");
					return;
				}
				var form=$("#getreport");	

				$("#processing").show();
				$.ajax({
			        type:"POST",
			        url:form.attr("action"),
			        data:form.serialize(),
			        success: function(response){
			           //alert(response);  
			           var json = JSON.parse(response);
			           json_data =  json["data"];
			           var arr = [];
			           for(var i = 0; i < json_data.length; i++) {
			        	    var parsed = json_data[i];
			        	    var row = [];
			        	    for(var x in parsed){
			        	    	row.push(parsed[x]);
				            }
				            arr.push(row);
			        	}
			           /*$resp_arr = array("data"=>$resp,"booking_income"=>$booking_income,"booking_cancel"=>$booking_cancel,"cargos_simply_income"=>$corgos_simply_income,
								"cargos_simply_cancel"=>$corgos_simply_cancel,"other_income"=>$other_income,"total_expense"=>$total_expenses,
								"branch_deposites"=>$branch_deposited,"bank_deposits"=>$bank_deposited
								);
								*/
			        	$("#swbookingincome").val(json["booking_income"]);
						$("#swbookingscancel").val(json["booking_cancel"]);
						$("#swcargossimplyincome").val(json["cargos_simply_income"]);
						$("#swcargossimplycancel").val(json["cargos_simply_cancel"]);
						$("#swotherincome").val(json["other_income"]);
						total_income = json["booking_income"]*1+json["booking_cancel"]*1+json["cargos_simply_income"]*1+json["cargos_simply_cancel"]*1+json["other_income"]*1;
						total_income = total_income.toFixed(2);
						$("#swtotalincome").val(total_income);
						$("#swtotalexpense").val(json["total_expense"]);
						balance = total_income-json["total_expense"];
						balance = balance.toFixed(2);
						$("#swbranchbalance").val(balance);
						$("#swdepositamount").val(json["bank_deposits"]);
						$("#swbranchdeposit").val(json["branch_deposites"]);
						today_balance = balance-(json["bank_deposits"]*1+json["branch_deposites"]*1);
						$("#sbranchdeposited").val(json["cf_amt"]);
						$("#cffromprev").html(json["cf_prev_amt"]);
						$("#cftonext").html(json["cf_amt"]);
						$("#swtodaysuspense").val(today_balance-json["cf_amt"]);

						$("#acbookingincome").val(json["booking_income"]);
						$("#acbookingscancel").val(json["booking_cancel"]);
						$("#accargossimplyincome").val(json["cargos_simply_income"]);
						$("#accargossimplycancel").val(json["cargos_simply_cancel"]);
						$("#acotherincome").val(json["other_income"]);
						total_income = json["booking_income"]*1+json["booking_cancel"]*1+json["cargos_simply_income"]*1+json["cargos_simply_cancel"]*1+json["other_income"]*1;
						total_income = total_income.toFixed(2);
						$("#actotalincome").val(total_income);
						$("#actotalexpense").val(json["total_expense"]);
						balance = total_income-json["total_expense"];
						balance = balance.toFixed(2);
						$("#acbranchbalance").val(balance);
						$("#acdepositamount").val(json["bank_deposits"]);
						$("#acbranchdeposit").val(json["branch_deposites"]);
						today_balance = balance-(json["bank_deposits"]*1+json["branch_deposites"]*1);
						$("#sbranchdeposited").val(json["cf_amt"]);
						$("#actodaysuspense").val(today_balance-json["cf_amt"]);
						$("#totalbalance").html(today_balance-json["cf_amt"]);
						$("#itreportdate").val(fdt);
						$("#reportdate").val(fdt);
						$("#reportbranchid").val(reporttype);
						myTable1.clear().draw();
						myTable1.rows.add(arr); // Add new data
						myTable1.columns.adjust().draw(); // Redraw 
						$("#table1").show();
						$("#reportbody").show();
						$("#processing").hide();
			        }
			    });
			}

			function modalEditLookupValue(id, value){
				$("#value1").val(value);
				$("#id1").val(id);
				return;				
			}

			function createCarryForward(){
				branch = $("#branch").val();
				dt = $("#date").val();
				balance = $("#swtodaysuspense").val();
				$("#processing").show();
				$.ajax({
			        type:"GET",
			        url:"carryforward?branch="+branch+"&date1="+dt+"&amount="+balance,
			        success: function(response){
			           //alert(response);  
			           //var json = JSON.parse(response);
			           //json_data =  json["data"];
			           if(response == "success"){
			        	   paginate(1);
			        	   bootbox.confirm('Carry Forward Created/Updated successfully!', function(result) {});
			           }
			           else if(response == "fail"){
			        	   bootbox.confirm('Carry Forward Created/Updated could not be done!', function(result) {});
			           }
			           //location.reload();
					   $("#processing").hide();
			        }
			    });
			}
			

			function modalEditTransaction(id){
				//$("#addfields").html('<div style="margin-left:600px; margin-top:100px;"><i class="ace-icon fa fa-spinner fa-spin orange bigger-125" style="font-size: 250% !important;"></i></div>');
				url = "edittransaction?type="+transtype+"&id="+id;
				var ifr=$('<iframe />', {
		            id:'MainPopupIframe',
		            src:url,
		            style:'seamless="seamless" scrolling="no" display:none;width:100%;height:423px; border:0px solid',
		            load:function(){
		                $(this).show();
		            }
		        });
	    	    $("#modal_body").html(ifr);
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

			$('.file').ace_file_input({
				no_file:'No File ...',
				btn_choose:'Choose',
				btn_change:'Change',
				droppable:false,
				onchange:null,
				thumbnail:false //| true | large
				//whitelist:'gif|png|jpg|jpeg'
				//blacklist:'exe|php'
				//onchange:''
				//
			});
			//pre-show a file name, for example a previously selected file
			//$('#id-input-file-1').ace_file_input('show_file_list', ['myfile.txt'])
		
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

			//or change it into a date range picker
			$('.input-daterange').datepicker({autoclose:true,todayHighlight: true});

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

			var myTable1 = null;
			var myTable2 = null;

			jQuery(function($) {
					//initiate dataTables plugin
					myTable1 = $('#dynamic-table1')
					//.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
					.DataTable( {
						dom: 'Bfrtip',
						buttons: [
							{
								extend:'colvis',
								text : "<i class='fa fa-search bigger-110 blue'></i> <span class='hidden'>Show/hide columns</span>"
							},
							{
								extend: 'excelHtml5',
								text : "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to Excel</span>",
								exportOptions: {
									columns: ':visible'
								}
							},
							{
								extend: 'pdfHtml5',
								text : "<i class='fa fa-file-pdf-o bigger-110 red'></i> <span class='hidden'>Export to PDF</span>",
								exportOptions: {
									columns: ':visible'
								}
							}
							
						], 
						bAutoWidth: false,
						"aoColumns": [
						  null, null, null,  null, null, null,  null, null
						],
						"aaSorting": [],
						//"sScrollY": "500px",
						//"bPaginate": false,
						"sScrollX" : "true",
						//"sScrollX": "300px",
						//"sScrollXInner": "120%",
						"bScrollCollapse": true,
						select: {
							style: 'multi'
						}
				    } );
					
					////
					setTimeout(function() {
						$("#table1").hide();
						$("#reportbody").hide();
					}, 500);
				})
			
		</script>
	@stop