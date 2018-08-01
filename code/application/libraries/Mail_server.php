<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mail_server {

  function __construct() {

    $this->fromemail = FROM_EMAIL;
    $this->fromname = FROME_NAME;

  }

  /**
   * Send mail to admin user about the forgot password
   * with the reset password reset link
   */
  function send_mail_to_adminuser_forogot_password($result) {

    $link1 = base_url() . "login/resetpassword/" . md5($result->userid);

    $email_content = $this->get_email_content(2);

    $param = array();
    $subject = $email_content->subject;
    $message = $email_content->message;

    $replace = array();
    $replace['[NAME]'] = ucfirst($result->fname);
    $replace['[RESETLINK]'] = $link1;

    $param = $this->filter_template($subject, $message, $replace);

    $param['email'] = $result->email;
    $result = $this->send_mail($param);

    return $result;

  }

  function send_mail_to_newuser($result) {

    $email_content = $this->get_email_content(3);

    $param = array();
    $subject = $email_content->subject;
    $message = $email_content->message;

    $replace = array();
    $replace['[NAME]'] = ucfirst($result->fname);
    $replace['[EMAIL]'] = $result->email;
    $replace['[PASSWORD]'] = $result->password;

    $param = $this->filter_template($subject, $message, $replace);

    $param['email'] = $result->email;
    $result = $this->send_mail($param);

    return $result;

  }

  function send_mail_for_activation_link_unregister($result) {

    $link1 = base_url() . "c/" . $result->org_domain . "/login";

    $email_content = $this->get_email_content(20);

    $param = array();
    $subject = $email_content->subject;
    $message = $email_content->message;

    $replace = array();
    $replace['[NAME]'] = ucfirst($result->fname);
    $replace['[ACTIVATIONLINK]'] = $link1;
    $replace['[ACCESSCODE]'] = $result->accessCode;

    $param = $this->filter_template($subject, $message, $replace);

    $param['email'] = $result->email;
    $result = $this->send_mail($param);

    return $result;

  }

  function send_mail_for_activation_link_register($result) {

    $link1 = app_url() . "c/" . $result->org_domain . "/login?access_code=" . md5($result->accessCode);

    $email_content = $this->get_email_content(19);

    $param = array();
    $subject = $email_content->subject;
    $message = $email_content->message;

    $replace = array();
    $replace['[NAME]'] = ucfirst($result->fname);
    $replace['[ACTIVATIONLINK]'] = $link1;

    $param = $this->filter_template($subject, $message, $replace);

    $param['email'] = $result->email;
    $result = $this->send_mail($param);

    return $result;

  }

  function send_status_change_mail_to_user($result) {

    $email_id = '';
    if ($result->status == 1) {
      $email_id = 8;
    }
    if ($result->status == 2) {
      $email_id = 7;
    }

    if ($result->status == 3) {
      $email_id = 9;
    }

    $email_content = $this->get_email_content($email_id);

    $param = array();
    $subject = $email_content->subject;
    $message = $email_content->message;

    $replace = array();
    $replace['[NAME]'] = ucfirst($result->fname);

    $param = $this->filter_template($subject, $message, $replace);

    $param['email'] = $result->email;
    $result = $this->send_mail($param);

    return $result;

  }

  /**
   * get email content page
   *
   * @param integer $uid
   */
  public function get_email_content($id) {

    $data_string = array();

    $data_string['args']['id'] = $id;
    $url = 'email_template/email_details_by_id';
    $result = api_curl($url, $data_string, 'POST');

    if (isset($result->form_data->res)) {

      return $result->form_data->res;
    }
    else {
      return false;
    }

  }

  /**
   * function to get the email templat in html format with replaced values
   *
   * @param string $subject
   * @param string $message
   * @param array $replace
   * @return array
   */
  public function filter_template($subject, $message, $replace = array()) {

    $r = array();
    $replace['[SITENAME]'] = SITE_NAME;
    $replace['[SITENAMECOM]'] = SITE_NAME_COM;
    $replace['[BASE_URL]'] = base_url();
    $replace['[SITE_LINK]'] = base_url();
    $replace['[MESSAGE_BODY]'] = $message;
    $replace['[LOGO_SRC]'] = get_instance()->config->item('logo_url');

    $message = strtr($message, $replace);
    $emailTemplate = file_get_contents(EMAIL_TEMPLATE);

    $emailTemplate = strtr($emailTemplate, $replace);
    $r['message'] = strtr($emailTemplate, $replace);
    $r['subject'] = strtr($subject, $replace);

    return $r;

  }

  /**
   * function to send the mail of collected data
   *
   * @param array $param
   * @throws exception
   * @return array
   */
  public function send_mail(array $param) {

    $r = array();
    try {

      if (!$param['email']) {
        throw new exception("Please provide email address.");
      }
      elseif (!$param['subject']) {
        throw new exception("Please provide subject.");
      }
      elseif (!$param['message']) {
        throw new exception("Please provide message.");
      }
      else {
        $CI = & get_instance();

        $CI->load->library('email');
		
		/*$config['protocol'] = "smtp";
		$config['smtp_host'] = "ssl://smtp.gmail.com";
		$config['smtp_port'] = "465";
		$config['smtp_user'] = "saurabh.patil@clariontechnologies.co.in"; 
		$config['smtp_pass'] = "Saurabh21@!";
		$config['charset'] = "utf-8";
		$config['mailtype'] = "html";
		$config['newline'] = "\r\n";

		$CI->email->initialize($config);*/


        if (isset($param['fromemail'])) {
          $this->fromemail = $param['fromemail'];
        }

        if (isset($param['fromname'])) {
          $this->fromname = $param['fromname'];
        }

        $CI->email->from($this->fromemail, $this->fromname);

        $CI->email->to($param['email']);
        $CI->email->subject($param['subject']);
        $CI->email->message($param['message']);

        $res = $CI->email->send();

        $r['status'] = 1;
        $r['message'] = 'mail sent';
        $r['res'] = $res;
      }
    }
    catch (Exception $e) {
      $e->getMessage();
      $r['status'] = 1;
      $r['message'] = $e->getMessage();
    }

    return $r;

  }

}
