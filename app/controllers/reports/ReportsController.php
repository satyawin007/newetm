<?php namespace reports;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
class ReportsController extends \Controller {

	
	
	/**
	 * add a new state.
	 *
	 * @return Response
	 */
	
	public function carryForward()
	{
		$values = Input::All();
		$values["type"] = "194";
		$nextDay = strtotime(date("Y-m-d", strtotime($values["date1"])) . " +1 day");
		$nextDay = date ( 'Y-m-d' , $nextDay );
		$values["remarks"] = "C/F from ".$values["date1"];
		$values["date1"] = $nextDay;
		$values["paymenttype"] = "cash";
		
		$cf_details = \IncomeTransaction::where("branchId","=",$values["branch"])->where("date","=",$nextDay)->where("status","=","ACTIVE")->where("lookupValueId","=","194")->get();
		if(count($cf_details)>0){
			$cf_details = $cf_details[0];
			$values["amount"] = $cf_details->amount+$values["amount"];
			$field_names = array("branch"=>"branchId","amount"=>"amount","paymenttype"=>"paymentType", "transtype"=>"name", "type"=>"lookupValueId",
					"branch1"=>"branchId1","incharge"=>"inchargeId","employee"=>"employeeId","vehicle"=>"vehicleIds", "bankId"=>"bankId",
					"remarks"=>"remarks","bankaccount"=>"bankAccount","chequenumber"=>"chequeNumber","issuedate"=>"issueDate",
					"transactiondate"=>"transactionDate", "suspense"=>"suspense", "date1"=>"date","accountnumber"=>"accountNumber","bankname"=>"bankName"
			);
			$fields = array();
			foreach ($field_names as $key=>$val){
				if(isset($values[$key])){
					$fields[$val] = $values[$key];
				}
			}
			$fields["name"] = "income";
			$db_functions_ctrl = new DBFunctionsController();
			$table = "IncomeTransaction";
			$data = array("id"=>$cf_details->transactionId);
			if($db_functions_ctrl->updatetrans($table, $fields, $data)){
				echo "success";
				return;
			}
			else{
				echo "fail";
				return;
			}
		}
		$field_names = array("branch"=>"branchId","amount"=>"amount","paymenttype"=>"paymentType", "transtype"=>"name", "type"=>"lookupValueId",
				"branch1"=>"branchId1","incharge"=>"inchargeId","employee"=>"employeeId","vehicle"=>"vehicleIds", "bankId"=>"bankId",
				"remarks"=>"remarks","bankaccount"=>"bankAccount","chequenumber"=>"chequeNumber","issuedate"=>"issueDate",
				"transactiondate"=>"transactionDate", "suspense"=>"suspense", "date1"=>"date","accountnumber"=>"accountNumber","bankname"=>"bankName"
		);
		$fields = array();
		foreach ($field_names as $key=>$val){
			if(isset($values[$key])){
				$fields[$val] = $values[$key];
			}
		}
		$transid =  strtoupper(uniqid().mt_rand(100,999));
		$chars = array("a"=>"1","b"=>"2","c"=>"3","d"=>"4","e"=>"5","f"=>"6");
		foreach($chars as $k=>$v){
			$transid = str_replace($k, $v, $transid);
		}
		$fields["transactionId"] = $transid;
		$fields["source"] = "income transaction";
		$db_functions_ctrl = new DBFunctionsController();
		$table = "IncomeTransaction";
		if($db_functions_ctrl->insert($table, $fields)){
			echo "success";
			return;
		}
		else{
			echo "fail";
			return;
		}
		
	}
	
	public function processBranchSuspense(){
		$values = Input::all();
		$field_names = array("reportbranchid"=>"branchId","reportdate"=>"reportDate","itreportdate"=>"itReportDate", "acbookingincome"=>"bookings_income", "acbookingscancel"=>"bookings_cancel",
				"accargossimplyincome"=>"cargos_simply_income","accargossimplycancel"=>"cargos_simply_cancel","acotherincome"=>"other_income","actotalincome"=>"total_income", "actotalexpense"=>"total_expense",
				"acdepositamount"=>"bank_deposit","acbranchdeposit"=>"branch_deposit","actodaysuspense"=>"today_suspense","adjustedamount"=>"adjusted_amount",
				"verstatus"=>"verification_status", "vercomments"=>"comments"
		);
		$fields = array();
		foreach ($field_names as $key=>$val){
			if(isset($values[$key])){
				if($key == "reportdate" || $key == "itreportdate" ){
					$fields[$val] = date("Y-m-d",strtotime($values[$key]));
				}
				else {
					$fields[$val] = $values[$key];
				}
			}
		}
		
		$branch_suspense = \BranchSuspenseReport::where("branchId","=",$fields["branchId"])->where("reportDate","=",$fields["reportDate"])->get();
		if(count($branch_suspense)>0){
			$db_functions_ctrl = new DBFunctionsController();
			$table = "BranchSuspenseReport";
			$data = array("branchId"=>$fields["branchId"],"reportDate"=>$fields["reportDate"]);
			if($db_functions_ctrl->updatesuspense($table, $fields, $data)){
				\Session::put("message","Operation completed Successfully");
				return \Redirect::to("report?reporttype=dailysettlement");
			}
			else{
				\Session::put("message","Operation Could not be completed, Try Again!");
				return \Redirect::to("report?reporttype=dailysettlement");
			}
		}
		
		$db_functions_ctrl = new DBFunctionsController();
		$table = "BranchSuspenseReport";
		if($db_functions_ctrl->insert($table, $fields)){
			\Session::put("message","Operation completed Successfully");
			return \Redirect::to("report?reporttype=dailysettlement");
		}
		else{
			\Session::put("message","Operation Could not be completed, Try Again!");
			return \Redirect::to("report?reporttype=dailysettlement");
		}
	}

	
	/**
	 * add a new state.
	 *
	 * @return Response
	 */
	
	public function getReport()
	{
		$values = Input::all();
		if(isset($values["reporttype"]) && $values["reporttype"] == "dailytransactions"){
			return $this->getDailyTransactiosReport($values);
		}
		if(isset($values["reporttype"]) && $values["reporttype"] == "dailysettlement"){
			return $this->getDailySettlementReport($values);
		}
		if(isset($values["reporttype"]) && $values["reporttype"] == "dailysettlementreport"){
			return $this->getDailySettlementReportsReport($values);
		}
		if(isset($values["reporttype"]) && $values["reporttype"] == "fuel"){
			return $this->getFuelReport($values);
		}
		if(isset($values["reporttype"]) && $values["reporttype"] == "salaryadvances"){
			return $this->getSalaryAdvancesReport($values);
		}
		if(isset($values["reporttype"]) && $values["reporttype"] == "salary"){
			return $this->getSalaryReport($values);
		}
		if(isset($values["reporttype"]) && $values["reporttype"] == "inchargetransactions"){
			return $this->getInchargeTransactionsReport($values);
		}
		if(isset($values["reporttype"]) && $values["reporttype"] == "loans"){
			return $this->getLoansReport($values);
		}
		if(isset($values["reporttype"]) && $values["reporttype"] == "dailyfinance"){
			return $this->getDailyFinanceReport($values);
		}
		if(isset($values["reporttype"]) && $values["reporttype"] == "bankposition"){
			return $this->getBankPositionReport($values);
		}
	}
	
	private function getDailyTransactiosReport($values){
		if (\Request::isMethod('post'))
		{
			$frmDt = date("Y-m-d", strtotime($values["fromdate"]));
			$toDt = date("Y-m-d", strtotime($values["todate"]));
			$brachId = $values["branch"];
			$empId = $values["employee"];
			$reportFor = $values["reportfor"];
			$resp = array();
			if($values["btntype"] == "ticket_corgos_summery"){
				if($brachId == 0){
					$branches =  \OfficeBranch::OrderBy("name")->get();
					foreach ($branches as $branch){
						$recs = DB::select( DB::raw("select lookupValueId,sum(amount) as amt from incometransactions where paymentType='CASH' and  branchId=".$branch->id." and date between '".$frmDt."' and '".$toDt."' group by lookupValueId order By lookupValueId"));
						if(count($recs)>0) {
							$row = array();
							$row["branch"] = "<a href='getreportdetails?branchid=".$branch->id."&fromdate=".$frmDt."&todate=".$toDt."&empId=".$empId."&reportfor=".$reportFor."' title='get report details'>".$branch->name."</a>";
							$totalAmt = 0;
							foreach ($recs as $rec){
								if($rec->lookupValueId==11){
									$row["tickets"] = $rec->amt;
									$totalAmt = $totalAmt+$rec->amt;
								}
								if($rec->lookupValueId==12){
									$row["ticketcancel"] = $rec->amt;
									$totalAmt = $totalAmt+$rec->amt;
								}
								if($rec->lookupValueId==13){
									$row["cargosimply"] = $rec->amt;
									$totalAmt = $totalAmt+$rec->amt;
								}
								if($rec->lookupValueId==14){
									$row["cargosimplycancel"] = $rec->amt;
									$totalAmt = $totalAmt+$rec->amt;
								}
							}
							if(!isset($row["tickets"])){
								$row["tickets"] = 0;
							}
							if(!isset($row["ticketcancel"])){
								$row["ticketcancel"] = 0;
							}
							if(!isset($row["cargosimply"])){
								$row["cargosimply"] = 0;
							}
							if(!isset($row["cargosimplycancel"])){
								$row["cargosimplycancel"] = 0;
							}
							if(!isset($row["cargos"])){
								$row["cargos"] = 0;
							}
							$row["total"] = $totalAmt;
							$resp[] = $row;
						}
					}
				}
				else if($brachId > 0){
					$recs = DB::select( DB::raw("select lookupValueId,sum(amount) as amt from incometransactions where paymentType='CASH' and  branchId=".$brachId." and date between '".$frmDt."' and '".$toDt."' group by lookupValueId order By lookupValueId"));
					if(count($recs)>0) {
						$row = array();
						$brachName = \OfficeBranch::where("id","=",$brachId)->first();
						$brachName = $brachName->name;
						$row["branch"] = "<a href='getreportdetails?branchid=".$brachId."&fromdate=".$frmDt."&todate=".$toDt."&empId=".$empId."&reportfor=".$reportFor."' title='get report details'>".$brachName."</a>";
						$totalAmt = 0;
						foreach ($recs as $rec){
							if($rec->lookupValueId==11){
								$row["tickets"] = $rec->amt;
								$totalAmt = $totalAmt+$rec->amt;
							}
							if($rec->lookupValueId==12){
								$row["ticketcancel"] = $rec->amt;
								$totalAmt = $totalAmt+$rec->amt;
							}
							if($rec->lookupValueId==13){
								$row["cargosimply"] = $rec->amt;
								$totalAmt = $totalAmt+$rec->amt;
							}
							if($rec->lookupValueId==14){
								$row["cargosimplycancel"] = $rec->amt;
								$totalAmt = $totalAmt+$rec->amt;
							}
						}
						if(!isset($row["tickets"])){
							$row["tickets"] = 0;
						}
						if(!isset($row["ticketcancel"])){
							$row["ticketcancel"] = 0;
						}
						if(!isset($row["cargosimply"])){
							$row["cargosimply"] = 0;
						}
						if(!isset($row["cargosimplycancel"])){
							$row["cargosimplycancel"] = 0;
						}
						if(!isset($row["cargos"])){
							$row["cargos"] = 0;
						}
						$row["total"] = $totalAmt;
						$resp[] = $row;
					}
				}
			}
			else if($values["btntype"] == "txn_details"){
				DB::statement(DB::raw("CALL daily_transactions_report('".$frmDt."', '".$toDt."');"));
				if($brachId == 0 && $reportFor=="0"){
					$recs = DB::select( DB::raw("select * from temp_daily_transaction order by branchId"));
				}
				else if($brachId > 0 && $reportFor=="0"){
					$recs = DB::select( DB::raw("select * from temp_daily_transaction where branchId=".$brachId." order by branchId"));
				}
				else if($brachId > 0 && $reportFor != "0"){
					$recs = DB::select( DB::raw("select * from temp_daily_transaction where branchId=".$brachId." and name='".$reportFor."' order by branchId"));
				}
				else if($brachId == 0 && $reportFor != "0"){
					$recs = DB::select( DB::raw("select * from temp_daily_transaction where  name='".$reportFor."' order by branchId"));
				}
				if(count($recs)>0) {
					$totalAmt = 0;
					foreach ($recs as $rec){
						$row = array();
						$brachName = "";
						if($rec->branchId>0){
							$brachName = \OfficeBranch::where("id","=",$rec->branchId)->first();
							$brachName = $brachName->name;
						}
						$row = array();
						$row["branch"] = $brachName;
						$row["type"] = strtoupper($rec->type);
						$row["date"] = date("d-m-Y",strtotime($rec->date));
						$row["amount"] = $rec->amount;
						$row["purpose"] = strtoupper($rec->name);
						if($rec->lookupValueId==999){
							if($rec->entityValue>0){
								$prepaidName = \LookupTypeValues::where("id","=",$rec->entityValue)->first();
								$prepaidName = $prepaidName->name;
								$row["purpose"] = strtoupper($rec->entity);
								$row["employee"] = $prepaidName;
							}
							else{
								$row["purpose"] = strtoupper($rec->entity);
								$row["employee"] = "";
							}
						}
						else if($rec->lookupValueId==73){
							$bankdetails = \IncomeTransaction::where("transactionId","=",$rec->rowid)->leftjoin("bankdetails","bankdetails.id","=","incometransactions.bankId")->first();
							$bankdetails = $bankdetails->bankName." - ".$bankdetails->accountNo;
							$row["employee"] = $bankdetails;
						}
						else if($rec->lookupValueId==84){
							$bankdetails = \ExpenseTransaction::where("transactionId","=",$rec->rowid)->leftjoin("bankdetails","bankdetails.id","=","expensetransactions.bankId")->first();
							$bankdetails = $bankdetails->bankName." - ".$bankdetails->accountNo;
							$row["employee"] = $bankdetails;
						}
						else{
							if($rec->entityValue != "0"){
								$row["employee"] = $rec->entity." - ".$rec->entityValue;
							}
							else{
								$row["employee"] = $rec->entity;
							}
								
						}
						$row["comments"] = $rec->remarks;
						//$row["billno"] = $rec->billNo;
						$row["createdby"] = $rec->createdBy;
						$totalAmt = $totalAmt+$rec->amount;
						$row["total"] = $totalAmt;
						$resp[] = $row;
					}
				}
			}
			echo json_encode($resp);
			return;
		}
		
		$values['bredcum'] = strtoupper($values["reporttype"]);
		$values['home_url'] = 'masters';
		$values['add_url'] = 'getreport';
		$values['form_action'] = 'getreport';
		$values['action_val'] = '';
		$theads = array('Bank Name','Branch Name', "Account Name", "Account No", "Account Type");
		$values["theads"] = $theads;
		
		$form_info = array();
		$form_info["name"] = "getreport";
		$form_info["action"] = "getreport";
		$form_info["method"] = "post";
		$form_info["class"] = "form-horizontal";
		$form_info["back_url"] = "bankdetails";
		$form_info["bredcum"] = "add bank details";
		$form_info["reporttype"] = $values["reporttype"];
		
		$form_fields = array();
		
		$branches =  \OfficeBranch::All();
		$branches_arr = array();
		$branches_arr["0"] = "ALL BRANCHES";
		foreach ($branches as $branch){
			$branches_arr[$branch->id] = $branch->name;
		}
		
		$emps =  \Employee::All();
		$emps_arr = array();
		$emps_arr["0"] = "ALL EMPLOYEES";
		foreach ($emps as $emp){
			$emps_arr[$emp->id] = $emp->fullName;
		}
		
		$parentId = -1;
		$parent = \LookupTypeValues::where("name","=","INCOME")->get();
		if(count($parent)>0){
			$parent = $parent[0];
			$parentId = $parent->id;
		}
		$incomes =  \LookupTypeValues::where("parentId","=",$parentId)->where("status","=","ACTIVE")->get();
		$transtype_arr = array();
		$transtype_arr["0"] = "ALL";
		foreach ($incomes as $income){
			$transtype_arr [$income->name] = strtoupper($income->name);
		}
		
		$parentId = -1;
		$parent = \LookupTypeValues::where("name","=","EXPENSE")->get();
		if(count($parent)>0){
			$parent = $parent[0];
			$parentId = $parent->id;
		}
		$incomes =  \LookupTypeValues::where("parentId","=",$parentId)->where("status","=","ACTIVE")->get();
		foreach ($incomes as $income){
			$transtype_arr [$income->name] = strtoupper($income->name);
		}
		
		$form_field = array("name"=>"branch", "content"=>"branch name", "readonly"=>"",  "required"=>"required","type"=>"select", "options"=>$branches_arr, "class"=>"form-control chosen-select");
		$form_fields[] = $form_field;
		$form_field = array("name"=>"employee", "content"=>"employee", "readonly"=>"",  "required"=>"required","type"=>"select", "options"=>$emps_arr, "class"=>"form-control chosen-select");
		$form_fields[] = $form_field;
		$form_field = array("name"=>"daterange", "content"=>"date range", "readonly"=>"",  "required"=>"required","type"=>"daterange", "class"=>"form-control");
		$form_fields[] = $form_field;
		$form_field = array("name"=>"reportfor", "content"=>"report for ", "readonly"=>"",  "required"=>"required","type"=>"select", "options"=>$transtype_arr, "class"=>"form-control chosen-select");
		$form_fields[] = $form_field;
		$form_field = array("name"=>"reporttype", "value"=>$values["reporttype"], "content"=>"", "readonly"=>"",  "required"=>"required","type"=>"hidden");
		$form_fields[] = $form_field;
		$form_info["form_fields"] = $form_fields;
		$values["form_info"] = $form_info;
		
		$values["provider"] = "bankdetails";
		return View::make('reports.dailytransactionreport', array("values"=>$values));
	}
	
