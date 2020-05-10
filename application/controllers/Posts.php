<!-- 
	References that helped in the development of this application:
		https://www.pushpendra.net/how-to-make-a-blog-in-codeigniter/
-->
<?php
	class Posts extends CI_Controller{
		public function index(){
			
			$data['title'] = 'Latest Posts';

			$data['posts'] = $this->post_model->get_posts();
				//print_r($data['posts']); //tests if the data is being passed through 

			$this->load->view('templates/header');
			$this->load->view('posts/index', $data);
			$this->load->view('templates/footer');
		}

		public function view($slug = NULL){
			$data['post'] = $this->post_model->get_posts($slug);
			$post_id = $data['post']['id'];
			$data['comments'] = $this->comment_model->get_comments($post_id);

			if(empty($data['post'])){
				show_404();
			}

			$data['title'] = $data['post']['title'];

			$this->load->view('templates/header');
			$this->load->view('posts/view', $data);
			$this->load->view('templates/footer');
		}

		public function create(){
			//Check if user is logged in
			if(!$this->session->userdata('logged_in')){
				redirect('users/login');
			}

			$this->load->helper('form');
			$this->load->library('form_validation');

			$data['title'] = 'Create Post';

			$data['categories'] = $this->post_model->get_categories();

			$this->form_validation->set_rules('title', 'Title', 'required');
			$this->form_validation->set_rules('body', 'Body', 'required');

			if($this->form_validation->run() === FALSE){
				$this->load->view('templates/header');
				$this->load->view('posts/create', $data);
				$this->load->view('templates/footer');

			} else {
				
				$this->post_model->create_post($post_image);

				//Set Message
				$this->session->set_flashdata('post_created', 'Your post has been successfully created!');

				redirect('posts');
			}

			
		}

		public function delete($id){
			//Check if user is logged in
			if(!$this->session->userdata('logged_in')){
				redirect('users/login');
			}

			$this->post_model->delete_post($id);

			//Set Message
				$this->session->set_flashdata('post_deleted', 'Your post has been deleted!');

			redirect('posts'); 
		}

		public function edit($slug){
			//Check if user is logged in
			if(!$this->session->userdata('logged_in')){
				redirect('users/login');
			}

			$data['post'] = $this->post_model->get_posts($slug);

			//Check user is logged in and has right to carry this out
			if($this->session->userdata('user_id') != $this->post_model->get_posts($slug)['user_id']){
				redirect('posts');
			}

			$data['categories'] = $this->post_model->get_categories();

			if(empty($data['post'])){
				show_404();
			}

			$data['title'] = 'Edit Post';

			$this->load->view('templates/header');
			$this->load->view('posts/edit', $data);
			$this->load->view('templates/footer');
		}

		public function update(){
			//Check if user is logged in
			if(!$this->session->userdata('logged_in')){
				redirect('users/login');
			}
			
			$this->post_model->update_post();

			//Set Message
				$this->session->set_flashdata('post_updated', 'Your post has been successfully updated!');

			redirect('posts');
		}
	}