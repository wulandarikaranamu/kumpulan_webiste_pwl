<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/Firebase/JWT/JWT.php';
use \Firebase\JWT\JWT;

class  Api_pcs  extends REST_Controller {

    private $secret_key = "asbjq87qwdcbjq987wqjhc0zsm";

    function __construct()
    {
        parent::__construct();
        $this->load->model('M_admin');
    }

    public function admin_get() {
        // $data['text'] = "halo duniaku";
        $this->load->model('M_admin');
        // $data['admin'] = $this->M_admin->getAdmin();
        $result= $this->M_admin->getAdmin();
        
        // print_r($data['admin']);
        // $this->load->view('v_admin', $data);
        // echo "Halo";
        
        $data_json = array (
            "success" => true,
            "massage" => "Data found",
            "data" => array(
                "admin" => $result
            )
        );

        // print_r($data_json);
        // echo json_encode($data_json); klu pakai librari bisa ga pake ini tapi
        $this->response($data_json,REST_Controller::HTTP_OK);
    }

    public function admin_post() {
        //validasi
        $validation_message = [];

        if($this->input->post("email")!="" && !filter_var($this->input->post("email"),FILTER_VALIDATE_EMAIL)){
            array_push($validation_message," format Email tidak valid");
        }

        if($this->input->post("password")==""){
            array_push($validation_message,"Password tidak boleh kosong");
        }

        if($this->input->post("nama")==""){
            array_push($validation_message,"Nama tidak boleh kosong");
        }

        if(count($validation_message)>0){
            $data_json = array (
                "success" => false,
                "massage" => "Data Tidak Valid",
                "data" =>  $validation_message
                
            );
        
            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->display();
            exit();
        }
        
        // $this->load->model('M_admin');
        
        // echo "halo";
        //jika lolos validasi
        $data = array(
            "email" => $this->input->post("email"),
            "password" => md5($this->input->post("password")),
            "nama" => $this->input->post("nama"),
        );

       $result = $this->M_admin->insertAdmin($data);

       $data_json = array (
        "success" => true,
        "massage" => "Insert Berhasil",
        "data" => array(
            "admin" => $result
        )
    );

    $this->response($data_json,REST_Controller::HTTP_OK);
        
    }

    public function admin_put() {
        //validasi
        $validation_message = [];

        if($this->put("email")!="" && !filter_var($this->put("email"),FILTER_VALIDATE_EMAIL)){
            array_push($validation_message," format Email tidak valid");
        }

        if($this->put("password")==""){
            array_push($validation_message,"Password tidak boleh kosong");
        }

        if($this->put("nama")==""){
            array_push($validation_message,"Nama tidak boleh kosong");
        }

        if(count($validation_message)>0){
            $data_json = array (
                "success" => false,
                "massage" => "Data Tidak Valid",
                "data" =>  $validation_message
                
            );
        
            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->display();
            exit();
        }
        
        // $this->load->model('M_admin');
        
        // echo "halo";
        //jika lolos validasi
        $data = array(
            "email" => $this->put("email"),
            "password" => md5($this->put("password")),
            "nama" => $this->put("nama"),
        );
    
       $id = $this->put("id");
       $result = $this->M_admin->updateAdmin($data, $id);

       $data_json = array (
        "success" => true,
        "massage" => "Update Berhasil",
        "data" => array(
            "admin" => $result
        )
    );

    $this->response($data_json,REST_Controller::HTTP_OK);
        
    }

    public function admin_delete() {
        $id = $this->delete("id");

        $result = $this->M_admin->deleteAdmin($data, $id);

        if(empty($result)) {
            $data_json = array (
                "success" => false,
                "massage" => "Id Tidak Valid",
                "data" => null
                
            );
        
            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->display();
            exit();
        }

        $data_json = array (
        "success" => true,
        "massage" => "Delete Berhasil",
        "data" => array(
            "admin" => $result
        )
    );

    $this->response($data_json,REST_Controller::HTTP_OK);

    }

    public function login_post(){
        $data = array(
            "email" => $this->input->post("email"),
            "password" => md5($this->input->post("password"))
        );

        $result = $this->M_admin->cekLoginAdmin($data);

        if(empty($result)){
            $data_json = array(
                "success" => false,
                "massage" => " Email dan Password Tidak Valid",
                "error_code" => 1308,
                "data" => null
                
            );
        
            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->display();
            exit();
        } else{
        //echo "berhasil";
        $date = new Datetime();

        $payload["id"] = $result["id"];
        $payload["email"] = $result["email"];
        $payload["iat"] = $date->getTimestamp();
        $payload["exp"] = $date->getTimestamp() + 3600 ;

        $data_json = array (
            "success" => true,
            "massage" => "Otentikasi Berhasil",
            "data" => array(
                "admin" => $result,
                "token" => JWT::encode($payload,$this->secret_key)
            )
        );
    
        $this->response($data_json,REST_Controller::HTTP_OK);


    }

    }

}

