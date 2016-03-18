<?php namespace masters;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
class DataTableController extends \Controller {

	/**
	 * add a new city.
	 *
	 * @return Response
	 */
	private $jobs;
	
	public function getDataTableData()
	{
		$this->jobs = \Session::get("jobs");
		$values = Input::All();
		$start = $values['start'];
		$length = $values['length'];
		$total = 0;
		$data = array();
		
		if(isset($values["name"]) && $values["name"]=="cities") {
			$ret_arr = $this->getCities($values, $length, $start);
			$total = $ret_arr["total"];
			$data = $ret_arr["data"];
		}
		else if(isset($values["name"]) && ($values["name"]=="Mobile/Dongle" || $values["name"]=="Internet" || $values["name"]=="Water Cans/Tankers" || $values["name"]=="Current" || $values["name"]=="Computer/Printer Purchases/Repairs")) {
			$ret_arr = $this->getProvider($values, $length, $start);
			$total = $ret_arr["total"];
			$data = $ret_arr["data"];
		}
		else if(isset($values["name"]) && $values["name"]=="states") {
			$ret_arr = $this->getStates($values, $length, $start);
			$total = $ret_arr["total"];
			$data = $ret_arr["data"];
		}
		else if(isset($values["name"]) && $values["name"]=="officebranches") {
			$ret_arr = $this->getOfficeBranches($values, $length, $start);
			$total = $ret_arr["total"];
			$data = $ret_arr["data"];
		}
		else if(isset($values["name"]) && $values["name"]=="lookupvalues") {
			$ret_arr = $this->getLookupValues($values, $length, $start, $values["type"]);
			$total = $ret_arr["total"];
			$data = $ret_arr["data"];
		}
		else if(isset($values["name"]) && $values["name"]=="inventorylookupvalues") {
			$ret_arr = $this->getInventoryLookupValues($values, $length, $start, $values["type"]);
			$total = $ret_arr["total"];
			$data = $ret_arr["data"];
		}
		else if(isset($values["name"]) && $values["name"]=="vehicles") {
			$ret_arr = $this->getVehicles($values, $length, $start);
			$total = $ret_arr["total"];
			$data = $ret_arr["data"];
		}
		else if(isset($values["name"]) && $values["name"]=="employeebattas") {
			$ret_arr = $this->getEmployeeBattas($values, $length, $start);
			$total = $ret_arr["total"];
			$data = $ret_arr["data"];
		}
		else if(isset($values["name"]) && $values["name"]=="services") {
			$ret_arr = $this->getServices($values, $length, $start);
			$total = $ret_arr["total"];
			$data = $ret_arr["data"];
		}
		else if(isset($values["name"]) && $values["name"]=="bankdetails") {
			$ret_arr = $this->getBankDetails($values, $length, $start);
			$total = $ret_arr["total"];
			$data = $ret_arr["data"];
		}
		else if(isset($values["name"]) && $values["name"]=="financecompanies") {
			$ret_arr = $this->getFinanceCompanies($values, $length, $start);
			$total = $ret_arr["total"];
			$data = $ret_arr["data"];
		}
		else if(isset($values["name"]) && $values["name"]=="creditsuppliers") {
			$ret_arr = $this->getCreditSuppliers($values, $length, $start);
			$total = $ret_arr["total"];
			$data = $ret_arr["data"];
		}
		else if(isset($values["name"]) && $values["name"]=="salarydetails") {
			$ret_arr = $this->getSalaryDetails($values, $length, $start);
			$total = $ret_arr["total"];
			$data = $ret_arr["data"];
		}
		else if(isset($values["name"]) && $values["name"]=="fuelstations") {
			$ret_arr = $this->getFuelStations($values, $length, $start);
			$total = $ret_arr["total"];
			$data = $ret_arr["data"];
		}
		else if(isset($values["name"]) && $values["name"]=="loans") {
			$ret_arr = $this->getLoans($values, $length, $start);
			$total = $ret_arr["total"];
			$data = $ret_arr["data"];
		}
		else if(isset($values["name"]) && $values["name"]=="dailyfinances") {
			$ret_arr = $this->getDailyFinances($values, $length, $start);
			$total = $ret_arr["total"];
			$data = $ret_arr["data"];
		}
		else if(isset($values["name"]) && $values["name"]=="roles") {
			$ret_arr = $this->getRoles($values, $length, $start);
			$total = $ret_arr["total"];
			$data = $ret_arr["data"];
		}
		
		else if(isset($values["name"]) && $values["name"]=="leaves") {
			$ret_arr = $this->getLeaves($values, $length, $start);
			$total = $ret_arr["total"];
			$data = $ret_arr["data"];
		}
				
		$json_data = array(
				"draw"            => intval( $_REQUEST['draw'] ),
				"recordsTotal"    => intval( $total ),
				"recordsFiltered" => intval( $total ),
				"data"            => $data
			);
		echo json_encode($json_data);
	}
	
