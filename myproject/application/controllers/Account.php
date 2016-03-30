<?php
class account extends CI_Controller{
		
	public function __construct(){
		parent:: __construct();
		$this->load->model('account_model');
	}
	
	public function index() {
		redirect('account/login');
	}

	/* LOGIN */
	public function login()	{
		$this->load->library('form_validation');

		if ($this->session->userdata('logged_in')) { //if user logged - go to home
			redirect('home');
		}

		$post = $this->input->post();
		if($post) { //if request received
			//form validation without the need to query the database
			$this->form_validation->set_rules('email' , 'Email Address', 'trim|required|min_length[6]|max_length[50]|valid_email|xss_clean');
			$this->form_validation->set_rules('password','Password', 'trim|required|min_length[6]|max_length[50]|callback_check_login');

			if ($this->form_validation->run() == TRUE) {
				$userData = $this->account_model->get_user_data(set_value('email'));//get user data
				if($userData) {
					$session_data = array(
						'userID' => $userData->userID,
						'firstname' => $userData->firstname,
						'lastname' => $userData->lastname,
						'email' => $userData->email,
						'username' => $userData->username,
						'logged_in' => 1
					);
					$this->session->set_userdata($session_data); //save it to session

					redirect('home/index'); // redirect to home
				} else {
					$this->session->set_flashdata('error', 'Unable to login now.'); //show message on database error
					redirect('account/login');
				}
			}
		}

		$this->load->view('account/header');
		$this->load->view('account/login');
		$this->load->view('account/footer');
	}

	public function check_login() { //validation function. this function checking is database contains user with requested email and password
		$email = set_value('email');
		$password = set_value('password');

		if(!$email && !$password) return FALSE;

		$result = $this->account_model->login_user($email, $password);
		switch ($result) {
			case 'logged_in':
				return TRUE;
				break;

			case 'incorrect_password':
				$this->form_validation->set_message('check_login', 'Incorrect password. Please try again');
				return FALSE;
				break;

			case 'not_activated';
				$this->form_validation->set_message('check_login', 'Account not activated');
				return FALSE;
				break;

			case 'email_not_found';
				$this->form_validation->set_message('check_login', 'Email not found. Please try again');
				return FALSE;
				break;
		}
	}
	
    public function reset_password(){
		$this->load->library('form_validation');
		$view_data = array();

		if ($this->session->userdata('logged_in')) {
			redirect('home');
		}

		$post = $this->input->post();
		if($post && $this->input->post('email', TRUE)) { //if data received
			
			$this->form_validation->set_rules('email', 'Email Address', 'trim|required|min_length[6]|max_length[50]|valid_email|xss_clean');
			
			if ($this->form_validation->run() == FALSE) {
				$view_data['error'] = 'Please supply a valid email address';
			}else{
				$email = set_value('email');
				$firstname = $this->account_model->email_exists($email);
				
				if ($firstname) {

					$email_code = md5($this->config->item('salt') . $firstname); //creating an email code which is unique for each user

					$message = '<!DOCTYPE html><meta charset=utf-8/>
					</head><body>';
					$message .= '<p> Dear '.$firstname.' ,</p>';
					$message .='<p> Please, <strong><a href="'.site_url('account/reset_password_form/'.str_replace('@', '...at...', $email).'/'.$email_code).
						'"> click here</a></strong> to reset your password</p>';
					$message .='<p> Thank you! From the Panna Daily Team </p>';
					$message .='</body></html>';

					send_email($email, 'Resetting your password.', $message);

					$this->session->set_flashdata('success', 'A link to reset your password has ben sent to '.$email.'.');
					redirect('account/login');
				}else{
					$view_data['error'] = 'This email address is not registered!';
				}
			}
		}

		$this->load->view('account/header');
		$this->load->view('account/reset_password', $view_data);
		$this->load->view('account/footer');
    }

	public function reset_password_form(){
		$email = $this->uri->segment(3);
		$email_code = $this->uri->segment(4);
		if(!($email && $email_code)) redirect('welcome');

		$email_code = trim($email_code); //incase user has whitespaces
		$email = str_replace('...at...', '@', $email); //changing "...at..." to "@", because "@" deprecated in URI and we use "...at..." instead "@" in links

		$email_hash = sha1($email . $email_code); //creating a unique hash for the email, so it matches the correct email and cannot be changed in the view
		$verified = $this->account_model->verify_reset_password_code($email, $email_code);
		if ($verified) {
			$this->load->view('account/header');
			$this->load->view('account/update_password', array('email_hash' => $email_hash, 'email_code' => $email_code, 'email' =>$email)); //sending the email hash, email code and the email to the view
			$this->load->view('account/footer');

		}else{
			$this->session->set_flashdata('error', 'There was an error with the link. Please request a new password.');
			redirect('account/reset_password');
		}
	}