	private function getSalaryAdvancesReport($values){
		if (\Request::isMethod('post'))
		{
			$frmDt = date("Y-m-d", strtotime($values["fromdate"]));
			$toDt = date("Y-m-d", strtotime($values["todate"]));
			$resp = array();
			$select_args = array();
			$select_args[] = "employee.fullName as empname";
			$select_args[] = "empdueamount.amount as amount";
			$select_args[] = "empdueamount.paymentDate as paymentDate";
			$select_args[] = "officebranch.name as branch";
			$select_args[] = "empdueamount.comments as remarks";
			$select_args[] = "empdueamount.id as id";
			$totaladvances = 0;
			$totalreturns  = 0;
			if(true){
				if($values["employee"] == "0"){
					$salaryadvances =  \EmpDueAmount::where("empdueamount.status","=","ACTIVE")->orWhere("empdueamount.deleted","=","No")->join("employee","employee.id","=","empdueamount.empId")->leftjoin("officebranch","officebranch.id","=","empdueamount.branchId")->OrderBy("paymentDate")->select($select_args)->get();
					foreach ($salaryadvances as $salaryadvance){
						$row = array();
						$row["empname"] = $salaryadvance->empname;
						if($salaryadvance->amount>0){
							$totaladvances = $totaladvances+$salaryadvance->amount;
							$row["amount"] = "<span style='color:green'> ".$salaryadvance->amount."</span>";
						}
						else{
							$totalreturns = $totaladvances+$totalreturns;
							$row["amount"] = "<span style='color:red'> ".$salaryadvance->amount."</span>";
						}
						$row["paymentDate"] = date("d-m-Y",strtotime($salaryadvance->paymentDate));
						$row["branch"] = $salaryadvance->branch;
						$row["remarks"] = $salaryadvance->remarks;
						$row["id"] = $salaryadvance->id;
						$resp[] = $row;
					}
				}
				else if($values["employee"] > 0){
				$salaryadvances =  \EmpDueAmount::where("empdueamount.empId","=",$values["employee"])
							->where(function($query){$query->where("empdueamount.status","=","ACTIVE")->orWhere("empdueamount.deleted","=","No");})->join("employee","employee.id","=","empdueamount.empId")->leftjoin("officebranch","officebranch.id","=","empdueamount.branchId")->OrderBy("paymentDate")->select($select_args)->get();
					foreach ($salaryadvances as $salaryadvance){
						$row = array();
						$row["empname"] = $salaryadvance->empname;
						if($salaryadvance->amount>0){
							$totaladvances = $totaladvances+$salaryadvance->amount;
							$row["amount"] = "<span style='color:green'> ".$salaryadvance->amount."</span>";
						}
						else{
							$totalreturns = $totaladvances+$totalreturns;
							$row["amount"] = "<span style='color:red'> ".$salaryadvance->amount."</span>";
						}
						$row["paymentDate"] = date("d-m-Y",strtotime($salaryadvance->paymentDate));
						$row["branch"] = $salaryadvance->branch;
						$row["remarks"] = $salaryadvance->remarks;
						$row["id"] = $salaryadvance->id;
						$resp[] = $row;
					}
				}
			}
			echo json_encode($resp);
			return;
		}
	
		$values['bredcum'] = strtoupper($values["reporttype"]);
		$values['home_url'] = 'masters';
		$values['add_url'] = 'getreport';
		$values['form_action'] = 'getreport';
		$values['action_val'] = '';
		$theads = array('Bank Name','Branch Name', "Account Name", "Account No", "Account Type");
		$values["theads"] = $theads;
	
		$form_info = array();
		$form_info["name"] = "getreport";
		$form_info["action"] = "getreport";
		$form_info["method"] = "post";
		$form_info["class"] = "form-horizontal";
		$form_info["back_url"] = "bankdetails";
		$form_info["bredcum"] = "add bank details";
		$form_info["reporttype"] = $values["reporttype"];
	
		$form_fields = array();
		$select_args = array();
		$select_args[] = "fuelstationdetails.id as id";
		$select_args[] = "fuelstationdetails.name as fname";
		$select_args[] = "cities.name as cname";
	
		$branches =  \Employee::ALL();
		$branches_arr = array();
		$branches_arr["0"] = "ALL EMPLOYEES";
		foreach ($branches as $branch){
			$branches_arr[$branch->id] = $branch->fullName." (".$branch->empCode.")";
		}
		$form_field = array("name"=>"daterange", "content"=>"date range", "readonly"=>"",  "required"=>"required","type"=>"daterange", "class"=>"form-control");
		$form_fields[] = $form_field;
		$form_field = array("name"=>"employee", "content"=>"report for ", "readonly"=>"",  "required"=>"required","type"=>"select",  "options"=>$branches_arr, "class"=>"form-control chosen-select");
		$form_fields[] = $form_field;
		$form_field = array("name"=>"reporttype", "value"=>$values["reporttype"], "content"=>"", "readonly"=>"",  "required"=>"required","type"=>"hidden");
		$form_fields[] = $form_field;
		
		$add_form_fields = array();
		$emps =  \Employee::where("roleId","=",19)->get();
		$emps_arr = array();
		$emps_arr["0"] = "ALL DRIVERS";
		foreach ($emps as $emp){
			$emps_arr[$emp->id] = $emp->fullName;
		}
		
		$vehs =  \Vehicle::where("status","=","ACTIVE")->get();
		$vehs_arr = array();
		foreach ($vehs as $veh){
			$vehs_arr[$veh->id] = $veh->veh_reg;
		}
		$form_field = array("name"=>"fuelstation", "content"=>"by station", "readonly"=>"",  "required"=>"required","type"=>"select", "options"=>$branches_arr, "class"=>"form-control chosen-select");
		$add_form_fields[] = $form_field;
		$form_field = array("name"=>"driver", "content"=>"by driver", "readonly"=>"",  "required"=>"required","type"=>"select", "options"=>$emps_arr, "class"=>"form-control chosen-select");
		$add_form_fields[] = $form_field;
		$form_field = array("name"=>"vehicle", "content"=>"by vehicle", "readonly"=>"",  "required"=>"required","type"=>"select", "options"=>$vehs_arr, "class"=>"form-control chosen-select");
		$add_form_fields[] = $form_field;

		$form_info["form_fields"] = $form_fields;
		$form_info["add_form_fields"] = $add_form_fields;
		$values["form_info"] = $form_info;
		$values["provider"] = "bankdetails";
		return View::make('reports.salaryadvancesreport', array("values"=>$values));
	}
	