	private function getCities($values, $length, $start){
		$total = 0;
		$data = array();
		$select_args = array();
		$select_args[] = "cities.id as cityId";
		$select_args[] = "cities.name as cityName";
		$select_args[] = "cities.code as cityCode";
		$select_args[] = "states.name as stateName";
		$select_args[] = "cities.status as status";
		$select_args[] = "cities.id as id";
			
		$actions = array();
		if(true){
			$action = array("url"=>"#edit", "type"=>"modal", "css"=>"primary", "js"=>"modalEditCity(", "jsdata"=>array("id","cityName","cityCode", "stateName", "status"), "text"=>"EDIT");
			$actions[] = $action;
		}
		$values["actions"] = $actions;
		
		$search = $_REQUEST["search"];
		$search = $search['value'];
		if($search != ""){
			$entities = \City::where("cities.name", "like", "%$search%")->join("states","states.id", "=", "cities.stateId")->select($select_args)->limit($length)->offset($start)->get();
			$total = count($entities);
		}
		else{
			$entities = \City::join("states","states.id", "=", "cities.stateId")->select($select_args)->limit($length)->offset($start)->get();
			$total = count(\City::where("stateId","!=",0)->get());
		}
		
		$entities = $entities->toArray();
		foreach($entities as $entity){
			$data_values = array_values($entity);
			$actions = $values['actions'];
			$action_data = "";
			foreach($actions as $action){
				if($action["type"] == "modal"){
					$jsfields = $action["jsdata"];
					$jsdata = "";
					$i=0;
					for($i=0; $i<(count($jsfields)-1); $i++){
						$jsdata = $jsdata." '".$entity[$jsfields[$i]]."', ";
					}
					$jsdata = $jsdata." '".$entity[$jsfields[$i]];
					$action_data = $action_data. "<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."' data-toggle='modal' onClick=\"".$action['js'].$jsdata."')\">".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
				else {
					$action_data = $action_data."<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."&id=".$entity['id']."'>".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
			}
			$data_values[5] = $action_data;
			$data[] = $data_values;
		}
		return array("total"=>$total, "data"=>$data);
	}


	private function getStates($values, $length, $start){
		$total = 0;
		$data = array();
		$select_args = array();
		$select_args[] = "states.id as id";
		$select_args[] = "states.name as name";
		$select_args[] = "states.code as code";	
		$select_args[] = "states.status as status";
		$select_args[] = "states.id as id";
	
		$actions = array();
		
		if(in_array(207, $this->jobs)){
			$action = array("url"=>"#edit", "type"=>"modal", "css"=>"primary", "js"=>"modalEditState(", "jsdata"=>array("id","name","code","status"), "text"=>"EDIT");
			$actions[] = $action;
		}
		$values["actions"] = $actions;
		
		$search = $_REQUEST["search"];
		$search = $search['value'];
		if($search != ""){
			$entities = \State::where("name", "like", "%$search%")->select($select_args)->limit($length)->offset($start)->get();
			$total = count($entities);
		}
		else{
			$entities = \State::where("id",">",0)->select($select_args)->limit($length)->offset($start)->get();
			$total = count($entities);
		}
	
		$entities = $entities->toArray();
		foreach($entities as $entity){
			$data_values = array_values($entity);
			$actions = $values['actions'];
			$action_data = "";
			foreach($actions as $action){
				if($action["type"] == "modal"){
					$jsfields = $action["jsdata"];
					$jsdata = "";
					$i=0;
					for($i=0; $i<(count($jsfields)-1); $i++){
						$jsdata = $jsdata." '".$entity[$jsfields[$i]]."', ";
					}
					$jsdata = $jsdata." '".$entity[$jsfields[$i]];
					$action_data = $action_data. "<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."' data-toggle='modal' onClick=\"".$action['js'].$jsdata."')\">".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
				else {
					$action_data = $action_data."<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."&id=".$entity['id']."'>".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
			}
			$data_values[4] = $action_data;
			$data[] = $data_values;
		}
		return array("total"=>$total, "data"=>$data);
	}
	
private function getProvider($values, $length, $start){
		$total = 0;
		$data = array();
		$select_args = array();
		$select_args[] = "serviceproviders.provider as provider";
		$select_args[] = "officebranch.name as branchId";
		$select_args[] = "serviceproviders.name as name";
		$select_args[] = "serviceproviders.number as number";
		$select_args[] = "serviceproviders.companyName as companyName";
		$select_args[] = "serviceproviders.address as address";
		$select_args[] = "serviceproviders.refName as refName";
		$select_args[] = "serviceproviders.refNumber as refNumber";
		$select_args[] = "serviceproviders.status as status";
		$select_args[] = "serviceproviders.id as id";
		$select_args[] = "serviceproviders.configDetails as configDetails";
						
		$actions = array();
		$action = array("url"=>"#edit", "type"=>"modal", "css"=>"primary", "js"=>"modalEditServiceProvider(", "jsdata"=>array("id","branchId","provider","name","number","companyName","configDetails","address","refName","refNumber","status"), "text"=>"EDIT");
		$actions[] = $action;
		$values["actions"] = $actions;
		
		$search = $_REQUEST["search"];
		$search = $search['value'];
		if($search != ""){
			$entities = \ServiceProvider::where("serviceproviders.companyName", "like", "%$search%")->leftjoin("officebranch", "officebranch.id","=","serviceproviders.branchId")->select($select_args)->limit($length)->offset($start)->get();
			$total = count($entities);
		}
		else{
			$entities = \ServiceProvider::where("provider", "=",$values["name"])->leftjoin("officebranch", "officebranch.id","=","serviceproviders.branchId")->select($select_args)->get();
			$total = count($entities);
		}
	
		$entities = $entities->toArray();
		foreach($entities as $entity){
			$data_values = array_values($entity);
			$actions = $values['actions'];
			$action_data = "";
			foreach($actions as $action){
				if($action["type"] == "modal"){
					$jsfields = $action["jsdata"];
					$jsdata = "";
					$i=0;
					for($i=0; $i<(count($jsfields)-1); $i++){
						$jsdata = $jsdata." '".$entity[$jsfields[$i]]."', ";
					}
					$jsdata = $jsdata." '".$entity[$jsfields[$i]];
					$action_data = $action_data. "<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."' data-toggle='modal' onClick=\"".$action['js'].$jsdata."')\">".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
				else {
					$action_data = $action_data."<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."&id=".$entity['id']."'>".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
			}
			$data_values[9] = $action_data;
			$data[] = $data_values;
		}
		return array("total"=>$total, "data"=>$data);
	}
	
	private function getOfficeBranches($values, $length, $start){
		$total = 0;
		$data = array();
		$select_args = array();
		
		$select_args[] = "officebranch.id as branchId";			
		$select_args[] = "officebranch.name as branchName";	
		$select_args[] = "officebranch.code as branchCode";		
		$select_args[] = "cities.name as cityName";
		$select_args[] = "states.name as stateName"; 
		$select_args[] = "rentdetails.ownerName as ownerName";
		$select_args[] = "rentdetails.ownerContactNo as ownerContactNo";
		$select_args[] = "rentdetails.occupiedDate as occupiedDate";
		$select_args[] = "rentdetails.expDate as expDate";
		$select_args[] = "rentdetails.monthlyRent as monthlyRent";
		$select_args[] = "rentdetails.paymentType as paymentType";
		$select_args[] = "rentdetails.paymentExpectedDay as paymentExpectedDay";		
		$select_args[] = "officebranch.id as id";
		
		$actions = array();
		$action = array("url"=>"editofficebranch?","css"=>"primary", "type"=>"", "text"=>"Edit");
		$actions[] = $action;
		$values["actions"] = $actions;
		
		$search = $_REQUEST["search"];
		$search = $search['value'];
		if($search != ""){
			$entities = \OfficeBranch::where("officebranch.name", "like", "%$search%")->leftjoin("rentdetails", "rentdetails.officeBranchId", "=", "officebranch.id")->join("states","states.id", "=", "officebranch.stateId")->join("cities","cities.id", "=", "officebranch.cityId")->select($select_args)->limit($length)->offset($start)->get();
			foreach($entities as $entry){
				if($entry["occupiedDate"] != "0000-00-00" &&  $entry["occupiedDate"] != "" )
					$entry["occupiedDate"] = date("d-m-Y", strtotime($entry["occupiedDate"]));
				if($entry["expDate"] != "0000-00-00" &&  $entry["expDate"] != "" )
					$entry["expDate"] = date("d-m-Y", strtotime($entry["expDate"]));
			}
			$total = count($entities);
		}
		else{
			$entities = \OfficeBranch::leftjoin("rentdetails", "rentdetails.officeBranchId", "=", "officebranch.id")->join("states","states.id", "=", "officebranch.stateId")->join("cities","cities.id", "=", "officebranch.cityId")->select($select_args)->limit($length)->offset($start)->get();
			foreach($entities as $entry){
				if($entry["occupiedDate"] != "0000-00-00" &&  $entry["occupiedDate"] != "" )
					$entry["occupiedDate"] = date("d-m-Y", strtotime($entry["occupiedDate"]));
				if($entry["expDate"] != "0000-00-00" &&  $entry["expDate"] != "" )
					$entry["expDate"] = date("d-m-Y", strtotime($entry["expDate"]));
			}
			$total = \OfficeBranch::leftjoin("rentdetails", "rentdetails.officeBranchId", "=", "officebranch.id")->join("states","states.id", "=", "officebranch.stateId")->join("cities","cities.id", "=", "officebranch.cityId")->count();
		}
		
		$entities = $entities->toArray();
		foreach($entities as $entity){
			$data_values = array_values($entity);
			$actions = $values['actions'];
			$action_data = "";
			foreach($actions as $action){
				if($action["type"] == "modal"){
					$jsfields = $action["jsdata"];
					$jsdata = "";
					$i=0;
					for($i=0; $i<(count($jsfields)-1); $i++){
						$jsdata = $jsdata." '".$entity[$jsfields[$i]]."', ";
					}
					$jsdata = $jsdata." '".$entity[$jsfields[$i]];
					$action_data = $action_data. "<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."' data-toggle='modal' onClick=\"".$action['js'].$jsdata."')\">".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
				else {
					$action_data = $action_data."<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."&id=".$entity['id']."'>".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
			}
			$data_values[12] = $action_data;
			$data[] = $data_values;
		}
		return array("total"=>$total, "data"=>$data);
	}
	
	private function getLookupValues($values, $length, $start, $typeId){
		$total = 0;
		$data = array();
		$select_args = array('name', "parentId", "remarks", "modules", "fields", "enabled", "status", "id");
	
		$actions = array();
		$action = array("url"=>"#edit", "type"=>"modal", "css"=>"primary", "js"=>"modalEditLookupValue(", "jsdata"=>array("id","name","remarks","modules","fields","enabled","status"), "text"=>"EDIT");
		$actions[] = $action;
		$values["actions"] = $actions;
	
		$search = $_REQUEST["search"];
		$search = $search['value'];
		if($search != ""){			
			$entities = \LookupTypeValues::where("name", "like", "%$search%")->select($select_args)->limit($length)->offset($start)->get();
			$total = \LookupTypeValues::where("name", "like", "%$search%")->count();
		}
		else{
			$entities = \LookupTypeValues::where("parentId", "=",$typeId)->select($select_args)->limit($length)->offset($start)->get();
			$total = \LookupTypeValues::where("parentId", "=",$typeId)->count();
		}
	
		$entities = $entities->toArray();
		foreach($entities as $entity){
			$parentName = \LookupTypeValues::where("id","=",$entity["parentId"])->get();
			if(count($parentName)>0){
				$parentName = $parentName[0];
				$parentName = $parentName->name;
				$entity["parentId"] = $parentName;
			}
			
			$data_values = array_values($entity);
			$actions = $values['actions'];
			$action_data = "";
			foreach($actions as $action){
				if($action["type"] == "modal"){
					$jsfields = $action["jsdata"];
					$jsdata = "";
					$i=0;
					for($i=0; $i<(count($jsfields)-1); $i++){
						$jsdata = $jsdata." '".$entity[$jsfields[$i]]."', ";
					}
					$jsdata = $jsdata." '".$entity[$jsfields[$i]];
					$action_data = $action_data. "<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."' data-toggle='modal' onClick=\"".$action['js'].$jsdata."')\">".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
				else {
					$action_data = $action_data."<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."&id=".$entity['id']."'>".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
			}
			$data_values[7] = $action_data;
			$data[] = $data_values;
		}
		return array("total"=>$total, "data"=>$data);
	}
	
	private function getInventoryLookupValues($values, $length, $start, $typeId){
		$total = 0;
		$data = array();
		$select_args = array('name', "parentId", "remarks", "status", "id");
	
		$actions = array();
		$action = array("url"=>"#edit", "type"=>"modal", "css"=>"primary", "js"=>"modalEditLookupValue(", "jsdata"=>array("id","name","remarks","status"), "text"=>"EDIT");
		$actions[] = $action;
		$values["actions"] = $actions;
	
		$search = $_REQUEST["search"];
		$search = $search['value'];
		if($search != ""){
			$entities = \InventoryLookupValues::where("name", "like", "%$search%")->select($select_args)->limit($length)->offset($start)->get();
			$parentName = \InventoryLookupValues::where("id","=",$values["type"])->get();
			if(count($parentName)>0){
				$parentName = $parentName[0];
				$parentName = $parentName->name;
				foreach ($entities as $entity){
					$entity->parentId = $parentName;
				}
			}
			$total = \LookupTypeValues::where("name", "like", "%$search%")->count();
		}
		else{
			$entities = \InventoryLookupValues::where("parentId", "=",$typeId)->select($select_args)->limit($length)->offset($start)->get();
			$parentName = \InventoryLookupValues::where("id","=",$values["type"])->get();
			if(count($parentName)>0){
				$parentName = $parentName[0];
				$parentName = $parentName->name;
				foreach ($entities as $entity){
					$entity->parentId = $parentName;
				}
			}
			$total = \InventoryLookupValues::where("parentId", "=",$typeId)->count();
		}
	
		$entities = $entities->toArray();
		foreach($entities as $entity){
			$data_values = array_values($entity);
			$actions = $values['actions'];
			$action_data = "";
			foreach($actions as $action){
				if($action["type"] == "modal"){
					$jsfields = $action["jsdata"];
					$jsdata = "";
					$i=0;
					for($i=0; $i<(count($jsfields)-1); $i++){
						$jsdata = $jsdata." '".$entity[$jsfields[$i]]."', ";
					}
					$jsdata = $jsdata." '".$entity[$jsfields[$i]];
					$action_data = $action_data. "<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."' data-toggle='modal' onClick=\"".$action['js'].$jsdata."')\">".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
				else {
					$action_data = $action_data."<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."&id=".$entity['id']."'>".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
			}
			$data_values[4] = $action_data;
			$data[] = $data_values;
		}
		return array("total"=>$total, "data"=>$data);
	}
	
	private function getVehicles($values, $length, $start){
		$total = 0;
		$data = array();
		$select_args = array();
		$select_args[] = "vehicle.veh_reg as veh_reg";
		$select_args[] = "lookuptypevalues.name as veh_type";
		$select_args[] = "vehicle.yearof_pur as yearof_pur";
		$select_args[] = "vehicle.seat_cap as seat_cap";
		$select_args[] = "vehicle.status as status";
		$select_args[] = "vehicle.id as id";
			
		$actions = array();
		$action = array("url"=>"editvehicle?","css"=>"primary", "type"=>"", "text"=>"Edit");
		$actions[] = $action;
		if(isset($values['action']) && $values['action']=="blocked") {
			$action = array("url"=>"#block", "type"=>"modal", "css"=>"purple", "js"=>"modalBlockVehicle(", "jsdata"=>array("id","veh_reg"), "text"=>"Unblock");
		}
		else{
			$action = array("url"=>"#block", "type"=>"modal", "css"=>"purple", "js"=>"modalBlockVehicle(", "jsdata"=>array("id","veh_reg"), "text"=>"block");
		}
		$actions[] = $action;
		if(isset($values['action']) && $values['action']=="sell") {
			$action = array("url"=>"#sell", "type"=>"modal", "css"=>"grey", "js"=>"modalSellVehicle(", "jsdata"=>array("id","veh_reg"), "text"=>"sell");
		}
		else{
			$action = array("url"=>"#sell", "type"=>"modal", "css"=>"grey", "js"=>"modalSellVehicle(", "jsdata"=>array("id","veh_reg"), "text"=>"sell");
		}
		$actions[] = $action;
		if(isset($values['action']) && $values['action']=="renew") {
			$action = array("url"=>"#renew", "type"=>"modal", "css"=>"success", "js"=>"modalRenewVehicle(", "jsdata"=>array("veh_reg"), "text"=>"renew");
		}
		else{
			$action = array("url"=>"#renew", "type"=>"modal", "css"=>"success", "js"=>"modalRenewVehicle(", "jsdata"=>array("veh_reg"), "text"=>"renew");
		}
		$actions[] = $action;
		
		$values["actions"] = $actions;
	
		$search = $_REQUEST["search"];
		$search = $search['value'];
		if($search != ""){
			$entities = \Vehicle::where("veh_reg", "like", "%$search%")->where("vehicle.status","=","ACTIVE")->orwhere("vehicle.status","=","INACTIVE")->leftjoin("lookuptypevalues","lookuptypevalues.id", "=", "vehicle.vehicle_type")->select($select_args)->limit($length)->offset($start)->get();
			$total = \Vehicle::where("veh_reg", "like", "%$search%")->where("vehicle.status","=","ACTIVE")->orwhere("vehicle.status","=","INACTIVE")->count();
			foreach ($entities as $entity){
				$entity->yearof_pur = date("d-m-Y",strtotime($entity->yearof_pur));
			}
		}
		else{
			if(isset($values['action']) && $values['action']=="blocked"){
				$entities = \Vehicle::where("vehicle.status","=","BLOCKED")->leftjoin("lookuptypevalues","lookuptypevalues.id", "=", "vehicle.vehicle_type")->select($select_args)->limit($length)->offset($start)->get();
				$total = \Vehicle::where("status","=","BLOCKED")->	count();
			}
			else if(isset($values['action']) && $values['action']=="sell"){
				$entities = \Vehicle::where("vehicle.status","=","SOLD")->leftjoin("lookuptypevalues","lookuptypevalues.id", "=", "vehicle.vehicle_type")->select($select_args)->limit($length)->offset($start)->get();
				$total = \Vehicle::where("status","=","sold")->	count();
			}
			else{
				$entities = \Vehicle::where("vehicle.status","=","ACTIVE")->orwhere("vehicle.status","=","INACTIVE")->leftjoin("lookuptypevalues","lookuptypevalues.id", "=", "vehicle.vehicle_type")->select($select_args)->limit($length)->offset($start)->get();
				$total = \Vehicle::where("vehicle.status","=","ACTIVE")->orwhere("vehicle.status","=","INACTIVE")->count();				
			}
			foreach ($entities as $entity){
				$entity->yearof_pur = date("d-m-Y",strtotime($entity->yearof_pur));
			}
		}
	
		$entities = $entities->toArray();
		foreach($entities as $entity){
			$data_values = array_values($entity);
			$actions = $values['actions'];
			$action_data = "";
			foreach($actions as $action){
				if($action["type"] == "modal"){
					$jsfields = $action["jsdata"];
					$jsdata = "";
					$i=0;
					for($i=0; $i<(count($jsfields)-1); $i++){
						$jsdata = $jsdata." '".$entity[$jsfields[$i]]."', ";
					}
					$jsdata = $jsdata." '".$entity[$jsfields[$i]];
					$action_data = $action_data. "<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."' data-toggle='modal' onClick=\"".$action['js'].$jsdata."')\">".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
				else {
					$action_data = $action_data."<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."&id=".$entity['id']."'>".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
			}
			$data_values[5] = $action_data;
			$data[] = $data_values;
		}
		return array("total"=>$total, "data"=>$data);
	}
	
	private function getEmployeeBattas($values, $length, $start){
		$total = 0;
		$data = array();
		
		$actions = array();
		$action = array("url"=>"editemployeebatta?","css"=>"primary", "type"=>"", "text"=>"Edit");
		$actions[] = $action;
		$values["actions"] = $actions;
			
		if(!isset($values['entries'])){
			$values['entries'] = 10;
		}
		
		$select_args = array();
		$select_args[] = "cities.name as sourceCity";
		$select_args[] = "cities1.name as destinationCity";
		$select_args[] = "lookuptypevalues.name as vehicleTypeId";
		$select_args[] = "employeebatta.driverBatta as driverBatta";
		$select_args[] = "employeebatta.driverSalary as driverSalary";		
		$select_args[] = "employeebatta.helperBatta as helperBatta";
		$select_args[] = "employeebatta.helperSalary as helperSalary";
		$select_args[] = "employeebatta.noOfDrivers as noOfDrivers";
		$select_args[] = "employeebatta.status as status";
		$select_args[] = "employeebatta.id as id";
		
	
		$search = $_REQUEST["search"];
		$search = $search['value'];
		if($search != ""){
			$citieids = \City::where("name", "like", "%$search%")->select("id")->get();
			$citieids_arr = array();
			foreach($citieids as $cityid){
				$citieids_arr[] = $cityid->id;
			}
			$entities = \EmployeeBatta::wherein("sourceCity", $citieids_arr)->leftjoin("cities","cities.id","=","employeebatta.sourceCity")->join("cities as cities1","cities1.id","=","employeebatta.destinationCity")->leftjoin("lookuptypevalues", "employeebatta.vehicleTypeId", "=", "lookuptypevalues.id")->select($select_args)->limit($length)->offset($start)->get();
			$total = \EmployeeBatta::wherein("sourceCity", $citieids_arr)->leftjoin("cities","cities.id","=","employeebatta.sourceCity")->join("cities as cities1","cities1.id","=","employeebatta.destinationCity")->leftjoin("lookuptypevalues", "employeebatta.vehicleTypeId", "=", "lookuptypevalues.id")->count();
		}
		else{
			$entities = \EmployeeBatta::leftjoin("cities","cities.id","=","employeebatta.sourceCity")->join("cities as cities1","cities1.id","=","employeebatta.destinationCity")->leftjoin("lookuptypevalues", "employeebatta.vehicleTypeId", "=", "lookuptypevalues.id")->select($select_args)->limit($length)->offset($start)->get();
			$total = \EmployeeBatta::count();
		}	
		$entities = $entities->toArray();
		foreach($entities as $entity){
			$data_values = array_values($entity);
			$actions = $values['actions'];
			$action_data = "";
			foreach($actions as $action){
				if($action["type"] == "modal"){
					$jsfields = $action["jsdata"];
					$jsdata = "";
					$i=0;
					for($i=0; $i<(count($jsfields)-1); $i++){
						$jsdata = $jsdata." '".$entity[$jsfields[$i]]."', ";
					}
					$jsdata = $jsdata." '".$entity[$jsfields[$i]];
					$action_data = $action_data. "<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."' data-toggle='modal' onClick=\"".$action['js'].$jsdata."')\">".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
				else {
					$action_data = $action_data."<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."&id=".$entity['id']."'>".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
			}
			$data_values[9] = $action_data;
			$data[] = $data_values;
		}
		return array("total"=>$total, "data"=>$data);
	}
	
	private function getServices($values, $length, $start){
		$total = 0;
		$data = array();
	
		$actions = array();
		$action = array("url"=>"#edit", "type"=>"modal", "css"=>"primary", "js"=>"modalEditService(", "jsdata"=>array("id","sourceCity","destinationCity","serviceNo","active","serviceStatus"), "text"=>"EDIT");
		$actions[] = $action;
		$values["actions"] = $actions;
		
		$select_args = array();		
		$select_args[] = "cities.name as sourceCity";
		$select_args[] = "cities1.name as destinationCity";
		$select_args[] = "servicedetails.serviceNo as serviceNo";
		$select_args[] = "servicedetails.active as active";
		$select_args[] = "servicedetails.serviceStatus as serviceStatus";
		$select_args[] = "servicedetails.id as id";
		
		$search = $_REQUEST["search"];
		$search = $search['value'];
		if($search != ""){
			$citieids = \City::where("name", "like", "%$search%")->select("id")->get();
			$citieids_arr = array();
			foreach($citieids as $cityid){
				$citieids_arr[] = $cityid->id;
			}
			$entities = \ServiceDetails::wherein("sourceCity", $citieids_arr)->join("cities","cities.id","=","servicedetails.sourceCity")->join("cities as cities1","cities1.id","=","servicedetails.destinationCity")->select($select_args)->limit($length)->offset($start)->get();
			$total = \ServiceDetails::wherein("sourceCity", $citieids_arr)->join("cities","cities.id","=","servicedetails.sourceCity")->join("cities as cities1","cities1.id","=","servicedetails.destinationCity")->count();;
		}
		else{
			$entities = \ServiceDetails::join("cities","cities.id","=","servicedetails.sourceCity")->join("cities as cities1","cities1.id","=","servicedetails.destinationCity")->select($select_args)->limit($length)->offset($start)->get();
			$total = \ServiceDetails::count();
		}
		$entities = $entities->toArray();
		foreach($entities as $entity){
			$data_values = array_values($entity);
			$actions = $values['actions'];
			$action_data = "";
			foreach($actions as $action){
				if($action["type"] == "modal"){
					$jsfields = $action["jsdata"];
					$jsdata = "";
					$i=0;
					for($i=0; $i<(count($jsfields)-1); $i++){
						$jsdata = $jsdata." '".$entity[$jsfields[$i]]."', ";
					}
					$jsdata = $jsdata." '".$entity[$jsfields[$i]];
					$action_data = $action_data. "<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."' data-toggle='modal' onClick=\"".$action['js'].$jsdata."')\">".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
				else {
					$action_data = $action_data."<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."&id=".$entity['id']."'>".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
			}
			$data_values[5] = $action_data;
			$data[] = $data_values;
		}
		return array("total"=>$total, "data"=>$data);
	}
	
	private function getBankDetails($values, $length, $start){
		$total = 0;
		$data = array();
	
		$select_args = array('lookuptypevalues.name as bankName','branchName', "accountName", "accountNo", "lookuptypevalues1.name as accountType", "balanceAmount", "bankdetails.status as status", "bankdetails.id as id");
		$actions = array();
		$action = array("url"=>"editbankdetails?","css"=>"primary", "type"=>"", "text"=>"Edit");
		$actions[] = $action;
		$values["actions"] = $actions;
			
		$search = $_REQUEST["search"];
		$search = $search['value'];
		if($search != ""){
			$bankids = \LookupTypeValues::where("name", "like", "%$search%")->select("id")->get();
			$bankids_arr = array();
			foreach($bankids as $bankid){
				$bankids_arr[] = $bankid->id;
			}
			$entities = \BankDetails::wherein("bankName", $bankids_arr)->select($select_args)->limit($length)->offset($start)->get();
			$total = \BankDetails::wherein("bankName", $bankids_arr)->count();;
		}
		else{
			//$entities = \BankDetails::join("cities","cities.id","=","servicedetails.sourceCity")->join("cities as cities1","cities1.id","=","servicedetails.destinationCity")->select($select_args)->limit($length)->offset($start)->get();
			$entities = \BankDetails::leftjoin("lookuptypevalues","lookuptypevalues.id","=","bankdetails.bankName")->leftjoin("lookuptypevalues as lookuptypevalues1","lookuptypevalues1.id","=","bankdetails.accountType")->select($select_args)->limit($length)->offset($start)->get();
			$total = \BankDetails::count();
		}
		$entities = $entities->toArray();
		foreach($entities as $entity){
			$data_values = array_values($entity);
			$actions = $values['actions'];
			$action_data = "";
			foreach($actions as $action){
				if($action["type"] == "modal"){
					$jsfields = $action["jsdata"];
					$jsdata = "";
					$i=0;
					for($i=0; $i<(count($jsfields)-1); $i++){
						$jsdata = $jsdata." '".$entity[$jsfields[$i]]."', ";
					}
					$jsdata = $jsdata." '".$entity[$jsfields[$i]];
					$action_data = $action_data. "<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."' data-toggle='modal' onClick=\"".$action['js'].$jsdata."')\">".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
				else {
					$action_data = $action_data."<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."&id=".$entity['id']."'>".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
			}
			$data_values[7] = $action_data;
			$data[] = $data_values;
		}
		return array("total"=>$total, "data"=>$data);
	}
	
	private function getFinanceCompanies($values, $length, $start){
		$total = 0;
		$data = array();
	
		$actions = array();
		$action = array("url"=>"editfinancecompany?","css"=>"primary", "type"=>"", "text"=>"Edit");
		$actions[] = $action;
		$values["actions"] = $actions;

		$select_args = array();
		$select_args[] = "financecompanies.name as name";
		$select_args[] = "financecompanies.contactPerson as contactPerson";
		$select_args[] = "financecompanies.phone1 as phone1";
		$select_args[] = "financecompanies.phone2 as phone2";
		$select_args[] = "financecompanies.fullAddress as fullAddress";
		$select_args[] = "cities.name as cityId";
		$select_args[] = "states.name as stateId";
		$select_args[] = "financecompanies.status as status";
		$select_args[] = "financecompanies.id as id";
			
		$search = $_REQUEST["search"];
		$search = $search['value'];
		if($search != ""){
			$entities = \FinanceCompany::where("financecompanies.name", "like", "%$search%")->join("cities","cities.id","=","financecompanies.cityId")->join("states","states.id","=","financecompanies.stateId")->select($select_args)->limit($length)->offset($start)->get();
			$total = \FinanceCompany::where("name", "like", "%$search%")->count();
		}
		else{
			//$entities = \BankDetails::join("cities","cities.id","=","servicedetails.sourceCity")->join("cities as cities1","cities1.id","=","servicedetails.destinationCity")->select($select_args)->limit($length)->offset($start)->get();
			$entities = \FinanceCompany::join("cities","cities.id","=","financecompanies.cityId")->join("states","states.id","=","financecompanies.stateId")->select($select_args)->limit($length)->offset($start)->get();
			$total = \FinanceCompany::count();;
		}
		$entities = $entities->toArray();
		foreach($entities as $entity){
			$data_values = array_values($entity);
			$actions = $values['actions'];
			$action_data = "";
			foreach($actions as $action){
				if($action["type"] == "modal"){
					$jsfields = $action["jsdata"];
					$jsdata = "";
					$i=0;
					for($i=0; $i<(count($jsfields)-1); $i++){
						$jsdata = $jsdata." '".$entity[$jsfields[$i]]."', ";
					}
					$jsdata = $jsdata." '".$entity[$jsfields[$i]];
					$action_data = $action_data. "<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."' data-toggle='modal' onClick=\"".$action['js'].$jsdata."')\">".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
				else {
					$action_data = $action_data."<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."&id=".$entity['id']."'>".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
			}
			$data_values[8] = $action_data;
			$data[] = $data_values;
		}
		return array("total"=>$total, "data"=>$data);
	}
	
	private function getCreditSuppliers($values, $length, $start){
		$total = 0;
		$data = array();
	
		$actions = array();
		$action = array("url"=>"editcreditsupplier?","css"=>"primary", "type"=>"", "text"=>"Edit");
		$actions[] = $action;
		$values["actions"] = $actions;

		$select_args = array();
		$select_args[] = "creditsuppliers.supplierName as supplierName";		
		$select_args[] = "creditsuppliers.contactPerson as contactPerson";
		$select_args[] = "creditsuppliers.contactPhoneNo as contactPhoneNo";
		$select_args[] = "cities.name as cityId";
		$select_args[] = "creditsuppliers.balanceAmount as balanceAmount";
		$select_args[] = "creditsuppliers.paymentType as paymentType";
		$select_args[] = "creditsuppliers.bankAccount as bankAccount";
		$select_args[] = "creditsuppliers.paymentExpectedDay as paymentExpectedDay";
		$select_args[] = "creditsuppliers.status as status";
		$select_args[] = "creditsuppliers.id as id";
				
		$search = $_REQUEST["search"];
		$search = $search['value'];
		if($search != ""){
			$entities = \CreditSupplier::where("supplierName", "like", "%$search%")->join("cities","cities.id","=","creditsuppliers.cityId")->select($select_args)->limit($length)->offset($start)->get();
			foreach ($entities as $entity){
				$bank =  \BankDetails::where("bankdetails.id", "=", $entity->bankAccount)->join("lookuptypevalues","lookuptypevalues.id","=","bankdetails.bankName")->select("bankdetails.id as id", "bankdetails.accountNo as accountNo", "lookuptypevalues.name as name")->get();
				if(count($bank)>0){
					$bank = $bank[0];
					$entity->bankAccount = $bank->name." - ".$bank->accountNo;
				}
			}
			$total = \CreditSupplier::where("supplierName", "like", "%$search%")->count();
		}
		else{
			$entities = \CreditSupplier::join("cities","cities.id","=","creditsuppliers.cityId")->select($select_args)->limit($length)->offset($start)->get();
			foreach ($entities as $entity){
				$bank =  \BankDetails::where("bankdetails.id", "=", $entity->bankAccount)->join("lookuptypevalues","lookuptypevalues.id","=","bankdetails.bankName")->select("bankdetails.id as id", "bankdetails.accountNo as accountNo", "lookuptypevalues.name as name")->get();
				if(count($bank)>0){
					$bank = $bank[0];
					$entity->bankAccount = $bank->name." - ".$bank->accountNo;
				}
			}
			$total = \CreditSupplier::count();;
		}
		$entities = $entities->toArray();
		foreach($entities as $entity){
			$data_values = array_values($entity);
			$actions = $values['actions'];
			$action_data = "";
			foreach($actions as $action){
				if($action["type"] == "modal"){
					$jsfields = $action["jsdata"];
					$jsdata = "";
					$i=0;
					for($i=0; $i<(count($jsfields)-1); $i++){
						$jsdata = $jsdata." '".$entity[$jsfields[$i]]."', ";
					}
					$jsdata = $jsdata." '".$entity[$jsfields[$i]];
					$action_data = $action_data. "<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."' data-toggle='modal' onClick=\"".$action['js'].$jsdata."')\">".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
				else {
					$action_data = $action_data."<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."&id=".$entity['id']."'>".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
			}
			$data_values[9] = $action_data;
			$data[] = $data_values;
		}
		return array("total"=>$total, "data"=>$data);
	}
	
	private function getSalaryDetails($values, $length, $start){
		$total = 0;
		$data = array();
	
		$actions = array();
		$action = array("url"=>"editsalarydetails?","css"=>"primary", "type"=>"", "text"=>"Edit");
		$actions[] = $action;
		$values["actions"] = $actions;
	
		$select_args = array();
		$select_args[] = "employee.empCode as empId";
		$select_args[] = "employee.fullName as empName";
		$select_args[] = "cities.name as cityName";
		$select_args[] = "officebranch.name as OfficeBranch";
		$select_args[] = "client.name as client";
		$select_args[] = "user_roles_master.name as title";
		$select_args[] = "empsalarydetails.salary as salary";
		$select_args[] = "empsalarydetails.batta as batta";
		$select_args[] = "empsalarydetails.paymentType as paymentType";
		$select_args[] = "empsalarydetails.status as status";		
		$select_args[] = "empsalarydetails.id as id";
		
		$search = $_REQUEST["search"];
		$search = $search['value'];
		if($search != ""){
			$empids = \Employee::where("fullName", "like", "%$search%")->select("id")->get();
			$empids_arr = array();
			foreach($empids as $empid){
				$empids_arr[] = $empid->id;
			}
			$entities = \SalaryDetails::wherein("empId", $empids_arr)->join("employee","employee.id","=","empsalarydetails.empId")->leftjoin("officebranch", "employee.officeBranchId","=","officebranch.id")->leftjoin("client", "employee.clientId","=","client.id")->leftjoin("user_roles_master", "empsalarydetails.title","=","user_roles_master.id")->join("cities", "cities.id","=","employee.cityId")->select($select_args)->limit($length)->offset($start)->get();;
			$total = \SalaryDetails::wherein("empId", $empids_arr)->count();
		}
		else{
			$entities = \SalaryDetails::join("employee","employee.id","=","empsalarydetails.empId")->leftjoin("officebranch", "employee.officeBranchId","=","officebranch.id")->leftjoin("client", "employee.clientId","=","client.id")->leftjoin("user_roles_master", "empsalarydetails.title","=","user_roles_master.id")->join("cities", "cities.id","=","employee.cityId")->select($select_args)->limit($length)->offset($start)->get();;
			$total = \SalaryDetails::count();
		}
		$entities = $entities->toArray();
		foreach($entities as $entity){
			$data_values = array_values($entity);
			$actions = $values['actions'];
			$action_data = "";
			foreach($actions as $action){
				if($action["type"] == "modal"){
					$jsfields = $action["jsdata"];
					$jsdata = "";
					$i=0;
					for($i=0; $i<(count($jsfields)-1); $i++){
						$jsdata = $jsdata." '".$entity[$jsfields[$i]]."', ";
					}
					$jsdata = $jsdata." '".$entity[$jsfields[$i]];
					$action_data = $action_data. "<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."' data-toggle='modal' onClick=\"".$action['js'].$jsdata."')\">".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
				else {
					$action_data = $action_data."<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."&id=".$entity['id']."'>".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
			}
			$data_values[10] = $action_data;
			$data[] = $data_values;
		}
		return array("total"=>$total, "data"=>$data);
	}
	
	private function getFuelStations($values, $length, $start){
		$total = 0;
		$data = array();
		
		$actions = array();
		$action = array("url"=>"editfuelstation?","css"=>"primary", "type"=>"", "text"=>"Edit");
		$actions[] = $action;
		$values["actions"] = $actions;
			
		if(!isset($values['entries'])){
			$values['entries'] = 10;
		}
		
		$select_args = array();
		$select_args[] = "fuelstationdetails.name as name";		
		$select_args[] = "fuelstationdetails.paymentType as paymentType";
		$select_args[] = "fuelstationdetails.PaymentExpectedDay as PaymentExpectedDay";
		$select_args[] = "bankdetails.bankName as bankAccount";
		$select_args[] = "bankdetails.accountNo as accountNo";
		$select_args[] = "cities.name as cityId";
		$select_args[] = "states.name as stateId";
		$select_args[] = "fuelstationdetails.status as status";
		$select_args[] = "fuelstationdetails.id as id";

		$search = $_REQUEST["search"];
		$search = $search['value'];
		if($search != ""){
			$entities = \FuelStation::where("fuelstationdetails.name","like","%$search%")->join("cities","cities.id","=","fuelstationdetails.cityId")->join("states","states.id","=","fuelstationdetails.stateId")->join("bankdetails","bankdetails.id","=","fuelstationdetails.bankAccount")->select($select_args)->limit($length)->offset($start)->get();;
			$total = \FuelStation::where("fuelstationdetails.name","like","%$search%")->count();
			foreach ($entities as $entity){
				$bank =  \BankDetails::where("bankdetails.bankName", "=", $entity->bankAccount)->join("lookuptypevalues","lookuptypevalues.id","=","bankdetails.bankName")->select("bankdetails.id as id", "bankdetails.accountNo as accountNo", "lookuptypevalues.name as name")->get();
				if(count($bank)>0){
					$bank = $bank[0];
					$entity->bankAccount = $bank->name." - ".$bank->accountNo;
				}
			}
		}
		else{
			$entities = \FuelStation::join("cities","cities.id","=","fuelstationdetails.cityId")->join("states","states.id","=","fuelstationdetails.stateId")->join("bankdetails","bankdetails.id","=","fuelstationdetails.bankAccount")->select($select_args)->limit($length)->offset($start)->get();;
			$total = \FuelStation::count();
			foreach ($entities as $entity){
				$bank =  \BankDetails::where("bankdetails.bankName", "=", $entity->bankAccount)->join("lookuptypevalues","lookuptypevalues.id","=","bankdetails.bankName")->select("bankdetails.id as id", "bankdetails.accountNo as accountNo", "lookuptypevalues.name as name")->get();
				if(count($bank)>0){
					$bank = $bank[0];
					$entity->bankAccount = $bank->name." - ".$bank->accountNo;
				}
			}
		}
		$entities = $entities->toArray();
		foreach($entities as $entity){
			$data_values = array_values($entity);
			$actions = $values['actions'];
			$action_data = "";
			foreach($actions as $action){
				if($action["type"] == "modal"){
					$jsfields = $action["jsdata"];
					$jsdata = "";
					$i=0;
					for($i=0; $i<(count($jsfields)-1); $i++){
						$jsdata = $jsdata." '".$entity[$jsfields[$i]]."', ";
					}
					$jsdata = $jsdata." '".$entity[$jsfields[$i]];
					$action_data = $action_data. "<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."' data-toggle='modal' onClick=\"".$action['js'].$jsdata."')\">".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
				else {
					$action_data = $action_data."<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."&id=".$entity['id']."'>".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
			}
			$data_values[8] = $action_data;
			$data[] = $data_values;
		}
		return array("total"=>$total, "data"=>$data);
	}
	
	private function getLoans($values, $length, $start){
		$total = 0;
		$data = array();
	
		$actions = array();
		$action = array("url"=>"editfuelstation?","css"=>"primary", "type"=>"", "text"=>"Edit");
		$actions[] = $action;
		$values["actions"] = $actions;
			
		$tds = array('loanNo','vehicleId', "purpose", "financeCompanyId", "amountFinanced", "agmtDate", "frequency", "installmentAmount","TotInsmt", "PaidInsmt");
		$values["tds"] = $tds;
			
		$actions = array();
		$action = array("url"=>"editloan?","css"=>"primary", "type"=>"", "text"=>"Edit");
		$actions[] = $action;
		$values["actions"] = $actions;
	
		$select_args = array();
		$select_args[] = "loans.loanNo as loanNo";		
		$select_args[] = "loans.vehicleId as vehicleId";
		$select_args[] = "loans.purpose as purpose";
		$select_args[] = "financecompanies.name as financeCompanyId";
		$select_args[] = "loans.amountFinanced as amountFinanced";
		$select_args[] = "loans.agmtDate as agmtDate";
		$select_args[] = "loans.frequency as frequency";
		$select_args[] = "loans.installmentAmount as installmentAmount";
		$select_args[] = "loans.totalInstallments as TotInsmt";
		$select_args[] = "loans.paidInstallments as PaidInsmt";
		$select_args[] = "loans.status as status";
		$select_args[] = "loans.id as id";

		$search = $_REQUEST["search"];
		$search = $search['value'];
		if($search != ""){
			$entities = \Loan::where("loanNo","like","%$search%")->leftjoin("financecompanies","financecompanies.id","=","loans.financeCompanyId")->leftjoin("lookuptypevalues","lookuptypevalues.id","=","loans.frequency")->select($select_args)->limit($length)->offset($start)->get();;
			foreach ($entities as $entity){
				$entity['agmtDate'] = date("d-m-Y",strtotime($entity->agmtDate));
				$vehids = (explode(",",$entity->vehicleId));
				$vehregs = "";
				foreach ($vehids as $vehid){
					$vehname = \Vehicle::where("id","=",$vehid)->get();
					if(count($vehname)>0){
						$vehname = $vehname[0];
						$vehname = $vehname->veh_reg;
					}
					else{
						$vehname = "";
					}
					$vehregs = $vehregs.$vehname.",";
				}
				$entity->vehicleId = $vehregs;
					
			}
			$total = \Loan::count();
		}
		else{
			$entities = \Loan::leftjoin("financecompanies","financecompanies.id","=","loans.financeCompanyId")->leftjoin("lookuptypevalues","lookuptypevalues.id","=","loans.frequency")->select($select_args)->limit($length)->offset($start)->get();;
			foreach ($entities as $entity){
				$entity['agmtDate'] = date("d-m-Y",strtotime($entity->agmtDate));
				$vehids = (explode(",",$entity->vehicleId));
				$vehregs = "";
				foreach ($vehids as $vehid){
					$vehname = \Vehicle::where("id","=",$vehid)->get();
					if(count($vehname)>0){
						$vehname = $vehname[0];
						$vehname = $vehname->veh_reg;
					}
					else{
						$vehname = "";
					}
					$vehregs = $vehregs.$vehname.",";
				}
				$entity->vehicleId = $vehregs;
					
			}
			$total = \Loan::count();
		}
		$entities = $entities->toArray();
		foreach($entities as $entity){
			$data_values = array_values($entity);
			$actions = $values['actions'];
			$action_data = "";
			foreach($actions as $action){
				if($action["type"] == "modal"){
					$jsfields = $action["jsdata"];
					$jsdata = "";
					$i=0;
					for($i=0; $i<(count($jsfields)-1); $i++){
						$jsdata = $jsdata." '".$entity[$jsfields[$i]]."', ";
					}
					$jsdata = $jsdata." '".$entity[$jsfields[$i]];
					$action_data = $action_data. "<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."' data-toggle='modal' onClick=\"".$action['js'].$jsdata."')\">".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
				else {
					$action_data = $action_data."<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."&id=".$entity['id']."'>".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
			}
			$data_values[11] = $action_data;
			$data[] = $data_values;
		}
		return array("total"=>$total, "data"=>$data);
	}
	
	private function getDailyFinances($values, $length, $start){
		$total = 0;
		$data = array();
			
		$actions = array();
		$action = array("url"=>"editdailyfinance?","css"=>"primary", "type"=>"", "text"=>"Edit");
		$actions[] = $action;
		$values["actions"] = $actions;
			
		$select_args = array();
		$select_args[] = "dailyfinances.branchId as branchId";	
		$select_args[] = "financecompanies.name as financeCompanyId";
		$select_args[] = "dailyfinances.amountFinanced as amountFinanced";
		$select_args[] = "dailyfinances.agmtDate as agmtDate";
		$select_args[] = "dailyfinances.interestRate as interestRate";
		$select_args[] = "dailyfinances.frequency as frequency";
		$select_args[] = "dailyfinances.installmentAmount as installmentAmount";
		$select_args[] = "dailyfinances.totalInstallments as TotInsmt";
		$select_args[] = "dailyfinances.paidInstallments as PaidInsmt";
		$select_args[] = "dailyfinances.paymentType as paymentType";
		$select_args[] = "dailyfinances.status as status";
		$select_args[] = "dailyfinances.id as id";
	
		$search = $_REQUEST["search"];
		$search = $search['value'];
		if($search != ""){			
			$fincomids = \FinanceCompany::where("name", "like", "%$search%")->select("id")->get();
			$fincomids_arr = array();
			foreach($fincomids as $fincomid){
				$fincomids_arr[] = $fincomid->id;
			}
			$entities = \DailyFinance::wherein("financeCompanyId", $fincomids_arr)->leftjoin("financecompanies","financecompanies.id","=","dailyfinances.financeCompanyId")->leftjoin("lookuptypevalues","lookuptypevalues.name","=","dailyfinances.frequency")->select($select_args)->limit($length)->offset($start)->get();
			foreach ($entities as $entity){
				$entity['agmtDate'] = date("d-m-Y",strtotime($entity->agmtDate));
				$officeBranch = \OfficeBranch::where("id","=",$entity->branchId)->get();
				$officeBranch = $officeBranch[0]->name;
				$entity['branchId'] = $officeBranch;
			}
			$total = \DailyFinance::count();
		}
		else{
			$entities = \DailyFinance::leftjoin("financecompanies","financecompanies.id","=","dailyfinances.financeCompanyId")->leftjoin("lookuptypevalues","lookuptypevalues.name","=","dailyfinances.frequency")->select($select_args)->limit($length)->offset($start)->get();
			foreach ($entities as $entity){
				$entity['agmtDate'] = date("d-m-Y",strtotime($entity->agmtDate));
				$officeBranch = \OfficeBranch::where("id","=",$entity->branchId)->get();
				$officeBranch = $officeBranch[0]->name;
				$entity['branchId'] = $officeBranch;
			}
			$total = \DailyFinance::count();
		}
		$entities = $entities->toArray();
		foreach($entities as $entity){
			$data_values = array_values($entity);
			$actions = $values['actions'];
			$action_data = "";
			foreach($actions as $action){
				if($action["type"] == "modal"){
					$jsfields = $action["jsdata"];
					$jsdata = "";
					$i=0;
					for($i=0; $i<(count($jsfields)-1); $i++){
						$jsdata = $jsdata." '".$entity[$jsfields[$i]]."', ";
					}
					$jsdata = $jsdata." '".$entity[$jsfields[$i]];
					$action_data = $action_data. "<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."' data-toggle='modal' onClick=\"".$action['js'].$jsdata."')\">".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
				else {
					$action_data = $action_data."<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."&id=".$entity['id']."'>".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
			}
			$data_values[11] = $action_data;
			$data[] = $data_values;
		}
		return array("total"=>$total, "data"=>$data);
	}
	
	private function getRoles($values, $length, $start){
		$total = 0;
		$data = array();
			
		$actions = array();
		$action = array("url"=>"editrole?","css"=>"primary", "type"=>"", "text"=>"Edit");
		$actions[] = $action;
		$action = array("url"=>"jobs?","css"=>"primary", "type"=>"", "text"=>"privilages");
		$actions[] = $action;
		$values["actions"] = $actions;
			
		$select_args = array();
		$select_args[] = "role.id as id";
		$select_args[] = "role.roleName as roleName";
		$select_args[] = "role.description as description";
		$select_args[] = "role.status as status";
		$select_args[] = "role.id as id";
	
		$search = $_REQUEST["search"];
		$search = $search['value'];
		if($search != ""){
			$entities = \Role::where("name", "like", "%$search%")->select("id")->get();
			$fincomids_arr = array();
			foreach($fincomids as $fincomid){
				$fincomids_arr[] = $fincomid->id;
			}
			$entities = \Role::where("roleName", "like", "%$search%")->select($select_args)->limit($length)->offset($start)->get();
			$total = \Role::count();
		}
		else{
			$entities = \Role::select($select_args)->limit($length)->offset($start)->get();
			$total = \Role::count();
		}
		$entities = $entities->toArray();
		foreach($entities as $entity){
			$data_values = array_values($entity);
			$actions = $values['actions'];
			$action_data = "";
			foreach($actions as $action){
				if($action["type"] == "modal"){
					$jsfields = $action["jsdata"];
					$jsdata = "";
					$i=0;
					for($i=0; $i<(count($jsfields)-1); $i++){
						$jsdata = $jsdata." '".$entity[$jsfields[$i]]."', ";
					}
					$jsdata = $jsdata." '".$entity[$jsfields[$i]];
					$action_data = $action_data. "<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."' data-toggle='modal' onClick=\"".$action['js'].$jsdata."')\">".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
				else {
					$action_data = $action_data."<a class='btn btn-minier btn-".$action["css"]."' href='".$action['url']."&id=".$entity['id']."'>".strtoupper($action["text"])."</a>&nbsp; &nbsp;" ;
				}
			}
			$data_values[4] = $action_data;
			$data[] = $data_values;
		}
		return array("total"=>$total, "data"=>$data);
	}
	
}


