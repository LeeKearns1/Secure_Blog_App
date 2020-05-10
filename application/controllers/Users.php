<!-- 
	References that helped in the development of this application:
		https://codereview.stackexchange.com/questions/226795/codeigniter-3-registration-and-login-system
		https://stackoverflow.com/questions/32502446/codeigniter-form-validation-setting-strong-password-validation-rule-in-an-array/32504388
		https://www.pushpendra.net/how-to-make-a-blog-in-codeigniter/
-->
<?php 
	class Users extends CI_Controller{
		//Register User
		public function register(){
			$data['title'] = 'Sign Up';

			$this->form_validation->set_rules('name', 'Name', 'trim|min_length[2]|required');
			$this->form_validation->set_rules('username', 'Username', 'trim|required|callback_check_username_exists|min_length[6]|max_length[20]');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|callback_check_email_exists');
			$this->form_validation->set_rules('password', 'Password', 'required|callback_valid_password');
			$this->form_validation->set_rules('password2', 'Confirm Password', 'matches[password]');


			if($this->form_validation->run() === FALSE){
				$this->load->view('templates/header');
				$this->load->view('users/register', $data);
				$this->load->view('templates/footer');

			} else {
				//Encrypt Password - goes in controller because of MVC guidelines 
				$enc_password = md5($this->input->post('password'));

				$this->user_model->register($enc_password);

				//Set Message
				$this->session->set_flashdata('user_registered', 'You are now registered and can login!');

				redirect('posts');
			}
		}

		//Strong Password Validation - Callback Function
	public function valid_password($password = ''){
		$password = trim($password);

		$regex_lowercase = '/[a-z]/';
		$regex_uppercase = '/[A-Z]/';
		$regex_number = '/[0-9]/';
		$regex_special = '/[!@#$%^&*()\-_=+{};:,<.>ยง~]/';

		if (empty($password)){
			$this->form_validation->set_message('valid_password', 'The {field} field is required.');

			return FALSE;
		}

		if (preg_match_all($regex_lowercase, $password) < 1){
			$this->form_validation->set_message('valid_password', 'The {field} field must have at least one lowercase letter.');

			return FALSE;
		}

		if (preg_match_all($regex_uppercase, $password) < 1){
			$this->form_validation->set_message('valid_password', 'The {field} field must have at least one uppercase letter.');

			return FALSE;
		}

		if (preg_match_all($regex_number, $password) < 1){
			$this->form_validation->set_message('valid_password', 'The {field} field must have at least one number.');

			return FALSE;
		}

		if (preg_match_all($regex_special, $password) < 1){
			$this->form_validation->set_message('valid_password', 'The {field} field must have at least one special character.' . ' ' . htmlentities('!@#$%^&*()\-_=+{};:,<.>ยง~'));

			return FALSE;
		}

		if (strlen($password) < 8){
			$this->form_validation->set_message('valid_password', 'The {field} field must be at least 8 characters in length.');

			return FALSE;
		}

		if (strlen($password) > 32){
			$this->form_validation->set_message('valid_password', 'The {field} field cannot exceed 32 characters in length.');

			return FALSE;
		}

		return TRUE;
	}
	//Strong Password Validation End

		//Login User
		public function login(){
			$data['title'] = 'Sign In';

			$this->form_validation->set_rules('username', 'Username', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');

			if($this->form_validation->run() === FALSE){
				$this->load->view('templates/header');
				$this->load->view('users/login', $data);
				$this->load->view('templates/footer');

			} else {
				//Get Username
				$username = $this->input->post('username');
				//Get Encrypted password
				$password = md5($this->input->post('password'));

				//Login user
				$user_id = $this->user_model->login($username, $password);

				if($user_id){
					//Create session
					$user_data = array(
						'user_id' => $user_id,
						'username' => $username,
						'logged_in' => true
					);

					$this->session->set_userdata($user_data); //access to user data whenever I want it

					//Set Message
				$this->session->set_flashdata('user_loggedin', 'You are now logged in!');

				redirect('posts');
				} 
				else{
						//Set Message
					$this->session->set_flashdata('login_failed', 'Username or Password is Invalid!');

					redirect('users/login');
				}				
			}
		}

		//Log Out
		public function logout(){
			//Unset user data
			$this->session->unset_userdata('logged_in');
			$this->session->unset_userdata('user_id');
			$this->session->unset_userdata('username');

			//Set Message
				$this->session->set_flashdata('user_loggedout', 'You are now Logged Out!');

				redirect('users/login');
		}

		// Check if username exists
		public function check_username_exists($username){
			$this->form_validation->set_message('check_username_exists', 'That username is already in use. Please choose another one!');

			if($this->user_model->check_username_exists($username)){
				return true;
			}
			else {
				return false;
			}
		}
		
		// Check if email exists
		public function check_email_exists($email){
			$this->form_validation->set_message('check_email_exists', 'That email is already in use. Please choose another one!');

			if($this->user_model->check_email_exists($email)){
				return true;
			}
			else {
				return false;
			}
		}
	}