	private function getBankPositionReport($values){
		if (\Request::isMethod('post'))
		{
			$frmDt = date("Y-m-d", strtotime($values["fromdate"]));
			$toDt = date("Y-m-d", strtotime($values["todate"]));
			$resp = array();
			$select_args = array();
			$select_args[] = "employee.fullName as empname";
			$select_args[] = "empdueamount.amount as amount";
			$select_args[] = "empdueamount.paymentDate as paymentDate";
			$select_args[] = "officebranch.name as branch";
			$select_args[] = "empdueamount.comments as remarks";
			$select_args[] = "empdueamount.id as id";
			$totaladvances = 0;
			$totalreturns  = 0;
			
			DB::statement(DB::raw("CALL bankposition_report('".$frmDt."', '".$toDt."');"));
			if($values["reportfor"] == "transaction_details"){
				if($values["bank"] == "0" && $values["branch"] == "0"){
					$recs = DB::select( DB::raw("SELECT * FROM temp_bankposition_transaction;"));
					foreach ($recs as $rec){
						$row = array();
						$row["type"] = "DEBIT";
						if($rec->type=="income" || $rec->type=="LOCAL"){
							$row["type"] = "CREDIT";
						}
						$row["purpose"] = strtoupper($rec->name);
						if($rec->lookupValueId==991|| $rec->lookupValueId==996){
							$row["purpose"] = strtoupper($rec->entity);
						}
						$row["date"] = date("d-m-Y", strtotime($rec->date));
						$row["chque"] = "";
						if($rec->paymentType=="cheque_credit" || $rec->paymentType=="cheque_debit"){
							$row["chque"] = $rec->chequeNumber;
						}
						$row["amount"] = $rec->amount;
						$row["obalance"] = "0.00";
						$row["cbalance"] = "0.00";
						$row["desc"] = $rec->remarks;
						$resp[] = $row;
					}
				}
			}
			echo json_encode($resp);
			return;
		}
	
		$values['bredcum'] = strtoupper($values["reporttype"]);
		$values['home_url'] = 'masters';
		$values['add_url'] = 'getreport';
		$values['form_action'] = 'getreport';
		$values['action_val'] = '';
		$theads = array('Bank Name','Branch Name', "Account Name", "Account No", "Account Type");
		$values["theads"] = $theads;
	
		$form_info = array();
		$form_info["name"] = "getreport";
		$form_info["action"] = "getreport";
		$form_info["method"] = "post";
		$form_info["class"] = "form-horizontal";
		$form_info["back_url"] = "bankdetails";
		$form_info["bredcum"] = "add bank details";
		$form_info["reporttype"] = $values["reporttype"];
	
		$form_fields = array();
		$select_args = array();
		$select_args[] = "fuelstationdetails.id as id";
		$select_args[] = "fuelstationdetails.name as fname";
		$select_args[] = "cities.name as cname";
	
		$branches =  \OfficeBranch::ALL();
		$branches_arr = array();
		$branches_arr["0"] = "ALL BRANCHES";
		foreach ($branches as $branch){
			$branches_arr[$branch->id] = $branch->name;
		}
		$banks =  \BankDetails::ALL();
		$banks_arr = array();
		$banks_arr["0"] = "ALL BANKS";
		foreach ($banks as $bank){
			$banks_arr[$bank->id] = $bank->bankName;
		}
		$form_field = array("name"=>"reportfor", "content"=>"report for ", "readonly"=>"",  "required"=>"required","type"=>"select",  "options"=>array("bank_summary"=>"Bank Summary Report","transaction_details"=>"Transaction Details Report"), "class"=>"form-control chosen-select");
		$form_fields[] = $form_field;
		$form_field = array("name"=>"daterange", "content"=>"date range", "readonly"=>"",  "required"=>"required","type"=>"daterange", "class"=>"form-control");
		$form_fields[] = $form_field;
		$form_field = array("name"=>"bank", "content"=>"bank ", "readonly"=>"",  "required"=>"required","type"=>"select",  "options"=>$banks_arr, "class"=>"form-control chosen-select");
		$form_fields[] = $form_field;
		$form_field = array("name"=>"branch", "content"=>"branch ", "readonly"=>"",  "required"=>"required","type"=>"select",  "options"=>$branches_arr, "class"=>"form-control chosen-select");
		$form_fields[] = $form_field;
		$form_field = array("name"=>"reporttype", "value"=>$values["reporttype"], "content"=>"", "readonly"=>"",  "required"=>"required","type"=>"hidden");
		$form_fields[] = $form_field;
		$form_info["form_fields"] = $form_fields;
		$values["form_info"] = $form_info;
		$values["provider"] = "bankdetails";
		return View::make('reports.bankpositionreport', array("values"=>$values));
	}
	
	private function getLoansReport($values){
		if (\Request::isMethod('post'))
		{
			$frmDt = date("Y-m-d", strtotime($values["fromdate"]));
			$toDt = date("Y-m-d", strtotime($values["todate"]));
			$resp = array();
			$select_args = array();
			$select_args[] = "employee.fullName as empname";
			$select_args[] = "empdueamount.amount as amount";
			$select_args[] = "empdueamount.paymentDate as paymentDate";
			$select_args[] = "officebranch.name as branch";
			$select_args[] = "empdueamount.comments as remarks";
			$select_args[] = "empdueamount.id as id";
			$totaladvances = 0;
			$totalreturns  = 0;
			$mons = array(1 => "JANUARY", 2 => "FEBRUARY", 3 => "MARCH", 4 => "APRIL", 5 => "MAY", 6 => "JUNE", 7 => "JULY", 8 => "AUGUST", 9 => "SEPTEMBER", 10 => "OCTOBER", 11 => "NOVEMBER", 12 => "DECEMBER");
			if(true){
				if($values["loan"] == "0"){
					$sql = 'SELECT date, financecompanies.name, loans.vehicleId, loans.agmtDate, loans.installmentAmount, loans.totalInstallments, loans.paidInstallments, loans.paymentType, loans.bankAccountId, concat(bankdetails.bankName," - ",bankdetails.accountNo) as bankName,loans.loanNo, loans.id as loanId, expensetransactions.remarks as remarks FROM `expensetransactions` join loans on loans.id=expensetransactions.entityValue join financecompanies on financecompanies.id=loans.financeCompanyId join bankdetails on bankdetails.id=loans.bankAccountId where entity="LOAN PAYMENT" and date between "'.$frmDt.'" and "'.$toDt.'" order by date;';
				}
				else if($values["loan"] > 0){
					$sql = 'SELECT date, financecompanies.name, loans.vehicleId, loans.agmtDate, loans.installmentAmount, loans.totalInstallments, loans.paidInstallments, loans.paymentType, loans.bankAccountId, concat(bankdetails.bankName," - ",bankdetails.accountNo) as bankName,loans.loanNo, loans.id as loanId, expensetransactions.remarks as remarks FROM `expensetransactions` join loans on loans.id=expensetransactions.entityValue join financecompanies on financecompanies.id=loans.financeCompanyId join bankdetails on bankdetails.id=loans.bankAccountId where loans.id='.$values["loan"].' and entity="LOAN PAYMENT" and date between "'.$frmDt.'" and "'.$toDt.'" order by date;';
				}
				$recs = DB::select( DB::raw($sql));
				foreach ($recs as $rec){
					$row = array();
					$row["date"] = date("d-m-Y",strtotime($rec->date));
					$row["fincompany"] = $rec->name;
					
					$veh_arr = explode(",", $rec->vehicleId);
					$vehs = \Vehicle::whereIn("id",$veh_arr)->get();
					$veh_arr = "";
					foreach ($vehs as $veh){
						$veh_arr = $veh_arr.$veh->veh_reg.", ";
					}
					$row["vehicles"] = $veh_arr;
					
					$agmtDate = $rec->agmtDate;
					$month = date("m", strtotime($agmtDate));
					$month_name = $mons[intval($month)];
					$year = date("Y", strtotime($agmtDate));
					$endDate = date('Y-m-d', strtotime("+$rec->totalInstallments months", strtotime($agmtDate)));						
					$endmonth = date("m", strtotime($endDate));
					$endmonth_name = $mons[intval($endmonth)];
					$endyear = date("Y", strtotime($endDate));
					$row["emiperiod"] = $month_name.", ".$year." - ".$endmonth_name.", ".$endyear;
					$row["emiamt"] = sprintf('%0.2f', $rec->installmentAmount);
					
					$sql = 'select count(*) as cnt from expensetransactions where entity="LoAN PAYMENT" and entityValue='.$rec->loanId.' and date BETWEEN "'.$rec->agmtDate.'" and "'.$rec->date.'";';
					$rec1 = DB::select( DB::raw($sql));
					$rec1 = $rec1[0];
					$row["paidemis"] = ($rec->paidInstallments+$rec1->cnt)."/".$rec->totalInstallments;
					
					$row["paymenttype"] = $rec->paymentType;
					$row["bankdetails"] = $rec->bankName;
					$row["loanno"] = $rec->loanNo;
					$row["remarks"] = $rec->remarks;
					$resp[] = $row;
				}
			}
			else if($values["employee"] > 0){
				$salaryadvances =  \EmpDueAmount::where("empdueamount.empId","=",$values["employee"])
				->where(function($query){$query->where("empdueamount.status","=","ACTIVE")->orWhere("empdueamount.deleted","=","No");})->join("employee","employee.id","=","empdueamount.empId")->leftjoin("officebranch","officebranch.id","=","empdueamount.branchId")->OrderBy("paymentDate")->select($select_args)->get();
				foreach ($salaryadvances as $salaryadvance){
					$row = array();
					$row["empname"] = $salaryadvance->empname;
					if($salaryadvance->amount>0){
						$totaladvances = $totaladvances+$salaryadvance->amount;
						$row["amount"] = "<span style='color:green'> ".$salaryadvance->amount."</span>";
					}
					else{
						$totalreturns = $totaladvances+$totalreturns;
						$row["amount"] = "<span style='color:red'> ".$salaryadvance->amount."</span>";
					}
					$row["paymentDate"] = date("d-m-Y",strtotime($salaryadvance->paymentDate));
					$row["branch"] = $salaryadvance->branch;
					$row["remarks"] = $salaryadvance->remarks;
					$row["id"] = $salaryadvance->id;
					$resp[] = $row;
				}
			}
			echo json_encode($resp);
			return;
		}
	
		$values['bredcum'] = strtoupper($values["reporttype"]);
		$values['home_url'] = 'masters';
		$values['add_url'] = 'getreport';
		$values['form_action'] = 'getreport';
		$values['action_val'] = '';
		$theads = array('Bank Name','Branch Name', "Account Name", "Account No", "Account Type");
		$values["theads"] = $theads;
	
		$form_info = array();
		$form_info["name"] = "getreport";
		$form_info["action"] = "getreport";
		$form_info["method"] = "post";
		$form_info["class"] = "form-horizontal";
		$form_info["back_url"] = "bankdetails";
		$form_info["bredcum"] = "add bank details";
		$form_info["reporttype"] = $values["reporttype"];
	
		$form_fields = array();
		$select_args = array();
		$select_args[] = "fuelstationdetails.id as id";
		$select_args[] = "fuelstationdetails.name as fname";
		$select_args[] = "cities.name as cname";
	
		$loans =  \Loan::ALL();
		$loans_arr = array();
		$loans_arr["0"] = "ALL LOANS";
		foreach ($loans as $loan){
			$vehs = "";
			if($loan->vehicleId != ""){
				$veh_arr = explode(",", $loan->vehicleId);
				$vehicles = \Vehicle::whereIn("id",$veh_arr)->get();
				$i = 0;
				for($i=0; $i<count($vehicles); $i++){
					if($i+1 == count($vehicles)){
						$vehs = $vehs.$vehicles[$i]->veh_reg;
					}
					else{
						$vehs = $vehs.$vehicles[$i]->veh_reg.", ";
					}
				}
			}
			$loans_arr[$loan->id] = $loan->loanNo." (".$vehs.")";
		}
		$form_field = array("name"=>"daterange", "content"=>"date range", "readonly"=>"",  "required"=>"required","type"=>"daterange", "class"=>"form-control");
		$form_fields[] = $form_field;
		$form_field = array("name"=>"loan", "content"=>"loan no", "readonly"=>"",  "required"=>"required","type"=>"select",  "options"=>$loans_arr, "class"=>"form-control chosen-select");
		$form_fields[] = $form_field;
		$form_field = array("name"=>"reporttype", "value"=>$values["reporttype"], "content"=>"", "readonly"=>"",  "required"=>"required","type"=>"hidden");
		$form_fields[] = $form_field;
	
		$form_info["form_fields"] = $form_fields;
		$values["form_info"] = $form_info;
		$values["provider"] = "bankdetails";
		return View::make('reports.loansreport', array("values"=>$values));
	}
	
