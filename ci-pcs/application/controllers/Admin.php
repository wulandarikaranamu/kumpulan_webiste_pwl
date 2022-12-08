<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class  Admin  extends CI_Controller {

public function index() {
    // $data['text'] = "halo duniaku";
    $this->load->model('M_admin');
    $data['admin'] = $this->M_admin->getAdmin();
    
    // print_r($data['admin']);
    $this->load->view('v_admin', $data);
}

}
