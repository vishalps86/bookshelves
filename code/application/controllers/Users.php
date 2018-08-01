<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller {

    function __construct() {

        parent::__construct();

        $this->load->library(array(
            'form_validation',
            'session',
            'PHPExcel'
        )); // load form lidation libaray & session library
        $this->load->helper(array(
            'url',
            'html',
            'form'
        )); // load url,html,form helpers optional       

        _is_admin();
    }

    /**
     * Index Page for this controller.
     */
    public function index() {

        $data_string = array();
        $data_string['args'] = _get_query_args();

		if(isset($_GET['p'])) {
			$data_string['args']['p'] = 1;
		}

        if ($_POST['user_registered_date'] && $_POST['user_registered_date_selected'] == '1') {
            $data_string['args']['user_registered_date'] = $_POST['user_registered_date'];
        }

        if (!isset($data_string['args']['search_box'])) {
            if (isset($_SESSION['user_search_box'])) {
                $data_string['args']['search_box'] = $_SESSION['user_search_box'];
                unset($_SESSION['user_search_box']);
            }
        } else {
            $_SESSION['user_search_box'] = $data_string['args']['search_box'];
        }

		if(!isset($data_string['args']['user_status'])){		
			if(isset($_SESSION['user_status'])) {						
				if(!isset($data_string['args']['p'])) {
					unset($_SESSION['user_status']);
				}
				$data_string['args']['user_status'] = $_SESSION['user_status'];	
			}
		}
		else {
			$_SESSION['user_status'] = $data_string['args']['user_status'];	
		}		

		if(!isset($data_string['args']['orgid'])){		
			if(isset($_SESSION['orgid'])) {						
				if(!isset($data_string['args']['p'])) {
					unset($_SESSION['orgid']);
				}
				$data_string['args']['orgid'] = $_SESSION['orgid'];	
			}
		}
		else {
			$_SESSION['orgid'] = $data_string['args']['orgid'];	
		}		

		if(!isset($data_string['args']['role'])){		
			if(isset($_SESSION['role_filter'])) {						
				if(!isset($data_string['args']['p'])) {
					unset($_SESSION['role_filter']);
				}
				$data_string['args']['role'] = $_SESSION['role_filter'];	
			}
		}
		else {
			$_SESSION['role_filter'] = $data_string['args']['role'];	
		}		

        if (!isset($data_string['args']['orderby'])) {
            if (isset($_SESSION['user_orderby'])) {
                $data_string['args']['orderby'] = $_SESSION['user_orderby'];
                unset($_SESSION['user_orderby']);
            }
        } else {
            $_SESSION['user_orderby'] = $data_string['args']['orderby'];
        }

        if (!isset($data_string['args']['order'])) {
            if (isset($_SESSION['user_order'])) {
                $data_string['args']['order'] = $_SESSION['user_order'];
                unset($_SESSION['user_order']);
            }
        } else {
            $_SESSION['user_order'] = $data_string['args']['order'];
        }

        $url = 'users/list';
		
        $result = api_curl($url, $data_string, 'POST');

        if ($_POST['user_registered_date_selected']) {
            $user_registered_date = date('F') . " " . '1, ' . date('Y') . " - " . date('F') . " " . date('t') . ", " . date('Y');
        }



        $d['result'] = (isset($result->form_data->res)) ? $result->form_data->res : '';
        $d['organizations'] = (isset($result->form_data->organizations)) ? $result->form_data->organizations : '';
		$d['orgevents'] = (isset($result->form_data->orgevents)) ? $result->form_data->orgevents : '';
		$d['peer_award'] = $result->form_data->peer_award;
		$d['hr_admins'] = (isset($result->form_data->hr_admins)) ? $result->form_data->hr_admins : '';
        $d['paging_info'] = (isset($result->form_data->paging->info)) ? $result->form_data->paging->info : '';
        $d['pagination'] = (isset($result->form_data->paging->pagination)) ? $result->form_data->paging->pagination : '';
        $d['per_page'] = (isset($result->form_data->paging->per_page)) ? $result->form_data->paging->per_page : '';
        $d['cur_page'] = (isset($result->form_data->paging->cur_page)) ? $result->form_data->paging->cur_page : '';
        $d['search_box'] = (isset($result->form_data->post->args->search_box)) ? $result->form_data->post->args->search_box : '';
        $d['user_status'] = (isset($result->form_data->post->args->user_status)) ? $result->form_data->post->args->user_status : '';
        $d['orderby'] = (isset($result->form_data->post->args->orderby)) ? $result->form_data->post->args->orderby : 'userid';
        $d['order'] = (isset($result->form_data->post->args->order)) ? $result->form_data->post->args->order : 'ASC';
        $d['limit'] = (isset($result->form_data->post->args->limit)) ? $result->form_data->post->args->limit : '';
        $d['orgid'] = (isset($result->form_data->post->args->orgid)) ? $result->form_data->post->args->orgid : '';
		$d['role_filter'] = (isset($result->form_data->post->args->role)) ? $result->form_data->post->args->role : '';
        $d['user_registered_date'] = (isset($_POST['user_registered_date'])) ? $_POST['user_registered_date'] : $user_registered_date;
        $d['user_registered_date_selected'] = (isset($_POST['user_registered_date_selected'])) ? $_POST['user_registered_date_selected'] : 0;
        $d['callback'] = 'users/index';

        $_SESSION['page'] = $data_string['args']['page'];

        #$d['response']= $result; //$result;
        // set js file to be load in view
        $d['js'][] = $this->viperks->load_script('assets/js/vip/users.js');

        $d['success_msg'] = false;
        if ($this->session->flashdata('success_msg')) {
            $d['success_msg'] = $this->session->flashdata('success_msg');
        }	
        
        // added by pallavi for-1266

        if(isset($_POST['orgid']) && $_POST['orgid']!='' ){
            $d['hidden_gotoorg'] =1;
        }
        //$d['internalString'] = 'organizations/update/'.$_POST['orgid']; 
        $d['internalString'] = 'organizations/update/'.$_POST['orgid'].'/tab-about/tab0default/dashboard';
        // set view template
     
        $d['v'] = 'users/users';
        $this->load->view('template', $d);
    }

    /**
     * create view to create user form page
     */
    public function add_user() {

        $data_string = array();
        $url = 'users/user_form_data';
        $result = api_curl($url, $data_string, 'POST');

        // $d['response']= $result; //$result;
        // set js file to be load in view
        $d['js'][] = $this->viperks->load_script('assets/js/vip/users.js');

        if ($result) {
            if ($result->form_data->success) {
                if (isset($result->form_data->organization)) {
                    $o[] = 'Select';
                    foreach ($result->form_data->organization as $key => $val) {
                        if ($val->orgnization != '') {
                            $o[$val->orgid] = $val->orgnization . ' (' . $val->orgid . ')';
                        }
                    }
                    $d['org_list'] = $o;
                }
            }
        }
		$d['managers'] =  $result->form_data->managers;
        $d['js'][] = $this->viperks->load_script('assets/js/Jcrop.js');
        $d['default'] = _user_form_data();
        $d['v'] = 'users/add_user';
        $this->load->view('template', $d);
    }

    /**
     * save the user create form data
     */
    public function add_user_save() {

        $data_string = $r = array();
        $data_string['args'] = _get_query_args();

        if ($data_string['args']['reg_type'] == 'fle') {
            $this->form_validation->set_rules('fname', 'first Name', 'required|trim');
            $this->form_validation->set_rules('lname', 'last Name', 'required|trim');
            $this->form_validation->set_rules('email', 'email', 'required|valid_email|trim');
        }

        if ($data_string['args']['reg_type'] == 'eid') {
            $this->form_validation->set_rules('lname', 'last Name', 'required|trim');
            if (!isset($data_string['args']['email'])) {
                $this->form_validation->set_rules('empid', 'employee id', 'required|trim');
            }
            if ($data_string['args']['empid'] == '') {
                $this->form_validation->set_rules('email', 'email', 'required|valid_email|trim');
            }
        }
        if ($data_string['args']['empid'] != '') {
            $a = $this->form_validation->set_rules('empid', 'Employee Id', 'required|callback_validate_empid');
        }

        $this->form_validation->set_rules('active', 'Status', 'required');

        $r['result']['success'] = 0; // failed default status

        if ($this->form_validation->run() == false) {
            $errors = validation_errors();
            $r['result']['errors'] = $errors;
        } else {
            $site_url = front_url();
            $data_string['args']['site_url'] = $site_url;
			$data_string['args']['modifiedBy'] = $this->session->userdata['logged_in']['userid'];
            $result = api_curl('users/add_user_save', $data_string, 'POST');

            if ($result) {
                // send mail to user once user is added - Now sending from viperks-api code. Remove below code later.
                /* if ($result->form_data->post->email) {

                  $this->load->library('Mail_server');
                  $post_data = _get_array_args($data_string['args']);

                  if ($post_data["userType"] == 0) {
                  $r = $this->mail_server->send_mail_for_activation_link_unregister($result->form_data->post);
                  }
                  else {
                  $r = $this->mail_server->send_mail_for_activation_link_register($result->form_data->post);
                  }
                  } */

                if ($result->form_data->success == 1) {
                    if ($data_string['args']['active'] == 1) {
                        $user_status = 'registered';
                    } elseif ($data_string['args']['active'] == 2) {
                        $user_status = 'unregistered';
                    }
                    $org = $result->form_data->organization;
                    $internalString = $org[0]->internalString;
                    $orgname = $org[0]->name;
                    $orglogo = $org[0]->handle;
                    $colorScheme = json_decode($org[0]->colorScheme);

                    $header_color = $colorScheme->navigation_bg_color;
                    $header_text_color = $colorScheme->header_text_color;
                    $accessCode = $org[0]->accessCode;
                    $opt_status = $org[0]->weeklyEmailOptStatus;

                    if($orglogo==''){
                        $orglogo = 'viperks-logo.png';
                    }
                    $userid = $result->form_data->userid;
                    $userActivationKey = $result->form_data->userActivationKey;
                    //add user to mailtrain					
                    $mailtrain_data['users'][] = array(
                        'email' => $data_string['args']['email'],
                        'first-name' => $data_string['args']['fname'],
                        'last-name' => $data_string['args']['lname'],
                        'company_subdomain' => $internalString,
                        'company_name' => $orgname,
                        'company_logo' => $orglogo,
                        'company_header_color' => $header_color,
                        'company_activation_code' => $userActivationKey,
                        'company_id' => $data_string['args']['org_list'],
                        'company_header_text_color' => $header_text_color,
                        'user_status' => $user_status,
                        'opt_status' => $opt_status,
                        'user_id' => $userid,
                        'twice' => 1
                    );

                    $m_data = json_encode($mailtrain_data);

                    $m_url = 'addUser';
                    $res = mailtrain_curl($m_url, $m_data, 'POST');

                    $r['result']['success'] = 1;
                    $r['result']['res'] = $result;
                    $this->session->set_flashdata('success_msg', 'User added successfully.');
                } else {
                    $r['result']['success'] = 0;
                    $r['result']['res'] = $result;
                    $r['result']['errors'] = $result->form_data->error;
                }
            } else {
                $r['result']['success'] = 0;
                $r['result']['res'] = $result;
                $r['result']['errors'] = 'API Result Empty';
            }
        }

        echo json_encode($r);
    }

    /**
     * create edit user form page
     *
     * @param integer $uid
     */
    public function edit_user($uid) {

        $data_string = array();

        $data_string['args']['userid'] = $uid;
        $url = 'users/user_form_data';
        $result = api_curl($url, $data_string, 'POST');

        if (isset($result->form_data->user_data)) {

            $user = $result->form_data->user_data[0];

            // set js file to be load in view
            $d['js'][] = $this->viperks->load_script('assets/js/vip/users.js');
            $d['reg_type'] = 'fle';
            if ($result) {
                if ($result->form_data->success) {
                    if (isset($result->form_data->organization)) {
                        $o[] = 'Select';
                        foreach ($result->form_data->organization as $key => $val) {
                            if ($val->orgnization != '') {
                                $o[$val->orgid] = $val->orgnization . ' (' . $val->orgid . ')';
                                if ($user->orgid == $val->orgid) {
                                    if ($val->registrationType) {
                                        $d['reg_type'] = $val->registrationType;
                                    } else {
                                        $d['reg_type'] = 'fle';
                                    }
                                }
                            }
                        }
                        $d['org_list'] = $o;
                    }
                }
            }

            $d['default'] = _user_form_data();
            $d['user'] = $user;
			$d['managers'] =  $result->form_data->managers;
            $d['profile_image_exist'] = 0;
            if ($user->profile_image) {
                if (file_exists(UPLOAD_DIR_USER . '/resized/' . $user->profile_image)) {
                    $d['profile_image_exist'] = 1;
                }
            }
        } else {
            $d['user_error'] = 'User Not found.';
        }

        $d['js'][] = $this->viperks->load_script('assets/js/Jcrop.js');
        $d['v'] = 'users/edit_user';


        // added by pallavi for-1266
        $d['internalString'] = 'organizations/update/'.$user->orgid; 
        
        $this->load->view('template', $d);
    }

    /**
     * save user edit form page data
     */
    public function edit_user_save() {
        $page = $_SESSION['page'];

        $data_string = $r = array();
        $site_url = front_url();
        $data_string['args'] = _get_query_args();
        $data_string['args']['site_url'] = $site_url;
		$data_string['args']['modifiedBy'] = $this->session->userdata['logged_in']['userid'];
		
        if ($data_string['args']['reg_type'] == 'fle') {
            $this->form_validation->set_rules('fname', 'first Name', 'required|trim');
            $this->form_validation->set_rules('lname', 'last Name', 'required|trim');
            $this->form_validation->set_rules('email', 'email', 'required|valid_email|trim');
        }
        /*
         * if ($data_string['args']['reg_type'] == 'fml') {
         * $this->form_validation->set_rules('fname', 'first Name', 'required|trim');
         * $this->form_validation->set_rules('mname', 'middle Name', 'required|trim');
         * $this->form_validation->set_rules('lname', 'last Name', 'required|trim');
         * }
         */
        if ($data_string['args']['reg_type'] == 'eid') {
            $this->form_validation->set_rules('lname', 'last Name', 'required|trim');
            if ($data_string['args']['email'] == '') {
                $this->form_validation->set_rules('empid', 'employee id', 'required|trim');
            }
            if ($data_string['args']['empid'] == '') {
                $this->form_validation->set_rules('email', 'email', 'required|valid_email|trim');
            }
        }
        if ($data_string['args']['empid'] != '') {
           // $this->form_validation->set_rules('empid', 'Employee Id', 'required|callback_validate_empid_edit');
        }

        $r['result']['success'] = 0; // failed default status

        if ($this->form_validation->run() == false) {
            $errors = validation_errors();
            $r['result']['errors'] = $errors;
            $r['result']['validation_errors'] = 1;
        } else {
            
            $result = api_curl('users/edit_user_save', $data_string, 'POST');
            $current_status = $data_string['args']['current_status'];
            $status = $data_string['args']['status'];

            $org = $result->form_data->organization;
            
            $internalString = $org[0]->internalString;
            $orgname = $org[0]->name;
            $orglogo = $org[0]->handle;
            $colorScheme = json_decode($org[0]->colorScheme);

            $header_color = $colorScheme->navigation_bg_color;
            $header_text_color = $colorScheme->header_text_color;
            $accessCode = $org[0]->accessCode;
            $email = $data_string['args']['email'];
            $userid = $data_string['args']['userid'];
            //$opt_status = $org[0]->weeklyEmailOptStatus;

            //if ($current_status != $status) {

                if ($status == '1') {
                    $user_status = 'registered';
                } elseif ($status == '2') {
                    $user_status = 'unregistered';
                } elseif ($status == '3') {
                    $user_status = 'closed';
                }

                if($orglogo ==''){
                    $orglogo = 'viperks-logo.png';
                }
				
				$userActivationKey = $result->form_data->userActivationKey;

                //add user to mailtrain					
                $mailtrain_data['users'][] = array(
                    'email' => $email,
                    'first-name' => $data_string['args']['fname'],
                    'last-name' => $data_string['args']['lname'],
                    'company_subdomain' => $internalString,
                    'company_name' => $orgname,
                    'company_logo' => $orglogo,
                    'company_header_color' => $header_color,
                    'company_activation_code' => $userActivationKey,
                    'company_id' => $data_string['args']['org_list'],
                    'company_header_text_color' => $header_text_color,
                    'user_status' => $user_status,
                    'opt_status' => $data_string['args']['weekly_email_status'],
                    'user_id' => $userid,
                    'twice' => 1
                );

                $m_data = json_encode($mailtrain_data);
                $m_url = 'addUser';
                $res = mailtrain_curl($m_url, $m_data, 'POST');
            //}

            // //add user back to mailtrain when user status is changed from closed to reg/unreg
            // if ($current_status == '3') {
            //     if ($status != '3') {

            //         if ($status == '1') {
            //             $user_status = 'registered';
            //         } elseif ($status == '2') {
            //             $user_status = 'unregistered';
            //         }

            //         if($orglogo ==''){
            //             $orglogo = 'viperks-logo.png';
            //         }
            //         //add user to mailtrain					
            //         $mailtrain_data['users'][] = array(
            //             'email' => $email,
            //             'first-name' => $data_string['args']['fname'],
            //             'last-name' => $data_string['args']['lname'],
            //             'company_subdomain' => $internalString,
            //             'company_name' => $orgname,
            //             'company_logo' => $orglogo,
            //             'company_header_color' => $header_color,
            //             'company_activation_code' => $accessCode,
            //             'company_id' => $data_string['args']['org_list'],
            //             'company_header_text_color' => $header_text_color,
            //             'user_status' => $user_status,
            //             'opt_status' => $opt_status,
            //             'user_id' => $userid,
            //             'twice' => 1
            //         );

            //         $m_data = json_encode($mailtrain_data);
            //         $m_url = 'addUser';
            //         $res = mailtrain_curl($m_url, $m_data, 'POST');
            //     }
            // }

            // send mail to user if status is changed - Now sending from viperks-api code. Remove below code later.

            /* if ($result->form_data->post->status != $result->form_data->post->current_status) {
              if ($result->form_data->post->email) {
              $this->load->library('Mail_server');
              $r = $this->mail_server->send_status_change_mail_to_user($result->form_data->post);
              }
              }
              if ($result->form_data->post->email != $result->form_data->post->current_email) {
              if ($result->form_data->post->email) {
              $this->load->library('Mail_server');
              $r = $this->mail_server->send_mail_for_activation_link($result->form_data->post);
              }
              } */

            if ($result->form_data->success == 1) {
                $r['result']['success'] = 1;
                $r['result']['res'] = $result;
                $r['result']['page'] = $page;
                $this->session->set_flashdata('success_msg', 'User updated successfully.');
            } else {
                $r['result']['success'] = 0;
                $r['result']['res'] = $result;
                $r['result']['errors'] = $result->form_data->error;
            }
        }

        echo json_encode($r);
    }

    /**
     * remove user using delete link
     */
    public function remove($uid) {
		$modifiedBy = $this->session->userdata['logged_in']['userid'];
        $data_string = array();
        $data_string['args'] = array('userid' => $uid,'modifiedBy' => $modifiedBy);

        $url = 'users/remove_user_by_id';
        $result = api_curl($url, $data_string, 'POST');

        $d['result'] = (isset($result->form_data)) ? $result->form_data : '';

        if (isset($result->form_data)) {
            $email = $result->form_data->email;
            $data['users'][] = array('email' => $email, 'user_status' => 'closed');
            $m_data = json_encode($data);
            $m_url = 'addUser';
            $res = mailtrain_curl($m_url, $m_data, 'POST');
            $this->session->set_flashdata('success_msg', 'Peer wallet credits have been removed and the account has been closed');
        }

        echo json_encode($d);
    }

    /**
     * check is given email address is exist or not
     */
    public function check_email_exist() {

        $data_string = array();
        $data_string['args'] = _get_query_args();

        $url = 'users/check_email_exist';
        $result = api_curl($url, $data_string, 'POST');


        if (isset($result->form_data->success) && $result->form_data->success == 1) {
            echo "true"; // not exist
        } else {
            echo "false";
        }
    }

    /**
     * cropped uploaded image and display the crop section
     */
    public function upload_profile() {

        $dir_dest = UPLOAD_DIR_USER . 'original';
        $dir_pics = $dir_dest;

        $handle = new Upload($_FILES['myfile']);
        $r = $return = '';

        if ($handle->uploaded) {
            if ($handle->file_is_image == 1) {
                if ($handle->image_src_x > 400) {
                    $handle->image_resize = true;
                    $handle->image_ratio_y = true;
                    $handle->image_x = 400;
                }
                $handle->Process($dir_dest);
                // we check if everything went OK
                if ($handle->processed) {

                    //original file 
                    //copy files to s3 server (AWSVW-288)
                    upload_to_s3(UPLOAD_DIR_USER_URL . "original/" . $handle->file_dst_name, $dir_dest . "/" . $handle->file_dst_name);

                    $r = _profile_crop_ui($handle->file_dst_name);
                    $return['status'] = 1;
                } else {
                    $return['status'] = 0;
                    $return['error'] = $handle->error;
                }
            } else {
                $return['status'] = 0;
                $return['error'] = "<span class='error'>Please upload valid image.</span>";
            }
        }

        $return['html'] = $r;
        $return['filedata'] = $handle;
        echo json_encode($return);
        exit();
    }

    /**
     * cropped uploaded image save in to resized folder
     * return required data for validation and user interface
     */
    public function crop_profile() {

        if (!empty($this->input->post('w')) && !empty($this->input->post('h'))) {
            $param['src'] = UPLOAD_DIR_USER . 'original/' . $this->input->post('image_name');
            $param['newfilename'] = $this->input->post('image_name');
            $param['targ_w'] = 250;
            $param['targ_h'] = 250;
            $param['output_dir'] = UPLOAD_DIR_USER . 'resized';
            $param['x'] = $this->input->post('x');
            $param['y'] = $this->input->post('y');
            $param['w'] = $this->input->post('w');
            $param['h'] = $this->input->post('h');

            $handle = _crop_image($param);
            if ($handle) {
                $r['status'] = 1;
                $r['success'] = "Image cropped.";
                $r['image'] = $handle;
                $r['newfilename'] = $param['newfilename'];

                //copy files to s3 server (AWSVW-288)
                //resize file
                upload_to_s3(UPLOAD_DIR_USER_URL . "resized/" . $this->input->post('image_name'), $param['output_dir'] . "/" . $this->input->post('image_name'));

                /* $r['original_src'] = base_url() . UPLOAD_DIR_USER_URL . 'original/' . $this->input->post('image_name');
                  $r['resized_src'] = base_url() . UPLOAD_DIR_USER_URL . 'resized/' . $param['newfilename'] . '?file=' . time(); */

                $r['original_src'] = S3_BASE_URL . UPLOAD_DIR_USER_URL . 'original/' . $this->input->post('image_name');
                $r['resized_src'] = S3_BASE_URL . UPLOAD_DIR_USER_URL . 'resized/' . $this->input->post('image_name');
            } else {
                $r['status'] = 0;
                $r['error'] = "Image croping failed.";
                $r['image'] = $handle;
            }
        } else {
            $r['status'] = 0;
            $r['error'] = "Please select area to crop picture.";
            $r['image'] = '';
        }
        echo json_encode($r);
    }

    /**
     * remove user profile picture and update the database
     */
    public function remove_profile_img() {

        $data_string = array();
        $data_string['args'] = _get_query_args();

        $url = 'users/remove_profile_img';
        $result = api_curl($url, $data_string, 'POST');

        $r['result']['data_string'] = $data_string;
        if ($result->form_data->success == 1) {
            $r['result']['success'] = 1;
            $r['result']['res'] = $result;
            $r['result']['success_msg'] = "Profile picture removed successfully.";
        } else {
            $r['result']['success'] = 0;
            $r['result']['res'] = $result;
            $r['result']['errors'] = $result->form_data->error;
        }
        echo json_encode($r);
    }

    /**
     * delete uploaded but not saved image from disk
     */
    public function delete_uploaded_img() {

        $data_string = $r = array();
        $data_string['args'] = _get_query_args();

        $r['result']['data_string'] = $data_string;
        if ($data_string['args']['img_name'] != '') {
            if (file_exists(UPLOAD_DIR_USER . 'original/' . $data_string['args']['img_name'])) {
                @unlink(UPLOAD_DIR_USER . 'original/' . $data_string['args']['img_name']);
                //remove from s3 (AWSVW-288)
                remove_from_s3(UPLOAD_DIR_USER_URL . "original/" . $data_string['args']['img_name']);
                $r['result']['success'] = 1;
                $r['result']['success_msg'] = "Image deleted successfully.";
            } else {
                $r['result']['success'] = 0;
                $r['result']['errors'] = 'Something went wrong. Please try again';
            }
        } else {
            $r['result']['success'] = 0;
            $r['result']['errors'] = 'Something went wrong. Please try again';
        }
        echo json_encode($r);
    }

    /**
     * return the crop html with user uploaded image
     */
    public function edit_profile_img() {

        $data_string = array();
        $html = '';
        $data_string = _get_query_args();

        if ($data_string['img_name']) {
            $html = _profile_crop_ui($data_string['img_name'], 'edit');

            $r['result']['success'] = 1;
            $r['result']['res'] = $html;
        } else {
            $r['result']['success'] = 0;
            $r['result']['res'] = $html;
            $r['result']['errors'] = "image name not found";
        }

        echo json_encode($r);
    }

    /**
     * create view to create user form page
     */
    public function get_org_reg_type() {

        $data_string = array();
        $url = 'users/get_org_reg_type';
        $result = api_curl($url, $data_string, 'POST');

        $data_string = _get_query_args();
        if ($result) {
            if ($result->form_data->success) {
                if (isset($result->form_data->organization)) {
                    foreach ($result->form_data->organization as $key => $val) {
                        if ($data_string['org_id'] == $val->orgid) {
                            $r['result']['success'] = 1;
                            $r['result']['reg_type'] = $val->registrationType;
                        }
                    }
                }
            }
        } else {
            $r['result']['success'] = 0;
            $r['result']['errors'] = "Organization not found.";
        }

        echo json_encode($r);
    }

	public function get_managers_by_orgid() {
		$data_string = array();
        $data_string = _get_query_args();
		
		$url = 'users/get_managers_by_orgid';
        $result = api_curl($url, $data_string, 'POST');
		$r['html'] = $result->form_data->managers;

		echo json_encode($r);
	}

    /**
     * create edit user form page
     *
     * @param integer $uid
     */
    public function view($uid) {

        $data_string = array();

        $data_string['args']['userid'] = $uid;
        $url = 'users/user_form_data';
        $result = api_curl($url, $data_string, 'POST');

        if (isset($result->form_data->user_data)) {

            $user = $result->form_data->user_data[0];

            $d['user'] = $user;
            $d['profile_image_exist'] = 0;
            if ($user->profile_image) {
                if (file_exists(UPLOAD_DIR_USER . '/resized/' . $user->profile_image)) {
                    $d['profile_image_exist'] = 1;
                }
            }
        } else {
            $d['user_error'] = 'User Not found.';
        }

        $d['v'] = 'users/view_user';
        $this->load->view('template', $d);
    }

    /**
     * upload employee excel file
     */
    public function upload_excel() {

        $dir_dest = UPLOAD_DIR_USER . 'files';
        $dir_pics = $dir_dest;

        $handle = new Upload($_FILES['myfile']);
        $r = $return = '';
        if ($handle->uploaded) {

            $handle->Process($dir_dest);
            // we check if everything went OK
            if ($handle->processed) {

                $r = $handle->file_dst_name;
                $return['status'] = 1;
            } else {
                $return['status'] = 0;
                $return['error'] = $handle->error;
            }
        }

        $return['html'] = $r;
        echo json_encode($return);
        exit();
    }

    /* public function upload_user_excel() {
      $dir_dest = UPLOAD_DIR_USER.'files';
      $filename = $dir_dest."/".$_POST['filename'];

      $ext = pathinfo($filename, PATHINFO_EXTENSION);

      $result = null;

      $data = array();

      $data_string = array();


      if($ext == 'csv' || $ext == 'txt') {

      $file = file($filename);
      foreach($file as $k) {
      $csv[] = explode(';', $k);
      }
      //print_r($csv);
      $k = 0;
      foreach($csv as $val) {

      $validate_subdomain = $this->get_orgid($val['1']);

      if($validate_subdomain && (count($val) >= 3)) {


      $data_string['args'] = array(
      'email' => $val['0'],
      'org_list' => $validate_subdomain,
      'fname' => $val['2'],
      'lname' => $val['3'],
      'active' => 2
      );
      $url = 'users/add_user_save';
      $result[] = api_curl($url, $data_string, 'POST');

      if(isset($result[$k]->form_data->error)) {
      $arr[] = $result[$k]->form_data->error;
      }
      }
      else {
      $r['result']['errors'][] = "Company doesn't exits";
      }
      }

      }
      else {

      $this->load->library('PHPExcel');

      $objPHPExcel = PHPExcel_IOFactory::load($filename);

      //get only the Cell Collection
      $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
      $header = null;
      //extract to a PHP readable array format
      foreach ($cell_collection as $cell) {
      $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
      $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
      $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
      //header will/should be in row 1 only. of course this can be modified to suit your need.
      if ($row == 1) {
      $header[$row][$column] = $data_value;
      } else {
      $arr_data[$row][$column] = $data_value;
      }
      }

      //send the data in an array format
      $data['header'] = $header;
      $data['values'] = $arr_data;

      $i = 1;
      $j = 0;
      $values = $data['values'];
      sort($values);

      foreach($values as $key => $val) {
      if(count($val) >= 3) {

      $data_n['valid'][] = $val;
      }
      else {
      $data_n['invalid'][] = $val;
      }
      }
      $r['result']['errors'][] = null;
      foreach($data_n['valid'] as $val) {
      $validate_subdomain = $this->get_orgid($val['B']);
      if($validate_subdomain) {

      $data_string['args'] = array(
      'email' => $val['A'],
      'org_list' => $validate_subdomain,
      'fname' => $val['C'],
      'lname' => $val['D'],
      'active' => 2
      //'activationKey' => $val['E'],
      );

      $url = 'users/add_user_save';
      $result[] = api_curl($url, $data_string, 'POST');

      if(isset($result[$j]->form_data->error)) {
      $arr[] = $result[$j]->form_data->error;
      }
      }
      else {
      $r['result']['errors'][] = "Company doesn't exits";
      }
      $j++;
      }
      }

      if(!empty($result)) {
      if ($result[0]->form_data->success == 1) {
      $r['result']['success'] = 1;
      $this->session->set_flashdata('success_msg', 'Employee added successfully.');
      }
      else {
      $r['result']['errors'][] = $arr;
      }
      }
      else {
      $r['result']['errors'] = 'Error in employee upload.';
      }

      echo json_encode($r);
      exit;

      } */

    public function upload_user_excel() {
        $filename = $_POST['filename'];
        $data_string = array('filename' => $filename, 'modified_by' => $this->session->userdata['logged_in']['userid']);
        $url = 'users/update_empimport';
        $result = api_curl($url, $data_string, 'POST');

        $res = $result->form_data->data;

        if (isset($res)) {
            $r['result']['success'] = 1;
            $this->session->set_flashdata('success_msg', 'File is being uploaded, this may take a few minutes.');
        } else {
            $r['result']['errors'] = 'Error in employee upload.';
        }

        echo json_encode($r);
        exit;
    }

    public function get_orgid($orgname) {

        $data_string = array('orgname' => $orgname);
        $url = 'users/get_orgid';
        $result = api_curl($url, $data_string, 'POST');

        $data_string = _get_query_args();
        if ($result) {
            return $result->form_data->data->orgid;
        } else {
            return false;
        }
    }

    /**
     * check is given empid is exist or not
     */
    public function validate_empid($empid) {

        $orgid = $this->input->post('org_list');

        $data_string = array();
        $data_string['args']['empid'] = $empid;
        $data_string['args']['orgid'] = $orgid;

        $url = 'users/check_empid_exist';
        $result = api_curl($url, $data_string, 'POST');


        if (isset($result)) {
            if ($result->form_data->data > 0) {
                $this->form_validation->set_message('validate_empid', 'Employee Id already exists.');
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }

    /**
     * check is given empid is exist or not
     */
    public function validate_empid_edit($empid) {

        $orgid = $this->input->post('org_list');
        $userid = $this->input->post('userid');

        $data_string = array();
        $data_string['args']['empid'] = $empid;
        $data_string['args']['orgid'] = $orgid;
        $data_string['args']['userid'] = $userid;

        $url = 'users/check_empid_exist';
        $result = api_curl($url, $data_string, 'POST');


        if (isset($result)) {
            if ($result->form_data->data > 0) {
                $this->form_validation->set_message('validate_empid_edit', 'Employee Id already exists.');
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }

    public function reset_password() {
        $email = $_POST['email'];
        $subdomain = $_POST['subdomain'];

        $url = '/v3/user/password/forgot/index.php';

        $data_string = array();
        $data_string['email'] = $email;

        $response = webservice_curl($url, $data_string, 'POST');

        if ($response) {
            $url = '/v3/user/read/index.php';
            $userData = webservice_curl($url, $data_string, 'POST');

            $decode = $response;
            $responseUserName = $userData;

            if ($responseUserName && $responseUserName->active) {
                $site_url = front_url();
                $resetUrl = 'https://' . $subdomain . '.' . $site_url . 'account/reset-password/' . $responseUserName->userid . '/' . $decode->resetKey;
                $name = $responseUserName->first_name . " " . $responseUserName->last_name;
                $subject = 'Your viperks Reset Password Link!';

                $data = array('name' => $name, 'reseturl' => $resetUrl, 'email' => $email, 'subject' => $subject, 'userid' => $responseUserName->userid);

                $url_api = 'users/reset_password';
                $result = api_curl($url_api, $data, 'POST');

                $r['result']['success'] = 1;
                echo json_encode($r);
                exit;
            } else {
                $this->session->set_flashdata('success_msg', 'This email address hasn\'t activated yet, please activate the user first.');
                $r['result']['error'] = 1;
                echo json_encode($r);
                exit;
            }
        }
    }

    //By Vivek VVD-514
    public function user_action() {
        $data_string = array();
        $data_string['args'] = _get_query_args();
		$data_string['args']['modifiedBy'] = $this->session->userdata['logged_in']['userid'];
        $url = 'users/user_action';
        $result = api_curl($url, $data_string, 'POST');
		$user_arr = $result->form_data->users;        
		
		if($data_string['args']['action'] == 'close_users') {			
			 $users = explode(",", $data_string['args']['val']);
			 $user_status = 'closed';		
			 foreach ($users as $user) {	                
				$email = $user_arr->$user;
				//delete user from mailtrain					
				$mailtrain_data['users'][] = array(
					'email' => $email,
					'user_status' => $user_status				
				);
			 }
		  
			$m_data = json_encode($mailtrain_data);
			$m_url = 'addUser';
			$res = mailtrain_curl($m_url, $m_data, 'POST');
		}
        if ($result->form_data->status == 1) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function upload_users() {
        $data_string = array();
        $url = 'users/get_org_list';
        $result = api_curl($url, $data_string, 'POST');

        $d['organizations'] = (isset($result->form_data->organizations)) ? $result->form_data->organizations : '';
        $d['js'][] = $this->viperks->load_script('assets/js/vip/users.js');
        $d['v'] = 'users/upload_users';
        $this->load->view('template', $d);
    }

    public function dropped_file_upload() {
        $file = $_FILES["file"];
        $file["clean"] = date("Y-m-d-H-i-s") . "-" . $file["name"];

        $pb = null;
        $pb["dest"] = $file["dest"];
        $pb["file"] = $file["clean"];

        //print json_encode($pb);

        $dir_dest = UPLOAD_DIR_USER . 'files';
        $dir_pics = $dir_dest;

        $handle = new Upload($_FILES['file']);
        $r = $return = '';
        if ($handle->uploaded) {

            $handle->Process($dir_dest);
            // we check if everything went OK
            if ($handle->processed) {

                $r = $handle->file_dst_name;
                $return['status'] = 1;
            } else {
                $return['status'] = 0;
                $return['error'] = $handle->error;
            }
        }

        $return['html'] = $r;
        echo json_encode($return);
        exit();
    }

    public function upload_multiple_users() {

        $pb = null;
        $dir_dest = UPLOAD_DIR_USER . 'files/';
        $file = $_POST["file"];
        $dest = $dir_dest . $file;

        $d['first_header'] = $_POST['first_header'];
        $d['update_existing'] = $_POST['update_existing'];
        $d['terminate_employees'] = $_POST['terminate_employees'];
		$d['invite_new_employees'] = $_POST['invite_new_employees'];

        $pb["file"] = $dest;
        $pb["clean"] = $_POST["file"];
        $upload = $this->getExcelObject($pb);

        $max_col = $upload["sheet"]["max_col"];
        $rows = $upload["sheet"]["rows"];
        $max_row = $upload["sheet"]["max_row"];
        $orgid = $_POST['orgid'];
		$requested_by = $_POST['hr_admin'];

        $d['file'] = $file;
        $d['max_col'] = $max_col;
        $d['rows'] = $rows;
        $d['max_row'] = $max_row;
        $d['orgid'] = $orgid;
		$d['requested_by'] = $requested_by;
	
        $d['v'] = 'users/upload_multiple_users';
        $this->load->view('template', $d);
    }

    public function getExcelObject($pb) {

        // pb expects file
        $dest_file = $pb["file"];

        $this->load->library('PHPExcel');

        //  Read your Excel workbook
        try {
            $inputFileType = PHPExcel_IOFactory::identify($dest_file);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($dest_file);
        } catch (Exception $e) {
            die("Error loading file '" . pathinfo($dest_file, PATHINFO_BASENAME) . "': " . $e->getMessage());
            exit;
        }

        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumnStr = $sheet->getHighestColumn();
        $highestColumnNum = PHPExcel_Cell::columnIndexFromString($highestColumnStr);

        $rows = null;

        //  Loop through each row of the worksheet in turn
        for ($row = 1; $row <= $highestRow; $row++) {
            //  Read a row of data into an array
            $rowData = $sheet->rangeToArray("A" . $row . ":" . $highestColumnStr . $row, null, true, false);
            $rows[$row - 1] = grab($rowData);
        }

        $upload = null;
        $upload["file"] = $dest_file;
        $upload["sheet"] = null;
        $upload["sheet"]["max_row"] = $highestRow;
        $upload["sheet"]["max_col"] = $highestColumnNum;
        $upload["sheet"]["max_col_str"] = $highestColumnStr;
        $upload["sheet"]["rows"] = $rows;
        return $upload;
    }

    public function upload() {
        $dir_dest = UPLOAD_DIR_USER . 'files/';
        $file = $_POST["file"];
        $dest = $dir_dest . $file;
        $pb['file'] = $dest;

        $orgid = $_POST['orgid'];
        $first_column_is_header = $_POST['first_column_is_header'];
        $update_existing_employee = $_POST['update_existing_employee'];
        $terminate_employees = $_POST['terminate_employees'];
		$invite_new_employees = $_POST['invite_new_employees'];
		$requested_by = $_POST['requested_by'];

        $excel = $this->getExcelObject($pb);
        $max_col = $excel["sheet"]["max_col"];

        $key = $_POST['key'];
        $keys = array();
        for ($i = 0; $i < $max_col; $i++) {
            $selected_keys = array_keys($key);
            if (in_array($i, $selected_keys) !== false) {
                $keys[$i] = $key[$i];
            } else {
                $keys[$i] = 'skip';
            }
        }
        $keys_encoded = json_encode($keys, true);
        $data_string = array();

        $data_string['args']['orgid'] = $orgid;
        $data_string['args']['first_header'] = $first_column_is_header;
        $data_string['args']['update_existing_employee'] = $update_existing_employee;
        $data_string['args']['terminate_employees'] = $terminate_employees;
		$data_string['args']['invite_new_employees'] = $invite_new_employees;
        $data_string['args']['column_key'] = $keys_encoded;
        $data_string['args']['filename'] = $file;
		$data_string['args']['modifiedBy'] = $this->session->userdata['logged_in']['userid']; 
		$data_string['args']['requested_by'] = $requested_by; 
	
        $url = 'users/save_import_empoloyee_cron';
        $result = api_curl($url, $data_string, 'POST');

        if ($result->form_data->success > 0) {
            $return['result']['success'] = 1;
        } else {
            $return['result']['success'] = 0;
        }

        echo json_encode($return);
        exit();
    }

    public function credit_log($uid) {
        $data_string = array();
		$data_string = _get_query_args();
        $data_string['user_id'] = $uid;
        $r['result']['success'] = 0; // failed default status

		$data_string_event = array();
        $data_string_event['userid'] = $uid;
		$data_string_event['type'] = '';

		$url = 'credits/get_orgevents';
        $result = api_curl($url, $data_string_event, 'POST');
		$d['events'] = $result->res;

		 //Get event data
        $url_event = 'events/get_events';
        $events_data = api_curl($url_event,null,'POST');	
        $main_events = $events_data->form_data->res;
        $d['main_events'] = $main_events;

        $url = 'credits/user_credit_log';
        $result = api_curl($url, $data_string, 'POST');

        $url_peer = 'credits/user_peer_credit_bal';
        $result_peer = api_curl($url_peer, $data_string, 'POST');
        //echo "<pre>";print_r($result_peer);exit();

        $d['log'] = $result->form_data->res;
        $d['peer_bal'] = $result_peer->form_data->res[0]->peerWallet;
        $d['user'] = $result->form_data->userres[0];
		$d['callback'] = 'users/credit_log/'.$uid;
		$d['method'] = $data_string['method'];
		$d['event_type'] = $data_string['event_type'];
		$d['event_name'] = $data_string['event_name'];
		$d['main_event'] = $data_string['main_event'];
        $d['v'] = 'users/credit_log';

        $this->load->view('template', $d);
    }

    public function download_credit_history($uid){
        $data_string = array();
        $data_string['user_id'] = $uid;
        $r['result']['success'] = 0; // failed default status

        $url = 'credits/user_credit_log';
        $result = api_curl($url, $data_string, 'POST');
        $log = $result->form_data->res; 

        if (empty($log)){
            echo 'false';die;
            return false;
        }       

        $this->phpexcel->setActiveSheetIndex(0);

        $this->phpexcel->getActiveSheet()->setTitle('Credit History');       

        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Date');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Debit');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Credit');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Main Event');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Event Type');
        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Org Event Name');
        $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Order#');
		$this->phpexcel->getActiveSheet()->setCellValue('H1', 'Reason');
		$this->phpexcel->getActiveSheet()->setCellValue('I1', 'Method');
		$this->phpexcel->getActiveSheet()->setCellValue('J1', 'Modified By');

        
        $this->phpexcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->phpexcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
        $this->phpexcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
        $this->phpexcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);
        $this->phpexcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);
        $this->phpexcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);
        $this->phpexcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);
  	    $this->phpexcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);
		$this->phpexcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);
		$this->phpexcel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);
        
        $i = 2;
        foreach ($log as $key => $userlog) {
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $i, $userlog->creditDate);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $i, $userlog->debited ? $userlog->debited : '-');
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $i, $userlog->credited ? $userlog->credited : '-');
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $i, $userlog->eventName ? $userlog->eventName : '-');
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $i, $userlog->eventType ? $userlog->eventType : '-');
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $i, $userlog->orgEventName ? $userlog->orgEventName : '-');
            $this->phpexcel->getActiveSheet()->setCellValue('G' . $i, $userlog->tranid);
			$this->phpexcel->getActiveSheet()->setCellValue('H' . $i, $userlog->reason);
			$this->phpexcel->getActiveSheet()->setCellValue('I' . $i, $userlog->method);
			$this->phpexcel->getActiveSheet()->setCellValue('J' . $i, $userlog->modifiedBy);

            $this->phpexcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(TRUE);
            $this->phpexcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(TRUE);
            $this->phpexcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(TRUE);
            $this->phpexcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(TRUE);
            $this->phpexcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(TRUE);
            $this->phpexcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(TRUE);
            $this->phpexcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(TRUE);            
			$this->phpexcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(TRUE);     
			$this->phpexcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(TRUE);     
			$this->phpexcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(TRUE);     
            $i++;
        }

        ob_end_clean();
        $filename = 'credit-history.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        $objWriter->save('php://output');
        exit;
    }

	public function get_org_hr_admins() {
		$data_string = array();
        $data_string = _get_query_args();
		
		$url = 'users/get_org_hr_admins';
        $result = api_curl($url, $data_string, 'POST');
		$r['html'] = $result->res;

		echo json_encode($r);
	}

	public function peer_wallet_log($uid) {
        $data_string = array();
		$data_string = _get_query_args();
        $data_string['user_id'] = $uid;
        $r['result']['success'] = 0; // failed default status

		$data_string_event = array();
        $data_string_event['userid'] = $uid;
		$data_string_event['type'] = '';

		$url = 'credits/get_orgevents';
        $result = api_curl($url, $data_string_event, 'POST');
		$d['events'] = $result->res;

		 //Get event data
        /*$url_event = 'events/get_events';
        $events_data = api_curl($url_event,null,'POST');	
        $main_events = $events_data->form_data->res;
        $d['main_events'] = $main_events;*/

        $url = 'credits/user_peer_wallet_log';
        $result = api_curl($url, $data_string, 'POST');

        $url_peer = 'credits/user_peer_credit_bal';
        $result_peer = api_curl($url_peer, $data_string, 'POST');
        //echo "<pre>";print_r($result_peer);exit();

        $d['log'] = $result->form_data->res;
        $d['peer_bal'] = $result_peer->form_data->res[0]->peerWallet;
        $d['user'] = $result->form_data->userres[0];
		$d['callback'] = 'users/peer_wallet_log/'.$uid;
		$d['method'] = $data_string['method'];
		$d['transaction_type'] = $data_string['transaction_type'];
		$d['peer_event_name'] = $data_string['peer_event_name'];
		$d['transaction_status'] = $data_string['transaction_status'];
        $d['v'] = 'users/peer_wallet_log';

        $this->load->view('template', $d);
    }

}