	private function getDailyFinanceReport($values){
		if (\Request::isMethod('post'))
		{
			$frmDt = date("Y-m-d", strtotime($values["date"]));
			$resp = array();
			$select_args = array();
			$select_args[] = "employee.fullName as empname";
			$select_args[] = "empdueamount.amount as amount";
			$select_args[] = "empdueamount.paymentDate as paymentDate";
			$select_args[] = "officebranch.name as branch";
			$select_args[] = "empdueamount.comments as remarks";
			$select_args[] = "empdueamount.id as id";
			$totaladvances = 0;
			$totalreturns  = 0;
			if(true){
				$sql = 'SELECT date, expensetransactions.amount, financecompanies.name, loans.amountFinanced, loans.vehicleId, loans.agmtDate, loans.installmentAmount, loans.totalInstallments, loans.paidInstallments, loans.paymentType, loans.bankAccountId, concat(bankdetails.bankName," - ",bankdetails.accountNo) as bankName,loans.loanNo, loans.id as loanId, expensetransactions.remarks as remarks FROM `expensetransactions` join loans on loans.id=expensetransactions.entityValue join financecompanies on financecompanies.id=loans.financeCompanyId join bankdetails on bankdetails.id=loans.bankAccountId where entity="LOAN PAYMENT" and date between "'.$frmDt.'" and "'.$frmDt.'" order by date;';
				$recs = DB::select( DB::raw($sql));
				foreach ($recs as $rec){
					$row = array();
					$Date = $rec->agmtDate;
					$startDate = strtotime(date("Y-m-d", strtotime($Date)) . " +".$rec->paidInstallments." day");
					$startDate = date ( 'Y-m-d' , $startDate );
					$endDate = strtotime(date("Y-m-d", strtotime($Date)) . " +".$rec->totalInstallments." day");
					$endDate = date ( 'Y-m-d' , $endDate );

					$sql = 'select sum(amount) as amt from expensetransactions where entity="LoAN PAYMENT" and status="ACTIVE" and entityValue='.$rec->loanId.' and date BETWEEN "'.$rec->agmtDate.'" and "'.$frmDt.'";';
					$rec1 = DB::select( DB::raw($sql));
					$rec1 = $rec1[0];
					$totalamnt = $rec1->amt;
					$amount = $rec->installmentAmount;
					$todayPaidAmnt = $rec->amount;
					$start = strtotime($startDate);
					$end = strtotime($frmDt);
					$datediff = $end - $start;
					$days = floor($datediff/(60*60*24));

					$paidStuff = (int)($totalamnt/$amount);
					$remstuff = $totalamnt%$amount;
						
					$currentDay = $days+$rec->paidInstallments;
					$totalTobePaid = $currentDay*$amount;
					$suspenseAmount = ($days*$amount) - $totalamnt;
					$total_installments = $rec->totalInstallments;
					
					if(true){ //$currentDay <= $total_installments
						$row["fincompany"] = $rec->name;
						$row["loanamt"] = sprintf('%0.2f', $rec->amountFinanced);
						$row["startdt"] = date("d-m-Y",strtotime($startDate));
						$row["enddt"] = date("d-m-Y",strtotime($endDate));
						$row["suspense"] = "<font color='red'><b>".sprintf('%0.2f', $suspenseAmount)."</b></font>";
						$row["paidemis"] = ($days+1+$rec->paidInstallments)." Day";
						$row["todaypayment"] = sprintf('%0.2f',$todayPaidAmnt);
						$row["todaysuspense"] = sprintf('%0.2f',$todayPaidAmnt-$amount);
					}
					
					$resp[] = $row;
				}
			}
			echo json_encode($resp);
			return;
		}
	
		$values['bredcum'] = strtoupper($values["reporttype"]);
		$values['home_url'] = 'masters';
		$values['add_url'] = 'getreport';
		$values['form_action'] = 'getreport';
		$values['action_val'] = '';
		$theads = array('Bank Name','Branch Name', "Account Name", "Account No", "Account Type");
		$values["theads"] = $theads;
	
		$form_info = array();
		$form_info["name"] = "getreport";
		$form_info["action"] = "getreport";
		$form_info["method"] = "post";
		$form_info["class"] = "form-horizontal";
		$form_info["back_url"] = "bankdetails";
		$form_info["bredcum"] = "add bank details";
		$form_info["reporttype"] = $values["reporttype"];
	
		$form_fields = array();
		$select_args = array();
		$select_args[] = "fuelstationdetails.id as id";
		$select_args[] = "fuelstationdetails.name as fname";
		$select_args[] = "cities.name as cname";
	
		$loans =  \Loan::ALL();
		$loans_arr = array();
		$loans_arr["0"] = "ALL LOANS";
		foreach ($loans as $loan){
			$vehs = "";
			if($loan->vehicleId != ""){
				$veh_arr = explode(",", $loan->vehicleId);
				$vehicles = \Vehicle::whereIn("id",$veh_arr)->get();
				$i = 0;
				for($i=0; $i<count($vehicles); $i++){
					if($i+1 == count($vehicles)){
						$vehs = $vehs.$vehicles[$i]->veh_reg;
					}
					else{
						$vehs = $vehs.$vehicles[$i]->veh_reg.", ";
					}
				}
			}
			$loans_arr[$loan->id] = $loan->loanNo." (".$vehs.")";
		}
		$form_field = array("name"=>"date", "content"=>"date", "readonly"=>"",  "required"=>"required","type"=>"text", "class"=>"form-control date-picker");
		$form_fields[] = $form_field;
		$form_field = array("name"=>"reporttype", "value"=>$values["reporttype"], "content"=>"", "readonly"=>"",  "required"=>"required","type"=>"hidden");
		$form_fields[] = $form_field;
	
		$form_info["form_fields"] = $form_fields;
		$values["form_info"] = $form_info;
		$values["provider"] = "bankdetails";
		return View::make('reports.dailyfinancereport', array("values"=>$values));
	}
	
	private function getDailySettlementReport($values){
		if (\Request::isMethod('post'))
		{
			$dt = date("Y-m-d", strtotime($values["date"]));
			$brachId = $values["branch"];
			$resp = array();
			
			$booking_income = 0;
			$booking_cancel = 0;
			$corgos_simply_income = 0;
			$corgos_simply_cancel = 0;
			$other_income = 0;
			$total_expenses = 0;
			$bank_deposited = 0;
			$branch_deposited = 0;
			
			
			DB::statement(DB::raw("CALL daily_transactions_report('".$dt."', '".$dt."');"));
			$recs = DB::select( DB::raw("select * from temp_daily_transaction where branchId=".$brachId." order by branchId"));
			if(count($recs)>0) {
				$totalAmt = 0;
				foreach ($recs as $rec){
					$row = array();
					$brachName = "";
					if($rec->branchId>0){
						$brachName = \OfficeBranch::where("id","=",$rec->branchId)->first();
						$brachName = $brachName->name;
					}
					$row = array();
					$row["branch"] = $brachName;
					if($rec->type=="LOCAL"  || $rec->type == "DAILY"){
						$rec->type = $rec->type." TRIP";
					}
					$row["type"] = strtoupper($rec->type);
					$row["date"] = date("d-m-Y",strtotime($rec->date));
					$row["amount"] = $rec->amount;
					$row["purpose"] = strtoupper($rec->name);
					if($rec->lookupValueId==8888){
						$row["purpose"] = "CREDITED TO BRANCH - TRIP BALANCE";
					}
					if($rec->lookupValueId==9999){
						$row["purpose"] = "DEBITED FROM BRANCH - TRIP BALANCE";
					}
					else if($rec->lookupValueId==999){
						if($rec->entityValue>0){
							$prepaidName = \LookupTypeValues::where("id","=",$rec->entityValue)->first();
							$prepaidName = $prepaidName->name;
							$row["purpose"] = strtoupper($rec->entity);
							$row["employee"] = $prepaidName;
						}
						else{
							$row["purpose"] = strtoupper($rec->entity);
							$row["employee"] = "";
						}
					}
					else if($rec->lookupValueId==998){
						if($rec->entityValue>0){
							$creditsupplier = \CreditSupplier::where("id","=",$rec->entityValue)->first();
							$creditsupplier = $creditsupplier->supplierName;
							$row["purpose"] = strtoupper($rec->entity);
							$row["employee"] = $creditsupplier;
						}
						else{
							$row["purpose"] = strtoupper($rec->entity);
							$row["employee"] = "";
						}
					}
					else if($rec->lookupValueId==997){
						if($rec->entityValue>0){
							$fuelstation = \FuelStation::where("id","=",$rec->entityValue)->first();
							$fuelstation = $fuelstation->name;
							$row["purpose"] = strtoupper($rec->entity);
							$row["employee"] = $fuelstation;
						}
						else{
							$row["purpose"] = strtoupper($rec->entity);
							$row["employee"] = "";
						}
					}
					else if($rec->lookupValueId==991){
						if($rec->entityValue>0){
							$dfid = \DailyFinance::where("id","=",$rec->entityValue)->first();
							$dfid = $dfid->financeCompanyId;
							$finanacecompany = \FinanceCompany::where("id","=",$dfid)->first();
							$finanacecompany = $finanacecompany->name;
							$row["purpose"] = strtoupper($rec->entity);
							$row["employee"] = $finanacecompany;
						}
						else{
							$row["purpose"] = strtoupper($rec->entity);
							$row["employee"] = "";
						}
					}
					else if($rec->lookupValueId==73){
						$bankdetails = \IncomeTransaction::where("transactionId","=",$rec->rowid)->leftjoin("bankdetails","bankdetails.id","=","incometransactions.bankId")->first();
						$bankdetails = $bankdetails->bankName." - ".$bankdetails->accountNo;
						$row["employee"] = $bankdetails;
					}
					else if($rec->lookupValueId==84){
						$bankdetails = \ExpenseTransaction::where("transactionId","=",$rec->rowid)->leftjoin("bankdetails","bankdetails.id","=","expensetransactions.bankId")->first();
						$bankdetails = $bankdetails->bankName." - ".$bankdetails->accountNo;
						$row["employee"] = $bankdetails;
					}
					else if($rec->lookupValueId==63){
						$lookupvalue = \LookupTypeValues::where("id","=",$rec->lookupValueId)->first();
						$lookupvalue = $lookupvalue->name;
						$row["employee"] = "";
					}
					else{
						if($rec->entityValue != "0"){
							$row["purpose"] = strtoupper($rec->entity);
							$row["employee"] = $rec->lookupValueId." - ".$rec->entityValue;
						}
						else{
							$row["employee"] = $rec->entity;
						}
							
					}
					
					if($row["type"] == "LOCAL TRIP" || $row["type"]=="DAILY TRIP"){
						if($row["purpose"] == "VEHICLE ADVANCES"){
							$row["purpose"] = $row["purpose"]." (".$row["type"].")";
							$row["type"] = "EXPENSE";
						}
						if($row["purpose"] == "ADVANCE AMOUNT"){
							$row["purpose"] = $row["purpose"]." (".$row["type"].")";
							$row["type"] = "INCOME";
						}
						if($row["purpose"] == "CREDITED TO BRANCH - TRIP BALANCE"){
							$row["purpose"] = $row["purpose"]." (".$row["type"].")";
							$row["type"] = "INCOME";
						}
						if($row["purpose"] == "DEBITED FROM BRANCH - TRIP BALANCE"){
							$row["purpose"] = $row["purpose"]." (".$row["type"].")";
							$row["type"] = "EXPENSE";
						}
					}
					
					if($row["purpose"] == "TICKETS AMOUNT" ){
						$booking_income = $booking_income+$row["amount"];
					}
					else if($row["purpose"] == "TICKETS CANCEL AMOUNT" ){
						$booking_cancel = $booking_cancel+$row["amount"];
					}
					else if($row["purpose"] == "CARGO SIMPLY AMOUNT" ){
						$corgos_simply_income = $corgos_simply_income+$row["amount"];
					}
					else if($row["purpose"] == "CARGO SIMPLY CANCEL" ){
						$corgos_simply_cancel = $corgos_simply_cancel+$row["amount"];
					}
					else if($row["purpose"] == "BANK DEPOSITS" ){
						$bank_deposited = $bank_deposited+$row["amount"];
					}
					else if($row["type"] == "INCOME" ){
						$other_income = $other_income+$row["amount"];
					}
					else if($row["type"] == "EXPENSE" && $row["purpose"] == "BRANCH DEPOSIT" ){
						$branch_deposited = $branch_deposited+$row["amount"];
					}
					else if($row["type"] == "EXPENSE" ){
						$total_expenses = $total_expenses+$row["amount"];
					}
					
					$row["comments"] = $rec->remarks;
					$row["createdby"] = $rec->createdBy;
					$resp[] = $row;
				}
			}
			$booking_income = sprintf('%0.2f', $booking_income);
			$booking_cancel = sprintf('%0.2f', $booking_cancel);
			$corgos_simply_income = sprintf('%0.2f', $corgos_simply_income);
			$corgos_simply_cancel = sprintf('%0.2f', $corgos_simply_cancel);
			$other_income = sprintf('%0.2f', $other_income);
			$total_expenses = sprintf('%0.2f', $total_expenses);
			$bank_deposited = sprintf('%0.2f', $bank_deposited);
			$branch_deposited = sprintf('%0.2f', $branch_deposited);
				
			$cf_amt = 0;
			$cf_prev_amt = 0;
			$nextDay = strtotime(date("Y-m-d", strtotime($dt)) . " +1 day");
			$nextDay = date ('Y-m-d', $nextDay);
			$cf_details = \IncomeTransaction::where("branchId","=",$brachId)->where("date","=",$nextDay)->where("status","=","ACTIVE")->where("lookupValueId","=","194")->get();
			if(count($cf_details)>0){
				$cf_details = $cf_details[0];
				$cf_amt = $cf_details->amount;
			}
			$cf_details = \IncomeTransaction::where("branchId","=",$brachId)->where("date","=",date("Y-m-d",strtotime($dt)))->where("status","=","ACTIVE")->where("lookupValueId","=","194")->get();
			if(count($cf_details)>0){
				$cf_details = $cf_details[0];
				$cf_prev_amt = $cf_details->amount;
			}
			
			$resp_arr = array("data"=>$resp,"booking_income"=>$booking_income,"booking_cancel"=>$booking_cancel,"cargos_simply_income"=>$corgos_simply_income,
					"cargos_simply_cancel"=>$corgos_simply_cancel,"other_income"=>$other_income,"total_expense"=>$total_expenses,
					"branch_deposites"=>$branch_deposited,"bank_deposits"=>$bank_deposited,"cf_amt"=>$cf_amt,"cf_prev_amt"=>$cf_prev_amt
					);
			echo json_encode($resp_arr);
			return;
		}
	
		$values['bredcum'] = strtoupper($values["reporttype"]);
		$values['home_url'] = 'masters';
		$values['add_url'] = 'getreport';
		$values['form_action'] = 'getreport';
		$values['action_val'] = '';
		$theads = array('Bank Name','Branch Name', "Account Name", "Account No", "Account Type");
		$values["theads"] = $theads;
	
		$form_info = array();
		$form_info["name"] = "getreport";
		$form_info["action"] = "getreport";
		$form_info["method"] = "post";
		$form_info["class"] = "form-horizontal";
		$form_info["back_url"] = "bankdetails";
		$form_info["bredcum"] = "add bank details";
		$form_info["reporttype"] = $values["reporttype"];
	
		$form_fields = array();
		$select_args = array();
		$select_args[] = "fuelstationdetails.id as id";
		$select_args[] = "fuelstationdetails.name as fname";
		$select_args[] = "cities.name as cname";
	
		$branches =  \OfficeBranch::ALL();
		$branches_arr = array();
		foreach ($branches as $branch){
			$branches_arr[$branch->id] = $branch->name;
		}
		$form_field = array("name"=>"branch", "content"=>"branch ", "readonly"=>"",  "required"=>"required","type"=>"select",  "options"=>$branches_arr, "class"=>"form-control chosen-select");
		$form_fields[] = $form_field;
		$form_field = array("name"=>"date", "content"=>"date ", "readonly"=>"",  "required"=>"required", "type"=>"text", "class"=>"form-control date-picker");
		$form_fields[] = $form_field;
		$form_field = array("name"=>"reporttype", "value"=>$values["reporttype"], "content"=>"", "readonly"=>"",  "required"=>"required","type"=>"hidden");
		$form_fields[] = $form_field;
	
		$form_info["form_fields"] = $form_fields;
		$values["form_info"] = $form_info;
		$values["provider"] = "dailysettlement";
		return View::make('reports.dailysettlementreport', array("values"=>$values));
	}
	
