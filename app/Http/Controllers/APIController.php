<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\DALController;

class APIController extends Controller
{
    //
    public function employee_api(Request $request){
        $content = $request->getContent();
        $content_json = json_decode($content,true);

        

        // $employee = array($hr_id,$employee_name,$employee_designation,$employee_division,$employee_department,$employee_section,$employee_gender,
        // $employee_email,$employee_phone,$employee_blood_group,$employee_marital_status,$employee_job_location,$employee_office_location,
        // $employee_supervisor_name,$employee_date_of_birth,$employee_religion,$employee_emergency_contact_name,$employee_emergency_contact_phone,
        // $employee_relation,$employee_home_district,$employee_present_address,$employee_permanent_address,$employee_image_path,$employee_status);

        $employee = array();

        $employee["hr_id"]                      =$content_json["hr_id"];
        $employee["employee_name"]              =$content_json["employee_name"];
        $employee["employee_designation"]       =$content_json["employee_designation"];
        $employee["employee_division"]          =$content_json["employee_division"];
        $employee["employee_department"]        =$content_json["employee_department"];
        $employee["employee_section"]           =$content_json["employee_section"];
        $employee["employee_gender"]            =$content_json["employee_gender"];
        $employee["employee_email"]             =$content_json["employee_email"];
        $employee["employee_phone"]             =$content_json["employee_phone"];
        $employee["employee_blood_group"]       =$content_json["employee_blood_group"];
        $employee["employee_marital_status"]    =$content_json["employee_marital_status"];
        $employee["employee_job_location"]      =$content_json["employee_job_location"];
        $employee["employee_office_location"]   =$content_json["employee_office_location"];
        $employee["employee_supervisor_name"]   =$content_json["employee_supervisor_name"];
        $employee["employee_date_of_birth"]     =$content_json["employee_date_of_birth"];
        $employee["employee_religion"]          =$content_json["employee_religion"];
        $employee["employee_emergency_contact_name"]=$content_json["employee_emergency_contact_name"];
        $employee["employee_emergency_contact_phone"]=$content_json["employee_emergency_contact_phone"];
        $employee["employee_relation"]          =$content_json["employee_relation"];
        $employee["employee_home_district"]     =$content_json["employee_home_district"];
        $employee["employee_present_address"]   =$content_json["employee_present_address"];
        $employee["employee_permanent_address"] =$content_json["employee_permanent_address"];
        $employee["employee_image_path"]        =$content_json["employee_image_path"];
        $employee["employee_status"]            =$content_json["employee_status"];

        $dal_controller = new DALController();
        $dal_controller->insert_employee($employee);

        return $employee;
    }

    public function bulk_employee_insert(){
        //return "true";

        $dal_controller = new DALController();
        $get_lms_users_data = $dal_controller->get_lms_users();

        $email_string = "";
        $email_array = array();
        foreach($get_lms_users_data as $user){
            $single_email = "'".$user->email."'";
            array_push($email_array,$single_email);
        }
        $email_string = implode(",",$email_array);


        $new_employee_list = $dal_controller->get_new_employee($email_string);

        foreach($new_employee_list as $employee){
            $dal_controller->insert_user($employee);
        }
        
        
        //print_r($new_employee_list);
        return "success";
    }
}
