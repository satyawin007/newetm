@extends('masters.master')
	@section('inline_css')
		<style>
			.accordion-style1.panel-group .panel + .panel {
			    margin-top: 10px;
			}
		</style>
		<link rel="stylesheet" href="../assets/css/bootstrap-datepicker3.css"/>
	@stop

	@section('bredcum')	
		<small>
			ADMINISTRATION
			<i class="ace-icon fa fa-angle-double-right"></i>
			MASTERS
			<i class="ace-icon fa fa-angle-double-right"></i>
			EMPLOYEES
			<i class="ace-icon fa fa-angle-double-right"></i>
			ADD EMPLOYEE
		</small>
		<div style="float:right;padding-right: 8%;padding-top: 6px;"><a style="color: grey;" href="masters"><i class="ace-icon fa fa-home bigger-110"></i></a> &nbsp; <a style="color: grey;"  href="employees"><i class="ace-icon fa fa-arrow-circle-left bigger-110"></i></a></div>
	@stop

	@section('page_content')
		<div class="row">
			<div class="col-xs-1"></div>
			<div class="col-xs-10">
				<!-- #section:elements.accordion -->
				<form class="form-horizontal" action="addemployee" method="post" role="form" name="addemployee">
				<div id="accordion" class="accordion-style1 panel-group">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
									<i class="ace-icon fa fa-angle-down bigger-110" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
									&nbsp;BASIC INFORMATION
								</a>
							</h4>
						</div>
						<div class="panel-collapse collapse in" id="collapseOne">
							<div class="panel-body">
								<div class="col-xs-6">
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Full Name <span style="color:red;">*</span> </label>
										<div class="col-xs-8">
											<input type="text" id="fullname" name="fullname"  required="required" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Gender<span style="color:red;">*</span> </label>
										<div class="col-xs-8">
											<select class="form-control" name="gender"   required="required">
												<option selected="selected" value="">-- Select Gender --</option>
												<option selected="selected" value="Male">Male</option>
												<option selected="selected" value="Female">Female</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> State<span style="color:red;">*</span> </label>
										<div class="col-xs-8">
											<select class="form-control"   required="required" name="state" id="state" onChange="changeState(this.value)">
												<option selected="selected" value="">-- Select State --</option>
												<?php 
													$states = \State::All();
													foreach ($states as $state){
														echo "<option value='".$state->id."'>".$state->name."</option>";
													}
												?>	
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> City<span style="color:red;">*</span> </label>
										<div class="col-xs-8">
											<select class="form-control"   required="required" name="city" id="city" onchange="changeCity(this.value)">
												<option selected="selected" value="">-- Select City --</option>												
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Email Address <span style="color:red;">*</span> </label>
										<div class="col-xs-8">
											<input type="email" id="email"   required="required" name="email" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Password <span style="color:red;">*</span> </label>
										<div class="col-xs-8">
											<input type="password"   required="required" id="password" name="password" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Confirm Password <span style="color:red;">*</span> </label>
										<div class="col-xs-8">
											<input type="password" id="confirm_password"   required="required" name="confirm_password" class="form-control">
										</div>
									</div>
								</div>
								
								<div class="col-xs-6">
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Designation<span style="color:red;">*</span> </label>
										<div class="col-xs-8">
											<select class="form-control" required="required"  name="designation" onChange="getEmpId()"">
												<option value="">-- Select Designation --</option>
												<?php 
													$roles = \UserRoleMaster::All();
													foreach ($roles as $role){
														echo "<option value='".$role->id."'>".$role->name."</option>";
													}
												?>												
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Role-Previlage </label>
										<div class="col-xs-8">
											<select class="form-control"   name="roleprevilage" >
												<option value="">-- Select Role Previlage --</option>
												<?php 
													$roles = \Role::All();
													foreach ($roles as $role){
														echo "<option value='".$role->id."'>".$role->roleName."</option>";
													}
												?>												
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Work Group<span style="color:red;">*</span> </label>
										<div class="col-xs-8">
											<select class="form-control" name="workgroup"   required="required">
												<option selected="selected" value="">-- Select Work group --</option>
												<option selected="selected" value="DRIVER">DRIVER</option>
												<option selected="selected" value="HELPER">HELPER</option>
												<option selected="selected" value="OFFICE EMPLOYEE">OFFICE EMPLOYEE</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Employee Type<span style="color:red;">*</span> </label>
										<div class="col-xs-8">
											<select class="form-control" name="employeetype"   required="required">
												<option selected="selected" value="">--Select Employee type--</option>
												<option value="1">Office</option>
												<option value="2">Non-Office</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Office Branch<span style="color:red;">*</span> </label>
										<div class="col-xs-8">
											<select class="form-control" name="officebranch" id="branch"   required="required">
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Employee Number <span style="color:red;">*</span> </label>
										<div class="col-xs-8">
											<input type="text" id="employeeid" name="employeeid" class="form-control" readonly="readonly">
										</div>
									</div>
								</div>								
							</div>
						</div>
					</div>
					
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
									<i class="ace-icon fa fa-angle-right bigger-110" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
									&nbsp;ADVANCED INFORMATION
								</a>
							</h4>
						</div>
						<div class="panel-collapse collapse" id="collapseTwo">
							<div class="panel-body">
								<div class="col-xs-6">
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Date of Birth   </label>
										<div class="col-xs-8">
											<input type="text" id="dateofbirth" name="dateofbirth" placeholder="format : dd-mm-yyyy" class="form-control date-picker">
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Age   </label>
										<div class="col-xs-8">
											<input type="text" id="age" name="age"  class="form-control number">
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Father Name   </label>
										<div class="col-xs-8">
											<input type="text" id="fathername" name="fathername"  class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Religion  </label>
										<div class="col-xs-8">
											<select name="religion" id="religion" class="form-control valid">
											   <option value=""> -- Select Religion -- </option>
											   <option value="Hindu"> Hindu </option>
											   <option value="Muslim"> Muslim </option>
											   <option value="Christian"> Christian </option>
											   <option value="Others"> Others </option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Residence  </label>
										<div class="col-xs-8">
											<select name="residance" id="residance" class="form-control valid" onchange="detailField(this.value)">
											   <option value=""> -- select Residance -- </option>
											   <option value="local"> local </option>
											   <option value="non-local"> non-local </option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Provide details if Non-local   </label>
										<div class="col-xs-8">
											<input type="text" id="nonlocaldetails" name="nonlocaldetails" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Phone number  </label>
										<div class="col-xs-8">
											<input type="text" id="phonenumber" name="phonenumber" class="form-control input-mask-phone">
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Home number  </label>
										<div class="col-xs-8">
											<input type="text" id="homenumber" name="homenumber" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> ID Proof name  </label>
										<div class="col-xs-8">
											<select class="form-control" name="idproof" >
												<option value="">--Select Id Proof--</option>
												<option value="PAN CARD">PAN CARD</option>
												<option value="PASSPORT">PASSPORT</option>
												<option value="VOTER ID">VOTER ID</option>
												<option value="BANK PASSBOOK">BANK PASSBOOK</option>
												<option value="AADHAR CARD">AADHAR CARD</option>			
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> ID Proof number  </label>
										<div class="col-xs-8">
											<input type="text" id="idproofnumber" name="idproofnumber" class="form-control">
										</div>
									</div>
								</div>
								
								<div class="col-xs-6">
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Present Address </label>
										<div class="col-xs-8">
											<textarea name="presentaddress" id="presentaddress" rows="3" cols="44"></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Permanent Address </label>
										<div class="col-xs-8">
											<textarea  name="permnentaddress" id="permnentaddress" rows="3" cols="44"></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Joining Date</label>
										<div class="col-xs-8">
											<input type="text" name="joiningdate" id="joiningdate" placeholder="format : dd-mm-yyyy" class="form-control  date-picker">
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> RTA Office Name  </label>
										<div class="col-xs-8">
											<select class="form-control" name="rtaoffice" id="rtaoffice">
												<option value="">-- Select RTA office name --</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Aadhaar Number</label>
										<div class="col-xs-8">
											<input type="text" name="aadhdaarnumber" id="aadhdaarnumber" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Ration Card Number</label>
										<div class="col-xs-8">
											<input type="text" name="rationcardnumber" id="rationcardnumber" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Driving Licence</label>
										<div class="col-xs-8">
											<input type="text" name="drivinglicence" id="drivinglicence" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Driving Licence Expire Date</label>
										<div class="col-xs-8">
											<input type="text" name="drivingliceneexpiredate" id="drivingliceneexpiredate" class="form-control date-picker">
										</div>
									</div>
								</div>								
							</div>
						</div>
					</div>	
					
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
									<i class="ace-icon fa fa-angle-right bigger-110" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
									&nbsp;BANK DETAILS
								</a>
							</h4>
						</div>

						<div class="panel-collapse collapse" id="collapseThree">
							<div class="panel-body">
								<div class="col-xs-6">								
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Account Number </label>
										<div class="col-xs-8">
											<input type="text" name="accountnumber" id="accountnumber" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Bank Name </label>
										<div class="col-xs-8">
											<input type="text" name="bankname" id="bankname" class="form-control">
										</div>
									</div>
								</div>
								<div class="col-xs-6">								
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> IFSC Code </label>
										<div class="col-xs-8">
											<input type="text" name="ifsccode" id="ifsccode" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Branch Name </label>
										<div class="col-xs-8">
											<input type="text" name="branchname" id="branchname" class="form-control">
										</div>
									</div>
								</div>
							</div>							
						</div>
					</div>
					
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<div class="row">
								<div class="col-xs-11">
								<a  class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
									<i class="ace-icon fa fa-angle-right bigger-110" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
									&nbsp;FAMILY DETAILS 
								</a>
								</div>
								<div style="margin-top: 5px;" class="col-xs-1"><i id="family_add" class="ace-icon fa fa-plus-circle bigger-160"></i> &nbsp;&nbsp; <i id="family_remove" class="ace-icon fa fa-minus-circle bigger-160"></i></div>
								</div>
								
							</h4>
						</div>

						<div class="panel-collapse collapse" id="collapseFour">
							<div class="panel-body">
								<div id="family_fields_all">
									<div id="family_fields" style="border-bottom: 2px solid; border-color: #CDD8E3; padding-top: 13px; padding-bottom: 5px;" class="family_fields row">
										<div class="col-xs-4">								
											<div class="form-group">
												<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Name </label>
												<div class="col-xs-8">
													<input type="text" name="family_name[]" class="form-control">
												</div>
											</div>
											<div class="form-group">
												<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Relationship</label>
												<div class="col-xs-8">
													<select class="form-control" name="family_relationship[]">
														<option value="">-- Relationship --</option>
														<option value="Father">Father</option>
														<option value="Mother">Mother</option>
														<option value="Spouse">Spouse</option>
														<option value="Husband">Husband</option>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Gender</label>
												<div class="col-xs-8">
													<select class="form-control" name="family_gender[]">
														<option value="">-- Gender --</option>
														<option value="Male">Male</option>
														<option value="Female">Female</option>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Age </label>
												<div class="col-xs-8">
													<input type="text" name="family_age[]" class="form-control number">
												</div>
											</div>
											<div class="form-group">
												<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Nominee</label>
												<div class="col-xs-8">
													<select class="form-control" name="family_nominee[]">
														<option value="">-- Nominee --</option>
														<option value="Father">Yes</option>
														<option value="Mother">No</option>
													</select>
												</div>
											</div>
										</div>
										<div class="col-xs-4">								
											<div class="form-group">
												<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Job </label>
												<div class="col-xs-8">
													<input type="text" name="family_job[]" class="form-control">
												</div>
											</div>
											<div class="form-group">
												<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Aadhaar Number </label>
												<div class="col-xs-8">
													<input type="text" name="family_aadhaar[]" class="form-control">
												</div>
											</div>
											<div class="form-group">
												<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Education </label>
												<div class="col-xs-8">
													<input type="text" name="family_education[]" class="form-control">
												</div>
											</div>
											<div class="form-group">
												<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Mobile Number </label>
												<div class="col-xs-8">
													<input type="text" name="family_mobile[]" class="form-control">
												</div>
											</div>
										</div>
										<div class="col-xs-4">								
											<div class="form-group">
												<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Account Number </label>
												<div class="col-xs-8">
													<input type="text" name="family_accountnumber[]" class="form-control">
												</div>
											</div>
											<div class="form-group">
												<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> IFSC Code </label>
												<div class="col-xs-8">
													<input type="text" name="family_ifsccode[]" class="form-control">
												</div>
											</div>
											<div class="form-group">
												<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Bank Name </label>
												<div class="col-xs-8">
													<input type="text" name="family_bankname[]" class="form-control">
												</div>
											</div>
											<div class="form-group">
												<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Branch Name </label>
												<div class="col-xs-8">
													<input type="text" name="family_branchname[]" class="form-control">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<div class="row">
								<div class="col-xs-11">
								<a  class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
									<i class="ace-icon fa fa-angle-right bigger-110" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
									&nbsp;CHILDEN DETAILS 
								</a>
								</div>
								<div style="margin-top: 5px;" class="col-xs-1"><i id="children_add" class="ace-icon fa fa-plus-circle bigger-160"></i> &nbsp;&nbsp; <i id="children_remove" class="ace-icon fa fa-minus-circle bigger-160"></i></div>
								</div>
								
							</h4>
						</div>

						<div class="panel-collapse collapse" id="collapseFive">
							<div class="panel-body">
								<div id="children_fields_all">
									<div id="children_fields" style="border-bottom: 2px solid; border-color: #CDD8E3; padding-top: 13px; padding-bottom: 5px;" class="children_fields row">
										<div class="row">								
											<div class="form-group inline col-xs-4">
												<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Name </label>
												<div class="col-xs-8">
													<input type="text" name="children_name[]" class="form-control">
												</div>
											</div>
											<div class="form-group inline col-xs-2">
												<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Gender</label>
												<div class="col-xs-8">
													<select class="form-control" name="children_gender[]">
														<option value="">-- Gender --</option>
														<option value="Male">Male</option>
														<option value="Female">Female</option>
													</select>
												</div>
											</div>
											<div class="form-group inline col-xs-2">
												<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Age </label>
												<div class="col-xs-8">
													<input type="text" name="children_age[]" class="form-control">
												</div>
											</div>
											<div class="form-group inline col-xs-4">
												<label class="col-xs-4 control-label no-padding-right" for="form-field-1"> Education </label>
												<div class="col-xs-8">
													<input type="text" name="children_education[]" class="form-control">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>								
				</div>
				<div class="clearfix form-actions">
					<div class="col-md-offset-3 col-md-9">
						<button id="submit" class="btn" type="submit">
							<i class="ace-icon fa fa-check bigger-110"></i>
							SUBMIT
						</button>

						&nbsp; &nbsp; &nbsp;
						<button id="reset" class="btn" type="reset">
							<i class="ace-icon fa fa-undo bigger-110"></i>
							RESET
						</button>
					</div>
				</div>
				</form>

				<!-- /section:elements.accordion -->
			</div><!-- /.col -->
		</div>
	@stop
	
	@section('inline_js')
		<script src="../assets/js/date-time/bootstrap-datepicker.js"></script>
		<script src="../assets/js/bootbox.js"></script>
		<script src="../assets/js/jquery.maskedinput.js"></script>
		
		<script>
			function getEmpId(){
				$.ajax({
			      url: "getempid",
			      success: function(data) {
			    	  $("#employeeid").val(data);
			      },
			      type: 'GET'
			   });
			}

			function changeState(val){
				$.ajax({
			      url: "getcitiesbystateid?id="+val,
			      success: function(data) {
			    	  $("#city").html(data);
			    	  $("#rtaoffice").html(data);
			      },
			      type: 'GET'
			   });
			}

			function changeCity(val){
				$.ajax({
			      url: "getbranchbycityid?id="+val,
			      success: function(data) {
			    	  $("#branch").html(data);
			      },
			      type: 'GET'
			   });
			}

			$("#family_add").on("click",function(){
				ele = $('#family_fields:first-child').clone();
                ele.appendTo('#family_fields_all').find('.input-xxlarge').val('').focus();
			});

			$("#family_remove").on("click",function(){
				if(($(".family_fields").length)>1)
					$('#family_fields:last-child').remove();
			});
			$("#children_add").on("click",function(){
				ele = $('#children_fields:first-child').clone();
                ele.appendTo('#children_fields_all').find('.input-xxlarge').val('').focus();
			});

			$("#children_remove").on("click",function(){
				if(($(".children_fields").length)>1)
					$('#children_fields:last-child').remove();
			});
			
			$("#submit").on("click",function(){
				$("#addemployee").submit(); 
			});			
			
			$("#reset").on("click",function(){
				$("#addemployee").reset();
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

			$('.input-mask-phone').mask('(999) 999-9999');
			
			<?php 
				if(Session::has('message')){
					echo "bootbox.confirm('".Session::pull('message')."', function(result) {});";
				}
			?>
			
			
		</script>
	@stop