	private function getDailySettlementReportsReport($values){
		if (\Request::isMethod('post'))
		{
			$frmDt = date("Y-m-d", strtotime($values["fromdate"]));
			$toDt = date("Y-m-d", strtotime($values["todate"]));
			$resp = array();
			$select_args = array();
			$select_args[] = "officebranch.name as branch";
			$select_args[] = "sum(actualSalary) as actualSalary";
			$select_args[] = "sum(dueDeductions) as dueDeductions";
			$select_args[] = "sum(leaveDeductions) as leaveDeductions";
			$select_args[] = "sum(pf) as pf";
			$select_args[] = "sum(esi) as esi";
			$select_args[] = "sum(salaryPaid) as salaryPaid";
			if(isset($values["branch"])){
				if($values["branch"] == "0"){
					$branchsuspenses = \BranchSuspenseReport::whereBetween("reportDate",array($frmDt,$toDt))->OrderBy("reportDate")->get() ;
					foreach ($branchsuspenses as $branchsuspense){
						$row = array();
						$branchname = \OfficeBranch::where("id","=",$branchsuspense->branchId)->get();
						if(count($branchname)>0){
							$branchname = $branchname[0];
							$branchname = $branchname->name;
						}
						else {
							$branchname = "";
						}
						$row["branchname"] = $branchname;
						$row["reportdate"] = date("d-m-Y",strtotime($branchsuspense->reportDate));
						$row["income"] = sprintf('%0.2f', $branchsuspense->total_income);
						$row["expense"] = sprintf('%0.2f', $branchsuspense->total_expense);
						$row["bankdeposit"] = sprintf('%0.2f', $branchsuspense->bank_deposit);
						$row["branchdeposit"] = sprintf('%0.2f', $branchsuspense->branch_deposit);
						$balanceWithoutCF = $branchsuspense->total_income-($branchsuspense->total_expense+$branchsuspense->bank_deposit+$branchsuspense->branch_deposit);
						$row["balance"] = sprintf('%0.2f', $balanceWithoutCF);
						
						$cf_amt = 0;
						$checkString = "";
						$col ="";
						$nextDay = strtotime(date("Y-m-d", strtotime($branchsuspense->reportDate)) . " +1 day");
						$nextDay = date ( 'Y-m-d' , $nextDay );
						$cf_details = \IncomeTransaction::where("branchId","=",$branchsuspense->branchId)->where("date","=",$nextDay)->where("status","=","ACTIVE")->where("lookupValueId","=","194")->get();
						if(count($cf_details)>0){
							$cf_details = $cf_details[0];
							$cf_amt = $cf_details->amount;
						}
						if($cf_amt>($branchsuspense->bank_deposit+$branchsuspense->branch_deposit)){
							$rem = round(($cf_amt-($branchsuspense->bank_deposit+$branchsuspense->branch_deposit)),2);
							$checkString = $rem." (LESS)";
							$col = "red";
						}
						else if($cf_amt<($branchsuspense->bank_deposit+$branchsuspense->branch_deposit)){
							$rem = round(($cf_amt-($branchsuspense->bank_deposit+$branchsuspense->branch_deposit)),2);
							$checkString = -1*$rem." (MORE)";
							$col = "green";
						}
						else {
							$checkString="DONE";
							$col="lightgrey";
						}
						
						$row["carryforward"] = sprintf('%0.2f', ($balanceWithoutCF-$cf_amt));
						$row["settlement"] = sprintf('%0.2f', $cf_amt);
						$row["status"] = "<span style='color:".$col.";font-weight:bold;'>".$checkString."</span>"; 
						$row["comments"] = $branchsuspense->comments;
						$date = date("d-m-Y",strtotime($branchsuspense->reportDate));
						$row["action"] = '<a href="report?reporttype=dailysettlement&branch='.$branchsuspense->branchId.'&date='.$date.'" target="_blank"><button class="btn btn-minier btn-primary">&nbsp;&nbsp;EDIT&nbsp;&nbsp;</button></a>';
						$resp[] = $row;
					}
				}
				else if($values["branch"]>0){
					$branchsuspenses = \BranchSuspenseReport::where("branchId","=",$values["branch"])->whereBetween("reportDate",array($frmDt,$toDt))->OrderBy("reportDate")->get() ;
					foreach ($branchsuspenses as $branchsuspense){
						$row = array();
						$branchname = \OfficeBranch::where("id","=",$branchsuspense->branchId)->get();
						if(count($branchname)>0){
							$branchname = $branchname[0];
							$branchname = $branchname->name;
						}
						else {
							$branchname = "";
						}
						$row["branchname"] = $branchname;
						$row["reportdate"] = date("d-m-Y",strtotime($branchsuspense->reportDate));
						$row["income"] = sprintf('%0.2f', $branchsuspense->total_income);
						$row["expense"] = sprintf('%0.2f', $branchsuspense->total_expense);
						$row["bankdeposit"] = sprintf('%0.2f', $branchsuspense->bank_deposit);
						$row["branchdeposit"] = sprintf('%0.2f', $branchsuspense->branch_deposit);
						$balanceWithoutCF = $branchsuspense->total_income-($branchsuspense->total_expense+$branchsuspense->bank_deposit+$branchsuspense->branch_deposit);
						$row["balance"] = sprintf('%0.2f', $balanceWithoutCF);
						
						$cf_amt = 0;
						$checkString = "";
						$col ="";
						$nextDay = strtotime(date("Y-m-d", strtotime($branchsuspense->reportDate)) . " +1 day");
						$nextDay = date ( 'Y-m-d' , $nextDay );
						$cf_details = \IncomeTransaction::where("branchId","=",$branchsuspense->branchId)->where("date","=",$nextDay)->where("status","=","ACTIVE")->where("lookupValueId","=","194")->get();
						if(count($cf_details)>0){
							$cf_details = $cf_details[0];
							$cf_amt = $cf_details->amount;
						}
						if($cf_amt>($branchsuspense->bank_deposit+$branchsuspense->branch_deposit)){
							$rem = round(($cf_amt-($branchsuspense->bank_deposit+$branchsuspense->branch_deposit)),2);
							$checkString = $rem." (LESS)";
							$col = "red";
						}
						else if($cf_amt<($branchsuspense->bank_deposit+$branchsuspense->branch_deposit)){
							$rem = round(($cf_amt-($branchsuspense->bank_deposit+$branchsuspense->branch_deposit)),2);
							$checkString = -1*$rem." (MORE)";
							$col = "green";
						}
						else {
							$checkString="DONE";
							$col="lightgrey";
						}
						
						$row["carryforward"] = sprintf('%0.2f', ($balanceWithoutCF-$cf_amt));
						$row["settlement"] = sprintf('%0.2f', $cf_amt);
						$row["status"] = "<span style='color:".$col.";font-weight:bold;'>".$checkString."</span>"; 
						$row["comments"] = $branchsuspense->comments;
						$date = date("d-m-Y",strtotime($branchsuspense->reportDate));
						$row["action"] = '<a href="report?reporttype=dailysettlement&branch='.$branchsuspense->branchId.'&date='.$date.'" target="_blank"><button class="btn btn-minier btn-primary">&nbsp;&nbsp;EDIT&nbsp;&nbsp;</button></a>';
						$resp[] = $row;
					}
				}
			}
			echo json_encode($resp);
			return;
		}
	
		$values['bredcum'] = strtoupper($values["reporttype"]);
		$values['home_url'] = 'masters';
		$values['add_url'] = 'getreport';
		$values['form_action'] = 'getreport';
		$values['action_val'] = '';
		$theads = array('Bank Name','Branch Name', "Account Name", "Account No", "Account Type");
		$values["theads"] = $theads;
	
		$form_info = array();
		$form_info["name"] = "getreport";
		$form_info["action"] = "getreport";
		$form_info["method"] = "post";
		$form_info["class"] = "form-horizontal";
		$form_info["back_url"] = "bankdetails";
		$form_info["bredcum"] = "add bank details";
		$form_info["reporttype"] = $values["reporttype"];
	
		$form_fields = array();
		$select_args = array();
		$select_args[] = "fuelstationdetails.id as id";
		$select_args[] = "fuelstationdetails.name as fname";
		$select_args[] = "cities.name as cname";
	
		$branches =  \OfficeBranch::ALL();
		$branches_arr = array();
		$branches_arr["0"] = "ALL BRANCHES";
		foreach ($branches as $branch){
			$branches_arr[$branch->id] = $branch->name;
		}
		$form_field = array("name"=>"branch", "content"=>"branch ", "readonly"=>"",  "required"=>"required","type"=>"select",  "options"=>$branches_arr, "class"=>"form-control chosen-select");
		$form_fields[] = $form_field;
		$form_field = array("name"=>"daterange", "content"=>"date range", "readonly"=>"",  "required"=>"required","type"=>"daterange", "class"=>"form-control");
		$form_fields[] = $form_field;
		$form_field = array("name"=>"reporttype", "value"=>$values["reporttype"], "content"=>"", "readonly"=>"",  "required"=>"required","type"=>"hidden");
		$form_fields[] = $form_field;
	
		$form_info["form_fields"] = $form_fields;
		$values["form_info"] = $form_info;
		$values["provider"] = "dailysettlement";
		return View::make('reports.dailysettlementreportsreport', array("values"=>$values));
	}
	
