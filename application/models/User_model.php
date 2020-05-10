<!-- 
	References that helped in the development of this application:
		https://www.pushpendra.net/how-to-make-a-blog-in-codeigniter/
-->
<?php
	class User_model extends CI_Model{
		public function register($enc_password){
			//User Data Array
			$data = array(
				'name' => $this->input->post('name'),
				'email' => $this->input->post('email'),
				'username' => $this->input->post('username'),
				'password' => $enc_password,
				'zipcode' => $this->input->post('zipcode')
			);

			//Insert User
			return $this->db->insert('users', $data);
			
		}

		//Log user in
		public function login($username, $password){
			//Validation
			$this->db->like('username', $username);
			$this->db->like('password', $password);

			$result = $this->db->get('users');

			if($result->num_rows() == 1){
				return $result->row(0)->id;
			}
			else {
				return false;
			}
		}

		// Check username exists
		public function check_username_exists($username){
			$query = $this->db->get_where('users', array('username' => $username));
			if(empty($query->row_array())){
				return true;
			}
			else {
				return false;
			}
		}
		
		// Check email exists
		public function check_email_exists($email){
			$query = $this->db->get_where('users', array('email' => $email));
			if(empty($query->row_array())){
				return true;
			}
			else {
				return false;
			}
		}
	}