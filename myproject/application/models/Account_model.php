<?php

class account_model extends CI_Model{

	public function __construct(){
		parent:: __construct();
		$this->load->database();
	}

	/* LOGIN */
	public function login_user($email, $password){
		$sql = "SELECT userID, activated, password FROM users WHERE email ='" .$email. "' LIMIT 1 ";
		$result = $this->db->query($sql);
		$row = $result->row();

		if ($result->num_rows() == 1) {
			if ($row->activated) {
				if ($row->password === sha1($this->config->item('salt').$password)) { //if user available and password same -> return logged in
					return 'logged_in';
				}
				else{
					return 'incorrect_password';
				}
			}else{
				return 'not_activated';
			}

		}else{
			return 'email_not_found';
		}
	}

	public function email_exists($email){
		$sql = "SELECT firstname, email FROM users WHERE email = '{$email}' LIMIT 1";
		$result = $this->db->query($sql);
		$row = $result->row();
		return ($result->num_rows() === 1 && $row->email) ? $row->firstname : false; //if num of rows returned contains the email, then the firstname is returned, else false is returned.
	}


	public function verify_reset_password_code($email, $code){
		$sql = "SELECT firstname, email FROM users WHERE email = '{$email}' LIMIT 1";
		$result = $this->db->query($sql);
		$row = $result->row();

		if ($result->num_rows() === 1) {
			return ($code == md5($this->config->item('salt') . $row->firstname)) ? true:false; //Checking whether the hashed email code is the same as in the controller, if true returns true, else returns false
		}else{
			return false;
		}
	}

	public function update_password($email, $password){
		$sql = "UPDATE users SET password = '{$password}' WHERE email = '{$email}' LIMIT 1 "; //update their new password where email is equal to the user email
		if ($this->db->query($sql)) {
			return true;
		}else{
			return false;
		}
	}
	/* LOGIN END */

	/* REGISTRATION */
	public function insertuser($data){

		$firstname = $data['firstname'];
		$lastname = $data['lastname'];
		$email = $data['email'];
		$username = $data['username'];
		$password = sha1($this->config->item('salt') . $data['password']); //accessed from config salt key in config file for password protection

		//sql query is run
		$sql = "INSERT INTO users (firstname, lastname, email, username, password)
		        VALUES (". $this->db->escape($firstname) .",
		        		". $this->db->escape($lastname) . ",
		        		'". $email . "',
		        		". $this->db->escape($username) . ",
		        		'". $password . "')";
		$result = $this->db->query($sql);

		if ($result && $this->db->affected_rows() === 1) { // Displays the number of affected rows during write queries, (Insert, update, etc).
			return true;
		} else {
			if (isset($email)) {
				send_email_to_administrator('Error, Problem inserting into the database', 'Unable to register with the email'.$email.'');
			} else {
				send_email_to_administrator('Error, Problem inserting into the database', 'Database error, cannot insert user');
			}
			return false;
		}
	}

	public function get_user_data($email){
		$sql = "SELECT * FROM users WHERE email = '" .$email."' LIMIT 1"; //reg_time is selected to send a private email to the user for verification
		$result = $this->db->query($sql);
		if($result) {
			return $result->row();
		} else {
			return false;
		}
	}

	public function validate_email($email_address, $email_code){
		$sql = "SELECT email, reg_time, firstname FROM users WHERE email = '" .$email_address. "' LIMIT 1";
		$result = $this->db->query($sql);
		$row = $result->row(); //check is email exists

		if ($row && $result->num_rows() === 1 && md5((string)$row->reg_time) === $email_code) { //check code

			$sql = "UPDATE users SET activated = 1 WHERE email = '" .$email_address. "' LIMIT 1"; //if all correct - set activated to TRUE
			$this->db->query($sql);
			if ($this->db->affected_rows() === 1){
				return true;
			}else{
				return false;
			}

		} else {
			return false;
		}
	}
	/* REGISTRATION END */
}
?>