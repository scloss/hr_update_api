<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class DALController extends Controller
{
    //

    public function insert_employee($employee){
        $position = $this->get_position($employee['employee_designation']);
        $email =  $employee['employee_email'];

        $email_explode = explode("@",$email);
        $user_id = $email_explode[0];

        $get_manager = $this->get_manager($employee['']);

        $timeline_log = DB::table('jorani.users')->insertGetId(
            [   
                'firstname'=> $employee['employee_name'],
                'lastname'=> "",
                'login'=> $user_id,
                'email'=> $employee['employee_email'],
                'password'=> "$2a$08$.jB2/fSFNG/us63VQg4iIeSunVVpFgoZFpUl5j2q999tuAJqSh6n2",
                'role'=> "2",
                'manager'=> $employee['employee_gender'],
                'employee_email'=> $employee['employee_email'],
                'employee_phone'=> $employee['employee_phone'],
                'employee_blood_group'=> $employee['employee_blood_group'],
                'employee_marital_status'=> $employee['employee_marital_status'],
                'employee_job_location'=> $employee['employee_job_location'],
                'employee_office_location'=> $employee['employee_office_location'],
                'employee_supervisor_name'=> $employee['employee_supervisor_name'],
                'employee_date_of_birth'=> $employee['employee_date_of_birth'],
                'employee_religion'=> $employee['employee_religion'],
                'employee_emergency_contact_name'=> $employee['employee_emergency_contact_name'],
                'employee_emergency_contact_phone'=> $employee['employee_emergency_contact_phone'],
                'employee_relation'=> $employee['employee_relation'],
                'employee_home_district'=> $employee['employee_home_district'],
                'employee_present_address'=> $employee['employee_present_address'],
                'employee_permanent_address'=> $employee['employee_permanent_address'],
                'employee_image_path'=> $employee['employee_image_path'],
                'employee_status'=> $employee['employee_status']

            ]
        );

        return $timeline_log;
    }

    public function get_position($designation){

        $query = "SELECT * FROM jorani.positions WHERE name = '$designation'";
        $designation_data = \DB::select(\DB::raw($query));

        if(count($designation_data)>0){
            $did = $designation_data[0]->id;
            return $did;
        }else{
            return 0;
        }
    }

    // public function get_manager($id){

    // }

    public function get_lms_users(){
        $query = "SELECT * FROM jorani.users";
        $data = \DB::select(\DB::raw($query));

        return $data;
    }

    public function get_new_employee($email_string){
        $query = "SELECT et.*,st.name as 'sup_name',st.email as 'sup_email' 
                FROM hr_tool_db.employee_table et 
                LEFT JOIN hr_tool_db.employee_table st ON et.supervisor_name = st.employee_row_id
                WHERE et.status = 'Active' AND et.email not in ($email_string)";
        // echo $query;
        // echo "<br/>";
        // dd("asdsa");
        
        $data = \DB::connection('mysql2')->select(\DB::raw($query)); //\DB::select(\DB::raw($query));
        return $data;
    }


    public function insert_user($employee){
        $first_name = $employee->name;
        $email = $employee->email;

        $email_explode = explode("@",$email);
        $login = $email_explode[0];
        $password = "$2a$08$.jB2/fSFNG/us63VQg4iIeSunVVpFgoZFpUl5j2q999tuAJqSh6n2";
        $role = 2;
        
        ///////////////////// Get manager ////////////////////////////////////
        $supervisor_email = $employee->sup_email;
        $manager_info = $this->get_manager_info($supervisor_email);
        if(count($manager_info)>0){
            $manager = $manager_info[0]->id;
        }else{
            $manager = 0;
        }
        ////////////////////// Get Organization ///////////////////////////////////
        $dept_name = $employee->department;
        $org_info = $this->get_organization_info($dept_name);
        if(count($org_info)>0){
            $organization = $org_info[0]->id;
        }else{
            $organization = 0;
        }

        $contract = 1;

        ///////////////////// Get Position ////////////////////////////////////////
        $designation = $employee->designation;
        $position_info = $this->get_position_info($designation);

        if(count($position_info)>0){
            $position = $position_info[0]->id;
        }else{
            $position = 0;
        }

        $datehired = $employee->joining_date;
        $identifier = $employee->hr_id;
        $active = 1;
        $timezone = "Asia/Dhaka";
        $random_hash = "SyiGmeV-LA8IeX5oB_MvSHvK";

        if(!$this->check_employee_exists($email)){
            $insert_user = DB::table('jorani.users')->insertGetId(
                [   
                    'firstname'=> $first_name,
                    'lastname'=> "",
                    'login'=> $login,
                    'email'=> $email,
                    'password'=> "$2a$08$.jB2/fSFNG/us63VQg4iIeSunVVpFgoZFpUl5j2q999tuAJqSh6n2",
                    'role'=> "2",
                    'manager'=> $manager,
                    'organization'=> $organization,
                    'contract'=> $contract,
                    'position'=> $position,
                    'datehired'=> $datehired,
                    'identifier'=> $identifier,
                    'active'=> $active,
                    'timezone'=> $timezone,
                    'random_hash'=> $random_hash
    
                ]
            );
    
            echo "$first_name is inserted";
            echo "<br/>";

            return $insert_user;
        }

        return 0;
    }

    public function get_manager_info($email){
        $query = "SELECT * FROM jorani.users WHERE email = '$email'";
        $data = \DB::select(\DB::raw($query));

        return $data;
    }

    public function get_organization_info($dept_name){
        $query = "SELECT * FROM jorani.organization WHERE name = '$dept_name'";
        $data = \DB::select(\DB::raw($query));

        return $data;
    }

    public function get_position_info($designation){
        $query = "SELECT * FROM jorani.positions WHERE name = '$designation'";
        $data = \DB::select(\DB::raw($query));

        return $data;
    }

    public function check_employee_exists($email){
        $query = "SELECT * FROM jorani.users WHERE email = '$email'";
        $data = \DB::select(\DB::raw($query));

        if(count($data)>0){
            return true;
        }else{
            return false;
        }
    }
}
