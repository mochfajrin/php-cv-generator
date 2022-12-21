<?php

use LDAP\Result;

use function PHPSTORM_META\type;

class Cv extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('email') == null) {
            $this->session->set_flashdata('failed_message', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>you are not logged in! </strong>Please login first
              </div>');
            redirect('Auth/login');
        }
    }
    public function index()
    {
        $data = array(
            'title' => 'CV Generator',
            'content' => 'Home',

        );
        $this->load->view('templates/wrapper', $data);
    }
    public function form_fill($id)
    {
        $user = $this->session->userdata('email');
        $dataCv = $this->Model_cv->getDataCv($id);
        $data = array(
            'id_user' => $id,
            'id' => $user,
            'title' => 'CV Generator',
            'content' => 'Formcv',
            'dataCv' => $dataCv,
            'id_users' => $this->Model_cv->getId($this->session->userdata('email')),
            'basics' => $this->Model_cv->getDataCv($id)
        );
        $this->load->view('Formcv', $data);
    }
    public function inputCv()
    {
        $id = $this->input->post('id_user');

        if ($this->Model_cv->getBasicById($this->session->userdata('email')) > 0) {
            $this->Model_cv->update_basic($id, $this->input->post());
            redirect('CV/form_fill/' . $id);
        } else {
            $photo = $_FILES['photo']['name'];

            if ($photo = '') {
            } else {

                $config['upload_path'] = './uploads/photo';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('photo')) {
                    echo 'Failed to Upload Thumbnail';
                } else {
                    $photo = $this->upload->data('file_name');
                }
            }
            $data = array(
                'id_user' => $this->input->post('id_user'),
                'fname' => $this->input->post('fname'),
                'email' => $this->input->post('email'),
                'phonenum' => $this->input->post('phonenum'),
                'website' => $this->input->post('website'),
                'address1' => $this->input->post('address1'),
                'address2' => $this->input->post('address2'),
                'address3' => $this->input->post('address3'),
                'qualy' => $this->input->post('qualy'),
                'interest' => $this->input->post('interest'),
                'photo' => $photo,
            );

            $this->Model_cv->insert_cv($data);
            redirect('CV/form_fill' . $id);
        }
    }
    public function inputWe()
    {
        $data = array(
            'id_user' => $this->input->post('id_user'),
            'jobtit' => $this->input->post('jobtit'),
            'compname' => $this->input->post('compname'),
            'startw' => $this->input->post('startw'),
            'endw' => $this->input->post('endw'),
            'otherwe' => $this->input->post('otherwe'),
        );
        $this->Model_cv->insert_we($data);
        redirect('CV/form_fill');
    }
}