	private function getInchargeTransactionsReport($values){
		if (\Request::isMethod('post'))
		{
			$frmDt = date("Y-m-d", strtotime($values["fromdate"]));
			$toDt = date("Y-m-d", strtotime($values["todate"]));
			$resp = array();
			$resp2 = array();
			$totexpenses = 0;
			$select_args = array();
			$select_args[] = "officebranch.name as branch";
			$select_args[] = "incometransactions.amount as amount";
			$select_args[] = "incometransactions.date as date";
			$select_args[] = "incometransactions.remarks as remarks";
			$select_args[] = "employee.fullName as name";
			if(isset($values["incharge"])){
				$select_args = array();
				$select_args[] = "officebranch.name as branch";
				$select_args[] = "incometransactions.amount as amount";
				$select_args[] = "incometransactions.date as date";
				$select_args[] = "incometransactions.remarks as remarks";
				$select_args[] = "employee.fullName as name";
				$inchargetransactions = \IncomeTransaction::leftjoin("officebranch","officebranch.id","=","incometransactions.branchId")->leftjoin("employee","employee.id","=","incometransactions.createdBy")->where("inchargeId","=",$values["incharge"])->where("lookupValueId","=",161)->whereBetween("date",array($frmDt,$toDt))->OrderBy("date")->select($select_args)->get() ;
				foreach ($inchargetransactions as $inchargetransaction){
					$row = array();
					$row["branch"] = $inchargetransaction->branch;
					$row["type"] =  "<span style='color:green;'>Debited from Incharge Account</span>";
					$row["amount"] = $inchargetransaction->amount;
					$row["date"] = date("d-m-Y",strtotime($inchargetransaction->date));
					$row["remarks"] = $inchargetransaction->remarks;
					$row["name"] = $inchargetransaction->name;
					$resp[] = $row;
				}
				
				$select_args = array();
				$select_args[] = "officebranch.name as branch";
				$select_args[] = "expensetransactions.amount as amount";
				$select_args[] = "expensetransactions.date as date";
				$select_args[] = "expensetransactions.remarks as remarks";
				$select_args[] = "employee.fullName as name";
				$inchargetransactions = \ExpenseTransaction::leftjoin("officebranch","officebranch.id","=","expensetransactions.branchId")->leftjoin("employee","employee.id","=","expensetransactions.createdBy")->where("inchargeId","=",$values["incharge"])->where("lookupValueId","=",195)->whereBetween("date",array($frmDt,$toDt))->OrderBy("date")->select($select_args)->get() ;
				foreach ($inchargetransactions as $inchargetransaction){
					$row = array();
					$row["branch"] = $inchargetransaction->branch;
					$row["type"] =  "<span style='color:red;'>Credited into Incharge Account</span>";
					$row["amount"] = $inchargetransaction->amount;
					$row["date"] = date("d-m-Y",strtotime($inchargetransaction->date));
					$row["remarks"] = $inchargetransaction->remarks;
					$row["name"] = $inchargetransaction->name;
					$resp[] = $row;
				}
				
				DB::statement(DB::raw("CALL incharge_transaction_report('".$frmDt."', '".$toDt."');"));
				$recs = DB::select( DB::raw("SELECT *,temp_incharge_transaction.name as purpose, temp_incharge_transaction.createdBy as createdBy, officebranch.name as branchname FROM `temp_incharge_transaction` left join officebranch on officebranch.id=temp_incharge_transaction.branchId where inchargeId='".$values["incharge"]."'"));
				foreach ($recs as $rec){
					$row = array();
					$row["branch"] = $rec->branchname;
					$row["date"] = date("d-m-Y",strtotime($rec->date));
					$row["amount"] =  $rec->amount;
					$totexpenses = $totexpenses+$rec->amount;
					$row["purpose"] =  $rec->purpose;
					$row["paidto"] =  $rec->entityValue;//$rec->type." - ".$rec->tripId." - ".$rec->entityValue;
					$vehreg = "";
					if($rec->type == "LOCAL"){
						$row["purpose"] = "LOCAL TRIP ADVANCE : <br/>";
						$entities = \BusBookings::where("id","=",$rec->tripId)->get();
						foreach ($entities as $entity){
							$entity["sourcetrip"] = $entity["source_start_place"]."<br/> ".$entity["source_end_place"];
							$entity["sourcetrip"] = $entity["sourcetrip"]."<br/>Date & Time &nbsp;: ".$entity["source_date"]." ".$entity["source_time"];
							$row["purpose"] = $row["purpose"].$entity->sourcetrip;
						}
						if($rec->entityValue>0){
							$vehicle = \Vehicle::where("id","=",$rec->entityValue)->get();
							if(count($vehicle)>0){
								$vehicle = $vehicle[0];
								$vehreg = $vehicle->veh_reg;
							}
						}
						$row["paidto"] = $vehreg;
					}
					if($rec->type == "DAILY"){
						$select_args = array();
						$select_args[] = "vehicle.veh_reg as vehicleId";
						$select_args[] = "tripdetails.tripStartDate as tripStartDate";
						$select_args[] = "tripdetails.id as routeInfo";
						$select_args[] = "tripdetails.tripCloseDate as tripCloseDate";
						$select_args[] = "tripdetails.routeCount as routes";
						$select_args[] = "tripdetails.id as id";
						$routeInfo = "";
						$entities = \TripDetails::where("tripdetails.id","=",$rec->tripId)->leftjoin("vehicle", "vehicle.id","=","tripdetails.vehicleId")->select($select_args)->get();
						foreach ($entities as $entity){
							$entity["tripStartDate"] = date("d-m-Y",strtotime($entity["tripStartDate"]));
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
							}
							$row["purpose"] = "DAILY TRIP ADVANCE : <br/>";
							$row["purpose"] = $row["purpose"].$routeInfo;
							$row["paidto"] = $entity->vehicleId;
						}
					}
					if($row["paidto"] == "0"){
						$row["paidto"] = "";
					}
					$row["remarks"] =$rec->remarks;
					$row["name"] = $rec->createdBy;
					$resp2[] = $row;
				}
			}
			$resp_json = array("data1"=>$resp,"data2"=>$resp2,"total_expenses"=>$totexpenses);
			echo json_encode($resp_json);
			return;
		}
	
		$values['bredcum'] = strtoupper($values["reporttype"]);
		$values['home_url'] = 'masters';
		$values['add_url'] = 'getreport';
		$values['form_action'] = 'getreport';
		$values['action_val'] = '';
		$theads = array('Bank Name','Branch Name', "Account Name", "Account No", "Account Type");
		$values["theads"] = $theads;
	
		$form_info = array();
		$form_info["name"] = "getreport";
		$form_info["action"] = "getreport";
		$form_info["method"] = "post";
		$form_info["class"] = "form-horizontal";
		$form_info["back_url"] = "bankdetails";
		$form_info["bredcum"] = "add bank details";
		$form_info["reporttype"] = $values["reporttype"];
	
		$form_fields = array();
		
		$select_args = array();
		$select_args[] = "inchargeaccounts.id as id";
		$select_args[] = "employee.fullName as fullName";	
		$incharges =  \InchargeAccounts::join("employee","employee.id","=","inchargeaccounts.empId")->select($select_args)->get();
		$incharges_arr = array();
		foreach ($incharges as $incharge){
			$incharges_arr[$incharge->id] = $incharge->fullName;
		}
		$form_field = array("name"=>"incharge", "content"=>"incharge ", "readonly"=>"",  "required"=>"required","type"=>"select",  "options"=>$incharges_arr, "class"=>"form-control chosen-select");
		$form_fields[] = $form_field;
		$form_field = array("name"=>"daterange", "content"=>"date range", "readonly"=>"",  "required"=>"required","type"=>"daterange", "class"=>"form-control");
		$form_fields[] = $form_field;
		$form_field = array("name"=>"reporttype", "value"=>$values["reporttype"], "content"=>"", "readonly"=>"",  "required"=>"required","type"=>"hidden");
		$form_fields[] = $form_field;
	