	public function update_password(){
		$post = $this->input->post();
		if($post) {
			if($this->input->post('email', TRUE) && $this->input->post('email_hash', TRUE) && $this->input->post('email_code', TRUE)) { //checking received data
				$email = $this->input->post('email', TRUE);
				$email_hash = $this->input->post('email_hash', TRUE);
				$email_code= $this->input->post('email_code', TRUE);
				if($email_hash == sha1($email.$email_code)) { //checking hash

					//otherwise the form validation library is loaded, the rules are set
					$this->load->library('form_validation');

					$this->form_validation->set_rules('email_hash', 'Email Hash', 'trim|required');
					$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
					$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[50]|matches[password_conf]|xss_clean');
					$this->form_validation->set_rules('password_conf' , 'Confirmed Password', 'trim|required|min_length[6]|max_length[50]|xss_clean');

					if ($this->form_validation->run() === FALSE) {
						$this->load->view('account/header');
						$this->load->view('account/update_password', array('email_hash' => $email_hash, 'email_code' => $email_code, 'email' =>$email));
						$this->load->view('account/footer');
					}else{
						//the password update is successful and returns the user's firstname
						$password = sha1($this->config->item('salt') . $this->input->post('password', TRUE)); //password is recreated, running sha1 hash on it
						$result = $this->account_model->update_password($email, $password);

						if ($result) {
							$this->session->set_flashdata('success', 'Your password has now been updated, you may now login');
							redirect('account/login');
						}else{
							$this->session->set_flashdata('error', 'Database error. Please try again later');
							//redirect('account/reset_password');
						}
					}
				} else {
					$this->session->set_flashdata('error', 'Wrong data');
					redirect('account/reset_password');
				}
			} else {
				$this->session->set_flashdata('error', 'Empty request');
				redirect('account/reset_password');
			}
		} else {
			$this->session->set_flashdata('error', 'Wrong request');
			redirect('account/reset_password');
		}
	}

	public function logout(){
		$this->session->sess_destroy();
		redirect('account/login');
	}

	/* LOGIN END */

	/* REGISTRATION */
	public function register(){
		$this->load->library('form_validation');

		if ($this->session->userdata('logged_in')) {
			redirect('home');
		}

		$post = $this->input->post();
		if($post) {
			$this->form_validation->set_rules('firstname', 'First Name', 'trim|required|min_length[3]|max_length[14]|xss_clean');
			$this->form_validation->set_rules('lastname', 'Last Name', 'trim|required|min_length[2]|max_length[14]|xss_clean');
			$this->form_validation->set_rules('email', 'Email Address', 'trim|required|min_length[6]|max_length[50]|valid_email|is_unique[users.email]|xss_clean');
			$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[5]|max_length[20]|is_unique[users.username]|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[50]|matches[cpassword]|xss_clean');
			$this->form_validation->set_rules('cpassword', 'Confirmed Password', 'trim|required|min_length[6]|max_length[50]|xss_clean');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
					"firstname" => set_value('firstname'),
					"lastname" => set_value('lastname'),
					"email" => set_value('email'),
					"username" => set_value('username'),
					"password" => set_value('password')
				);

				$result = $this->account_model->insertuser($data);
				if ($result) {
					$userData = $this->account_model->get_user_data($data['email']); //if user added - get his data and save to session
					if($userData) {
						$sess_data = array(
							'userID' => $userData->userID,
							'firstname' => $userData->firstname,
							'lastname' => $userData->lastname,
							'email' => $userData->email,
							'username' => $userData->username,
							'logged_in' => 0
						);
						$email_code = md5((string)$userData->reg_time); //needed for email activation
						$this->session->set_userdata($sess_data);

						// SEND VALIDATION EMAIL //

						$message = '<!DOCTYPE html><meta charset=utf-8/></head><body>';
						$message .= '<p> Dear ' . $userData->firstname . ',</p>';
						$message .= '<p> Thank you for registering at Panna Daily. <strong><a href="' . site_url('account/validate_email/' . str_replace('@', '...at...', $data['email']) . '/' . $email_code) .
							'">Click here</a></strong> to activate your account. Once activated, you are then able to log in</p>';
						$message .= '<p> Thank you! From the Panna Daily Team </p>';
						$message .= '</body></html>';

						send_email($data['email'], 'Please activate your account', $message); //send_email function available at email_functions helper


						// SET NOTIFICATION MESSAGE AND REDIRECT //

						$this->session->set_flashdata('success', 'Thank you for registering, ' . $userData->username . '!<br/> An email has been sent to you to activate your account. Once activated, you can proceed to login.');
						redirect('account/login');
					} else {
						$this->session->set_flashdata('error', 'Server Database Error. Please contact administration');
						redirect('account/register');
					}
				}else{
					$this->session->set_flashdata('error', 'Server Database Error. Please contact administration');
					redirect('account/register');
				}
			}
		}

		$this->load->view('account/header');
		$this->load->view('account/register');
		$this->load->view('account/footer');
	}

	public function validate_email(){
		$email_address = $this->uri->segment(3);
		$email_code = $this->uri->segment(4);
		if(!($email_address && $email_code)) redirect('welcome');

		$email_code = trim($email_code); //incase user has whitespaces
		$email_address = str_replace('...at...', '@', $email_address); //changing "...at..." to "@", because "@" deprecated in URI and we use "...at..." instead "@" in links

		$validated = $this->account_model->validate_email($email_address, $email_code);
		if ($validated === true) {
			$this->session->set_flashdata('success', 'Your email address, '.$email_address.', has been validated<br/> You may now Login');
			redirect('account/login');
		} else {
			$this->session->set_flashdata('error', 'Wrong email or validation code');
			redirect('account/login');
		}
	}
	/* REGISTRATION END */
}
?>