		$form_info["form_fields"] = $form_fields;
		$values["form_info"] = $form_info;
		$values["provider"] = "dailysettlement";
		return View::make('reports.inchargetransactionsreport', array("values"=>$values));
	}
	
	private function getSalaryReport($values){
		if (\Request::isMethod('post'))
		{
			$frmDt = date("Y-m-d", strtotime($values["fromdate"]));
			$toDt = date("Y-m-d", strtotime($values["todate"]));
			$resp = array();
			$select_args = array();
			$select_args[] = "officebranch.name as branch";
			$select_args[] = "sum(actualSalary) as actualSalary";
			$select_args[] = "sum(dueDeductions) as dueDeductions";
			$select_args[] = "sum(leaveDeductions) as leaveDeductions";
			$select_args[] = "sum(pf) as pf";
			$select_args[] = "sum(esi) as esi";
			$select_args[] = "sum(salaryPaid) as salaryPaid";
			$totalactsalary = 0;
			$totalduedeductions  = 0;
			$totalleavedeductions  = 0;
			$totalpf  = 0;
			$totalesi = 0;
			$totalsalarypaid = 0;
			
			if(isset($values["typeofreport"]) && $values["typeofreport"]=="branch_wise_salary_report"){
				if($values["paidfrombranch"] == "0"){
					$sql = "SELECT officebranch.name as branch, sum(actualSalary) as actualSalary, sum(dueDeductions) as dueDeductions, sum(leaveDeductions) as leaveDeductions, sum(pf) as pf, sum(esi) as esi, sum(salaryPaid) as salaryPaid from empsalarytransactions left join officebranch on officebranch.id=empsalarytransactions.branchId where paymentDate BETWEEN '".$frmDt."' and '".$toDt."' group by branchId";
					$salarytransactions =  \DB::select(DB::raw($sql));
					foreach ($salarytransactions as $salarytransaction){
						$row = array();
						$row["branch"] = $salarytransaction->branch;
						$row["actualSalary"] = $salarytransaction->actualSalary;
						$row["dueDeductions"] = $salarytransaction->dueDeductions;
						$row["leaveDeductions"] = $salarytransaction->leaveDeductions;
						$row["pf"] = $salarytransaction->pf;
						$row["esi"] = $salarytransaction->esi;
						$row["salaryPaid"] = $salarytransaction->salaryPaid;
						$resp[] = $row;
						$totalactsalary = $totalactsalary+$salarytransaction->actualSalary;
						$totalduedeductions = $totalduedeductions+$salarytransaction->dueDeductions;
						$totalleavedeductions = $totalleavedeductions+$salarytransaction->leaveDeductions;
						$totalpf = $totalpf+$salarytransaction->pf;
						$totalesi = $totalesi+$salarytransaction->esi;
						$totalsalarypaid = $totalsalarypaid+$salarytransaction->salaryPaid;
					}
				}
				else if($values["paidfrombranch"]>0){
					$sql = "SELECT officebranch.name as branch, sum(actualSalary) as actualSalary, sum(dueDeductions) as dueDeductions, sum(leaveDeductions) as leaveDeductions, sum(pf) as pf, sum(esi) as esi, sum(salaryPaid) as salaryPaid from empsalarytransactions left join officebranch on officebranch.id=empsalarytransactions.branchId where branchId= ".$values["paidfrombranch"]." and paymentDate BETWEEN '".$frmDt."' and '".$toDt."' group by branchId";
					$salarytransactions =  \DB::select(DB::raw($sql));
					foreach ($salarytransactions as $salarytransaction){
						$row = array();
						$row["branch"] = $salarytransaction->branch;
						$row["actualSalary"] = $salarytransaction->actualSalary;
						$row["dueDeductions"] = $salarytransaction->dueDeductions;
						$row["leaveDeductions"] = $salarytransaction->leaveDeductions;
						$row["pf"] = $salarytransaction->pf;
						$row["esi"] = $salarytransaction->esi;
						$row["salaryPaid"] = $salarytransaction->salaryPaid;
						$resp[] = $row;
						$totalactsalary = $totalactsalary+$salarytransaction->actualSalary;
						$totalduedeductions = $totalduedeductions+$salarytransaction->dueDeductions;
						$totalleavedeductions = $totalleavedeductions+$salarytransaction->leaveDeductions;
						$totalpf = $totalpf+$salarytransaction->pf;
						$totalesi = $totalesi+$salarytransaction->esi;
						$totalsalarypaid = $totalsalarypaid+$salarytransaction->salaryPaid;
					}
				}
			}
			if(isset($values["typeofreport"]) && $values["typeofreport"]=="bank_payment_report"){
				if($values["paidfrombranch"] == "0"){
					$sql = "SELECT officebranch.name as branch, employee.empCode as empId, employee.fullName as name, salaryMonth, paymentDate, (actualSalary) as actualSalary, (dueDeductions) as dueDeductions, (leaveDeductions) as leaveDeductions, (pf) as pf, (esi) as esi, (salaryPaid) as salaryPaid, empsalarytransactions.paymentType, empsalarytransactions.bankAccount, empsalarytransactions.accountNumber, empsalarytransactions.chequeNumber, empsalarytransactions.bankName, empsalarytransactions.accountNumber, empsalarytransactions.issueDate, empsalarytransactions.transactionDate from empsalarytransactions join employee on employee.id=empsalarytransactions.empId left join officebranch on officebranch.id=empsalarytransactions.branchId where paymentType != 'cash' and paymentDate BETWEEN '".$frmDt."' and '".$toDt."' order by branchId";
				}
				else if($values["paidfrombranch"]>0){
					$sql = "SELECT officebranch.name as branch, employee.empCode as empId, employee.fullName as name, salaryMonth, paymentDate, (actualSalary) as actualSalary, (dueDeductions) as dueDeductions, (leaveDeductions) as leaveDeductions, (pf) as pf, (esi) as esi, (salaryPaid) as salaryPaid, empsalarytransactions.paymentType, empsalarytransactions.bankAccount, empsalarytransactions.accountNumber, empsalarytransactions.chequeNumber, empsalarytransactions.bankName, empsalarytransactions.accountNumber, empsalarytransactions.issueDate, empsalarytransactions.transactionDate from empsalarytransactions join employee on employee.id=empsalarytransactions.empId left join officebranch on officebranch.id=empsalarytransactions.branchId where branchId=".$values["paidfrombranch"]." and paymentType != 'cash' and paymentDate BETWEEN '".$frmDt."' and '".$toDt."' order by branchId";
				}
				$salarytransactions =  \DB::select(DB::raw($sql));
				foreach ($salarytransactions as $salarytransaction){
					$row = array();
					$row["branch"] = $salarytransaction->branch;
					$row["name"] = $salarytransaction->name."-".$salarytransaction->empId;
					$row["salaryMonth"] = date("M",strtotime($salarytransaction->salaryMonth)).", ".date("Y",strtotime($salarytransaction->salaryMonth));
					$row["paymentDate"] = date("d-m-Y",strtotime($salarytransaction->paymentDate));
					$row["paymentInfo"] = "";
					if($salarytransaction->paymentType == "neft"){
						$row["paymentInfo"] = "Payment type : NEFT<br/>";
						$row["paymentInfo"] = $row["paymentInfo"]."Bank Name : ".$salarytransaction->bankName."<br/>";
						$row["paymentInfo"] = $row["paymentInfo"]."Acct No. : ".$salarytransaction->accountNumber."<br/>";
					}
					else if($salarytransaction->paymentType == "ecs"){
						$row["paymentInfo"] = "Payment type : ESC<br/>";
						$row["paymentInfo"] = $row["paymentInfo"]."Bank Name : ".$salarytransaction->bankName."<br/>";
						$row["paymentInfo"] = $row["paymentInfo"]."Acct No. : ".$salarytransaction->accountNumber."<br/>";
					}
					else if($salarytransaction->paymentType == "rtgs"){
						$row["paymentInfo"] = "Payment type : RTGS<br/>";
						$row["paymentInfo"] = $row["paymentInfo"]."Bank Name : ".$salarytransaction->bankName."<br/>";
						$row["paymentInfo"] = $row["paymentInfo"]."Acct No. : ".$salarytransaction->accountNumber."<br/>";
					}
					else if($salarytransaction->paymentType == "cheque_debit"){
						$row["paymentInfo"] = "Payment type : CHEQUE DEBIT<br/>";
						$bankinfo = "";
						$bank = \BankDetails::where("id","=",$salarytransaction->bankAccount)->get();
						if(count($bank)>0){
							$bank = $bank[0];
							$bankinfo = $bankinfo."Bank Name : ".$bank->bankName." - ".$bank->accountNo."(".$bank->branchName.")<br/>";
						}
						$row["paymentInfo"] = $row["paymentInfo"].$bankinfo;
						$row["paymentInfo"] = $row["paymentInfo"]."Cheque No. : ".$salarytransaction->chequeNumber."<br/>";
					}
					$row["actualSalary"] = $salarytransaction->actualSalary;
					$row["dueDeductions"] = $salarytransaction->dueDeductions;
					$row["leaveDeductions"] = $salarytransaction->leaveDeductions;
					$row["pf"] = $salarytransaction->pf;
					$row["esi"] = $salarytransaction->esi;
					$row["salaryPaid"] = $salarytransaction->salaryPaid;
					$resp[] = $row;
				}
			}
			if(isset($values["typeofreport"]) && $values["typeofreport"]=="employee_wise_salary_report"){
				if($values["employee"] == "0"){
					$sql = "SELECT officebranch.name as branch, employee.empCode as empId, employee.fullName as name, salaryMonth, paymentDate, (actualSalary) as actualSalary, (dueDeductions) as dueDeductions, (leaveDeductions) as leaveDeductions, (pf) as pf, (esi) as esi, (salaryPaid) as salaryPaid from empsalarytransactions join employee on employee.id=empsalarytransactions.empId left join officebranch on officebranch.id=empsalarytransactions.branchId where paymentDate BETWEEN '".$frmDt."' and '".$toDt."' order by branchId";
					$salarytransactions =  \DB::select(DB::raw($sql));
					foreach ($salarytransactions as $salarytransaction){
						$row = array();
						$row["branch"] = $salarytransaction->branch;
						$row["empId"] = $salarytransaction->empId;
						$row["name"] = $salarytransaction->name;
						$row["salaryMonth"] = date("M",strtotime($salarytransaction->salaryMonth)).", ".date("Y",strtotime($salarytransaction->salaryMonth));
						$row["paymentDate"] = date("d-m-Y",strtotime($salarytransaction->paymentDate));
						$row["actualSalary"] = $salarytransaction->actualSalary;
						$row["dueDeductions"] = $salarytransaction->dueDeductions;
						$row["leaveDeductions"] = $salarytransaction->leaveDeductions;
						$row["pf"] = $salarytransaction->pf;
						$row["esi"] = $salarytransaction->esi;
						$row["salaryPaid"] = $salarytransaction->salaryPaid;
						$resp[] = $row;
					}
				}
				else if($values["employee"]>0){
					$sql = "SELECT officebranch.name as branch, sum(actualSalary) as actualSalary, sum(dueDeductions) as dueDeductions, sum(leaveDeductions) as leaveDeductions, sum(pf) as pf, sum(esi) as esi, sum(salaryPaid) as salaryPaid from empsalarytransactions left join officebranch on officebranch.id=empsalarytransactions.branchId where empId= ".$values["employee"]." and paymentDate BETWEEN '".$frmDt."' and '".$toDt."'";
					$salarytransactions =  \DB::select(DB::raw($sql));
					foreach ($salarytransactions as $salarytransaction){
						$row = array();
						$row["branch"] = $salarytransaction->branch;
						$row["actualSalary"] = $salarytransaction->actualSalary;
						$row["dueDeductions"] = $salarytransaction->dueDeductions;
						$row["leaveDeductions"] = $salarytransaction->leaveDeductions;
						$row["pf"] = $salarytransaction->pf;
						$row["esi"] = $salarytransaction->esi;
						$row["salaryPaid"] = $salarytransaction->salaryPaid;
						$resp[] = $row;
						$totalactsalary = $totalactsalary+$salarytransaction->actualSalary;
						$totalduedeductions = $totalduedeductions+$salarytransaction->dueDeductions;
						$totalleavedeductions = $totalleavedeductions+$salarytransaction->leaveDeductions;
						$totalpf = $totalpf+$salarytransaction->pf;
						$totalesi = $totalesi+$salarytransaction->esi;
						$totalsalarypaid = $totalsalarypaid+$salarytransaction->salaryPaid;
					}
				}
			}
			if(isset($values["typeofreport"]) && $values["typeofreport"]=="pf_report"){
				$sql = "SELECT officebranch.name as branch, employee.empCode as empId, employee.fullName as name, salaryMonth, paymentDate, (pf) as pf from empsalarytransactions join employee on employee.id=empsalarytransactions.empId left join officebranch on officebranch.id=empsalarytransactions.branchId where pfOpted='Yes' and  paymentDate BETWEEN '".$frmDt."' and '".$toDt."' order by branchId";
				$salarytransactions =  \DB::select(DB::raw($sql));
				foreach ($salarytransactions as $salarytransaction){
					$row = array();
					$row["branch"] = $salarytransaction->branch;
					$row["empId"] = $salarytransaction->empId;
					$row["name"] = $salarytransaction->name;
					$row["salaryMonth"] = date("M",strtotime($salarytransaction->salaryMonth)).", ".date("Y",strtotime($salarytransaction->salaryMonth));
					$row["paymentDate"] = date("d-m-Y",strtotime($salarytransaction->paymentDate));
					$row["pf"] = $salarytransaction->pf;
					$resp[] = $row;
				}
			}
			if(isset($values["typeofreport"]) && $values["typeofreport"]=="esi_report"){
				$sql = "SELECT officebranch.name as branch, employee.empCode as empId, employee.fullName as name, salaryMonth, paymentDate, (esi) as esi from empsalarytransactions join employee on employee.id=empsalarytransactions.empId left join officebranch on officebranch.id=empsalarytransactions.branchId where pfOpted='Yes' and  paymentDate BETWEEN '".$frmDt."' and '".$toDt."' order by branchId";
				$salarytransactions =  \DB::select(DB::raw($sql));
				foreach ($salarytransactions as $salarytransaction){
					$row = array();
					$row["branch"] = $salarytransaction->branch;
					$row["empId"] = $salarytransaction->empId;
					$row["name"] = $salarytransaction->name;
					$row["salaryMonth"] = date("M",strtotime($salarytransaction->salaryMonth)).", ".date("Y",strtotime($salarytransaction->salaryMonth));
					$row["paymentDate"] = date("d-m-Y",strtotime($salarytransaction->paymentDate));
					$row["pf"] = $salarytransaction->esi;
					$resp[] = $row;
				}
			}
			echo json_encode($resp);
			return;
		}
	
		$values['bredcum'] = strtoupper($values["reporttype"]);
		$values['home_url'] = 'masters';
		$values['add_url'] = 'getreport';
		$values['form_action'] = 'getreport';
		$values['action_val'] = '';
		$theads = array('Bank Name','Branch Name', "Account Name", "Account No", "Account Type");
		$values["theads"] = $theads;
	
		$form_info = array();
		$form_info["name"] = "getreport";
		$form_info["action"] = "getreport";
		$form_info["method"] = "post";
		$form_info["class"] = "form-horizontal";
		$form_info["back_url"] = "bankdetails";
		$form_info["bredcum"] = "add bank details";
		$form_info["reporttype"] = $values["reporttype"];
	
		$form_fields = array();
		$select_args = array();
		$select_args[] = "fuelstationdetails.id as id";
		$select_args[] = "fuelstationdetails.name as fname";
		$select_args[] = "cities.name as cname";
	
		$branches =  \OfficeBranch::ALL();
		$branches_arr = array();
		$branches_arr["0"] = "ALL BRANCHES";
		foreach ($branches as $branch){
			$branches_arr[$branch->id] = $branch->name;
		}
		
		$employees =  \Employee::ALL();
		$employees_arr = array();
		$employees_arr["0"] = "ALL EMPLOYEES";
		foreach ($employees as $employee){
			$employees_arr[$employee->id] = $employee->fullName." (".$employee->empCode.")";
		}
		
		$report_type_arr = array();
		$report_type_arr["branch_wise_salary_report"] = "BRANCH WISE SALARY REPORT";
		$report_type_arr["employee_wise_salary_report"] = "EMPLOYEE WISE SALARY REPORT";
		$report_type_arr["pf_report"] = "PF REPORT";
		$report_type_arr["esi_report"] = "ESI REPORT";
		$report_type_arr["bank_payment_report"] = "BANK PAYMENT REPORT";
		$form_field = array("name"=>"typeofreport", "content"=>"type of report ", "readonly"=>"",  "required"=>"required","type"=>"select", "action"=>array("type"=>"onChange","script"=>"showSelectionType(this.value)"),  "options"=>$report_type_arr, "class"=>"form-control chosen-select");
		$form_fields[] = $form_field;
		$form_field = array("name"=>"daterange", "content"=>"date range", "readonly"=>"",  "required"=>"required","type"=>"daterange", "class"=>"form-control");
		$form_fields[] = $form_field;
		$form_field = array("name"=>"paidfrombranch", "content"=>"paid from ", "readonly"=>"",  "required"=>"required","type"=>"select",  "options"=>$branches_arr, "class"=>"form-control chosen-select");
		$form_fields[] = $form_field;
		$form_field = array("name"=>"employee", "content"=>"employee ", "readonly"=>"",  "required"=>"required","type"=>"select",  "options"=>$employees_arr, "class"=>"form-control chosen-select");
		$form_fields[] = $form_field;
		$form_field = array("name"=>"reporttype", "value"=>$values["reporttype"], "content"=>"", "readonly"=>"",  "required"=>"required","type"=>"hidden");
		$form_fields[] = $form_field;
	
		$add_form_fields = array();
		$emps =  \Employee::where("roleId","=",19)->get();
		$emps_arr = array();
		$emps_arr["0"] = "ALL DRIVERS";
		foreach ($emps as $emp){
			$emps_arr[$emp->id] = $emp->fullName;
		}
	
		$vehs =  \Vehicle::where("status","=","ACTIVE")->get();
		$vehs_arr = array();
		foreach ($vehs as $veh){
			$vehs_arr[$veh->id] = $veh->veh_reg;
		}
		$form_field = array("name"=>"fuelstation", "content"=>"by station", "readonly"=>"",  "required"=>"required","type"=>"select", "options"=>$branches_arr, "class"=>"form-control chosen-select");
		$add_form_fields[] = $form_field;
		$form_field = array("name"=>"driver", "content"=>"by driver", "readonly"=>"",  "required"=>"required","type"=>"select", "options"=>$emps_arr, "class"=>"form-control chosen-select");
		$add_form_fields[] = $form_field;
		$form_field = array("name"=>"vehicle", "content"=>"by vehicle", "readonly"=>"",  "required"=>"required","type"=>"select", "options"=>$vehs_arr, "class"=>"form-control chosen-select");
		$add_form_fields[] = $form_field;
	
		$form_info["form_fields"] = $form_fields;
		$form_info["add_form_fields"] = $add_form_fields;
		$values["form_info"] = $form_info;
		$values["provider"] = "bankdetails";
		return View::make('reports.salaryreport', array("values"=>$values));
	}
	
	private function getFuelReport($values){
		if (\Request::isMethod('post'))
		{
			$frmDt = date("Y-m-d", strtotime($values["fromdate"]));
			$toDt = date("Y-m-d", strtotime($values["todate"]));
			$resp = array();
			DB::statement(DB::raw("CALL fuel_transactions_report('".$frmDt."', '".$toDt."');"));
			if($values["fuelreporttype"] == "balanceSheetNoDt"){
				if($values["fuelstation"] == "0"){
					$fuelstations =  \FuelStation::OrderBy("name")->get();
					foreach ($fuelstations as $fuelstation){
						$row = array();
						$row["fuelstation"] = $fuelstation->name;
						$row["totalamt"] = 0;
						$recs = DB::select( DB::raw("SELECT sum(amount) as amt FROM `temp_fuel_transaction` where entity='FUEL TRANSACTION' and fuelStation='".$fuelstation->name."'"));
						if(count($recs)>0) {
							$rec = $recs[0];
							$row["totalamt"] = $rec->amt;
						}
						$row["paidamt"] = 0;
						$recs = DB::select( DB::raw("SELECT sum(amount) as amt FROM `temp_fuel_transaction` where fuelStation='".$fuelstation->name."' and (entity='EXPENSE TRANSACTION' or paymentPaid='Yes')"));
						if(count($recs)>0) {
							$rec = $recs[0];
							$row["paidamt"] = $rec->amt;
						}
						$row["balance"] = $row["totalamt"]-$row["paidamt"];
						if($row["paidamt"] != 0  || $row["totalamt"] != 0){
							$resp[] = $row;
						}
					}
				}
				else if($values["fuelstation"] > 0){
					$fuelstations =  \FuelStation::where("id","=",$values["fuelstation"])->get();
					if(count($fuelstations)>0){
						$fuelstation = $fuelstations[0];
						$row = array();
						$row["fuelstation"] = $fuelstation->name;
						$row["totalamt"] = 0;
						$recs = DB::select( DB::raw("SELECT sum(amount) as amt FROM `temp_fuel_transaction` where entity='FUEL TRANSACTION' and fuelStation='".$fuelstation->name."'"));
						if(count($recs)>0) {
							$rec = $recs[0];
							$row["totalamt"] = $rec->amt;
						}
						$row["paidamt"] = 0;
						$recs = DB::select( DB::raw("SELECT sum(amount) as amt FROM `temp_fuel_transaction` where fuelStation='".$fuelstation->name."' and (entity='EXPENSE TRANSACTION' or paymentPaid='Yes')"));
						if(count($recs)>0) {
							$rec = $recs[0];
							$row["paidamt"] = $rec->amt;
						}
						$row["balance"] = $row["totalamt"]-$row["paidamt"];
						if($row["paidamt"] != 0  || $row["totalamt"] != 0){
							$resp[] = $row;
						}
					}
				}
			}
			else if($values["fuelreporttype"] == "payment"){
				if($values["fuelstation"] == "0"){
					$fuelstations =  \FuelStation::OrderBy("name")->get();
					foreach ($fuelstations as $fuelstation){
						$row = array();
						$row["fuelstation"] = $fuelstation->name;
						$recs = DB::select( DB::raw("SELECT * FROM `temp_fuel_transaction` where (paymentPaid='Yes' or entity='EXPENSE TRANSACTION') and fuelStation='".$fuelstation->name."'"));
						foreach($recs as  $rec) {
							$row["amount"] = $rec->amount;
							$row["date"] = date("d-m-Y",strtotime($rec->date));
							$row["info"] = "Source : ".$rec->entity."<br/>"."Payment Type : ". $rec->paymentType;
							$row["remarks"] = $rec->remarks;
							$row["createdBy"] = $rec->createdBy;
							$resp[] = $row;
						}
					}
				}
				else if($values["fuelstation"] > 0){
					$fuelstations =  \FuelStation::where("id","=",$values["fuelstation"])->get();
					foreach ($fuelstations as $fuelstation){
						$row = array();
						$row["fuelstation"] = $fuelstation->name;
						$recs = DB::select( DB::raw("SELECT * FROM `temp_fuel_transaction` where (paymentPaid='Yes' or entity='EXPENSE TRANSACTION') and fuelStation='".$fuelstation->name."'"));
						foreach($recs as  $rec) {
							$row["amount"] = $rec->amount;
							$row["date"] = date("d-m-Y",strtotime($rec->date));
							$row["info"] = "Source : ".$rec->entity."<br/>"."Payment Type : ". $rec->paymentType;
							$row["remarks"] = $rec->remarks;
							$row["createdBy"] = $rec->createdBy;
							$resp[] = $row;
						}
					}
				}
			}
			else if($values["fuelreporttype"] == "tracking"){
				if($values["fuelstation"] == "0"){
					$fuelstations =  \FuelStation::OrderBy("name")->get();
					foreach ($fuelstations as $fuelstation){
						$row = array();
						$row["fuelstation"] = $fuelstation->name;
						$recs = DB::select( DB::raw("SELECT * FROM `temp_fuel_transaction` where (entity='FUEL TRANSACTION') and fuelStation='".$fuelstation->name."'"));
						foreach($recs as  $rec) {
							$row["vehicle"] = $rec->veh_reg;
							$row["date"] = date("d-m-Y",strtotime($rec->date));
							$row["ltrs"] = $rec->ltrs;
							$row["amount"] = $rec->amount;
							$row["info"] = "Source : ".$rec->entity."<br/>"."Payment Type : ". $rec->paymentType;
							$row["remarks"] = $rec->remarks;
							$row["createdBy"] = $rec->createdBy;
							$resp[] = $row;
						}
					}
				}
				else if($values["fuelstation"] > 0){
					$fuelstations =  \FuelStation::where("id","=",$values["fuelstation"])->get();
					foreach ($fuelstations as $fuelstation){
						$row = array();
						$row["fuelstation"] = $fuelstation->name;
						$recs = DB::select( DB::raw("SELECT * FROM `temp_fuel_transaction` where (entity='FUEL TRANSACTION') and fuelStation='".$fuelstation->name."'"));
						foreach($recs as  $rec) {
							$row["vehicle"] = $rec->veh_reg;
							$row["date"] = date("d-m-Y",strtotime($rec->date));
							$row["ltrs"] = $rec->ltrs;
							$row["amount"] = $rec->amount;
							$row["info"] = "Source : ".$rec->entity."<br/>"."Payment Type : ". $rec->paymentType;
							$row["remarks"] = $rec->remarks;
							$row["createdBy"] = $rec->createdBy;
							$resp[] = $row;
						}
					}
				}
			}
			else if($values["fuelreporttype"] == "vehicleReport"){
				$recs = DB::select( DB::raw("SELECT * FROM `temp_fuel_transaction` where (entity='FUEL TRANSACTION') and vehicleId='".$values["vehicle"]."'"));
				foreach($recs as  $rec) {
					$row = array();
					$row["fuelstation"] = $rec->fuelStation;
					$row["vehicle"] = $rec->veh_reg;
					$row["date"] = date("d-m-Y",strtotime($rec->date));
					$row["ltrs"] = $rec->ltrs;
					$row["amount"] = $rec->amount;
					$row["info"] = "Source : ".$rec->entity."<br/>"."Payment Type : ". $rec->paymentType;
					$row["remarks"] = $rec->remarks;
					$row["createdBy"] = $rec->createdBy;
					$resp[] = $row;
				}
			}
			echo json_encode($resp);
			return;
		}
	
		$values['bredcum'] = strtoupper($values["reporttype"]);
		$values['home_url'] = 'masters';
		$values['add_url'] = 'getreport';
		$values['form_action'] = 'getreport';
		$values['action_val'] = '';
		$theads = array('Bank Name','Branch Name', "Account Name", "Account No", "Account Type");
		$values["theads"] = $theads;
	
		$form_info = array();
		$form_info["name"] = "getreport";
		$form_info["action"] = "getreport";
		$form_info["method"] = "post";
		$form_info["class"] = "form-horizontal";
		$form_info["back_url"] = "bankdetails";
		$form_info["bredcum"] = "add bank details";
		$form_info["reporttype"] = $values["reporttype"];
	
		$form_fields = array();
		$select_args = array();
		$select_args[] = "fuelstationdetails.id as id";
		$select_args[] = "fuelstationdetails.name as fname";
		$select_args[] = "cities.name as cname";
	
		$branches =  \FuelStation::leftjoin("cities","cities.id","=","fuelstationdetails.cityId")->select($select_args)->get();
		$branches_arr = array();
		$branches_arr["0"] = "ALL FUEL STATIONS";
		foreach ($branches as $branch){
			$branches_arr[$branch->id] = $branch->fname." (".$branch->cname.")";
		}
	
		$fuel_rep_arr = array();
		$fuel_rep_arr['balanceSheetNoDt'] = "Fuel Station Balance Sheet";
		$fuel_rep_arr['balanceSheet'] = "Fuel Station Range Sheet";
		$fuel_rep_arr['payment'] = "Fuel Station Payments";
		$fuel_rep_arr['tracking'] = "Track By Station";
		$fuel_rep_arr['vehicleReport'] = "Track By Vehicle";
		$fuel_rep_arr['employeeReport'] = "Track By Driver";
	
		$form_field = array("name"=>"daterange", "content"=>"date range", "readonly"=>"",  "required"=>"required","type"=>"daterange", "class"=>"form-control");
		$form_fields[] = $form_field;
		$form_field = array("name"=>"fuelreporttype", "content"=>"report for ", "readonly"=>"",  "required"=>"required","type"=>"select", "action"=>array("type"=>"onChange","script"=>"showSelectionType(this.value)"), "options"=>$fuel_rep_arr, "class"=>"form-control chosen-select");
		$form_fields[] = $form_field;
		$form_field = array("name"=>"reporttype", "value"=>$values["reporttype"], "content"=>"", "readonly"=>"",  "required"=>"required","type"=>"hidden");
		$form_fields[] = $form_field;
	
		$add_form_fields = array();
		$emps =  \Employee::where("roleId","=",19)->get();
		$emps_arr = array();
		$emps_arr["0"] = "ALL DRIVERS";
		foreach ($emps as $emp){
			$emps_arr[$emp->id] = $emp->fullName;
		}
	
		$vehs =  \Vehicle::where("status","=","ACTIVE")->get();
		$vehs_arr = array();
		foreach ($vehs as $veh){
			$vehs_arr[$veh->id] = $veh->veh_reg;
		}
		$form_field = array("name"=>"fuelstation", "content"=>"by station", "readonly"=>"",  "required"=>"required","type"=>"select", "options"=>$branches_arr, "class"=>"form-control chosen-select");
		$add_form_fields[] = $form_field;
		$form_field = array("name"=>"driver", "content"=>"by driver", "readonly"=>"",  "required"=>"required","type"=>"select", "options"=>$emps_arr, "class"=>"form-control chosen-select");
		$add_form_fields[] = $form_field;
		$form_field = array("name"=>"vehicle", "content"=>"by vehicle", "readonly"=>"",  "required"=>"required","type"=>"select", "options"=>$vehs_arr, "class"=>"form-control chosen-select");
		$add_form_fields[] = $form_field;
	
		$form_info["form_fields"] = $form_fields;
		$form_info["add_form_fields"] = $add_form_fields;
		$values["form_info"] = $form_info;
		$values["provider"] = "bankdetails";
		return View::make('reports.fuelreport', array("values"=>$values));
	}
}
