<?php
class home extends CI_Controller{
	private $user;
	private $words_min;
	private $words_max;
	private $comments_words_min;
	private $comments_words_max;

	public function __construct(){
		parent::__construct();
		$this->load->model('home_model');
		$this->load->library('Curl');
		$this->load->library('typography');
		$this->words_min = 250; //article words limit - minimum
		$this->words_max = 1000; //article words limit - maximum
		$this->comments_words_min = 0; //article comments limit - minimum
		$this->comments_words_max = 100; //article comments limit - maximum

		if ($this->session->userdata('logged_in')) { //if user logged - save his data to session
			$email  = $this->session->userdata('email');
			$this->user = $this->home_model->get_user_details($email);
			if(!$this->user) {
				redirect('account/login');
			}
		} else { //otherwise - redirect to login page
			redirect('account/login');
		}
	}
	
	public function index(){
		redirect('home/timeline');
	}

	public function timeline()
	{
		$this->load->helper('text');

		
		
		$articles = $this->home_model->get_timeline($this->user->userID);

		$view_data = array(
			'user' => $this->user,
			'articles' => $articles
		);

		$this->load->view('home/header', $view_data);
		$this->load->view('home/panel_left', $view_data);
		$this->load->view('home/timeline', $view_data);
		$this->load->view('home/panel_right', $view_data);
		$this->load->view('home/footer', $view_data);
	}

	public function new_article()
	{
		$this->load->library('form_validation');
		$upload_error = false;

		$post = $this->input->post();
		if($post) { //if data received - try to add new article
			$this->form_validation->set_rules('article' , 'Article', 'trim|required|xss_clean|callback_words_count');

			if ($this->form_validation->run() == TRUE) {

				if(isset($_FILES) && count($_FILES) > 0 && $_FILES['file'] && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) { //if file attached - try to save file

					$config['upload_path'] = FCPATH . 'images/articles/';
					$config['allowed_types'] = 'gif|jpg|png';
					$config['max_size'] = '1024';
					$config['max_width'] = '1024';
					$config['max_height'] = '768';
					$config['encrypt_name'] = TRUE;

					$this->load->library('upload', $config);

					if (!$this->upload->do_upload('file')) {
						$upload_error = $this->upload->display_errors();
					} else {
						$img_data = $this->upload->data();

						$data = array(
							'user_id' => $this->user->userID,
							'title' => set_value('title'),
							'text' => set_value('article'),
							'image' => $img_data['file_name']
						);
					}
				} else { //...if not - save article without image
					$data = array(
						'user_id' => $this->user->userID,
						'title' => set_value('title'),
						'text' => set_value('article'),
						'image' => null
					);
				}

				if(isset($data) && count($data) == 4) {
					if ($this->home_model->add_article($data)) {
						$this->session->set_flashdata('success', 'New article successfully added');
						redirect('home/timeline');
					}
				}
			}
		}

		$view_data = array(
			'user' => $this->user,
			'words_min' => $this->words_min,
			'words_max' => $this->words_max,
			'upload_error' => $upload_error
		);

		$this->load->view('home/header', $view_data);
		$this->load->view('home/new_article', $view_data);
		$this->load->view('home/footer', $view_data);
	}

	public function words_count() //get words count in text - form validation function
	{
		$text = set_value('article');
		$count = str_word_count($text);

		if($count < $this->words_min || $count > $this->words_max) {
			$this->form_validation->set_message('words_count', 'Allowed only articles with a number of words from '.$this->words_min.' to '.$this->words_max.'.');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function profile(){ //show profile details and timeline
		$user_id = $this->uri->segment(3);
		if(!$user_id) $user_id = $this->user->userID; //if no user received - use current user

		$this->load->helper('text');

		$user_details = $this->home_model->get_user_details_by_id($user_id); //get details
		if(!$user_details) redirect('home');
		$articles = $this->home_model->get_user_articles($user_id); //get articles
		if($this->user->userID != $user_id) {
			$is_follower = $this->home_model->is_follower($this->user->userID, $user_id); //get following information if user not current user
		} else {
			$is_follower = false;
		}

		$view_data = array(
			'user' => $this->user,
			'user_details' => $user_details,
			'articles' => $articles,
			'is_follower' => $is_follower
		);

		$this->load->view('home/header', $view_data);
		$this->load->view('home/panel_profile_left', $view_data);
		$this->load->view('home/timeline', $view_data);
		$this->load->view('home/panel_right', $view_data);
		$this->load->view('home/footer', $view_data);
	}

	public function like() //add like to articel
	{
		$article_id = $this->uri->segment(3);
		if(!$article_id) redirect('home/timeline');

		if(!$this->home_model->is_liked($this->user->userID, $article_id)) {
			if($this->home_model->like($this->user->userID, $article_id)) {
				$this->session->set_flashdata('success', 'You liked this article');
			} else {
				$this->session->set_flashdata('error', 'Database Error');
			}
		} else {
			$this->session->set_flashdata('error', 'You already liked this article');
		}

		redirect('home/article/'.$article_id);
	}

	public function dislike() //remove like from article
	{
		$article_id = trim($this->uri->segment(3));
		if(!$article_id) redirect('home/timeline');

		if($this->home_model->is_liked($this->user->userID, $article_id)) {
			if($this->home_model->dislike($this->user->userID, $article_id)) {
				$this->session->set_flashdata('success', 'You remove your liked to this article');
			} else {
				$this->session->set_flashdata('error', 'Database Error');
			}
		} else {
			$this->session->set_flashdata('error', 'You can\'t dislike this article');
		}
		redirect('home/article/'.$article_id);
	}

	public function article() //view article
	{
		$article_id = $this->uri->segment(3);
		if(!$article_id) redirect('home/timeline');

		$article = $this->home_model->get_article($this->user->userID, $article_id);

		if(!$article) redirect('home');

		$view_data = array(
			'user' => $this->user,
			'article' => $article
		);

		$this->load->view('home/header', $view_data);
		$this->load->view('home/article', $view_data);
		$this->load->view('home/article_panel_right', $view_data);
		$this->load->view('home/footer', $view_data);
	}

	public function comments() //view article comments
	{
		$article_id = $this->uri->segment(3);
		if(!$article_id) redirect('home/timeline');

		$article = $this->home_model->get_article($this->user->userID, $article_id);
		$comments = $this->home_model->get_comments($article_id);

		if(!$article) redirect('home');

		$view_data = array(
			'user' => $this->user,
			'article' => $article,
			'comments' => $comments,
			'comment_words_min' => $this->comments_words_min,
			'comment_words_max' => $this->comments_words_max
		);

		$this->load->view('home/header', $view_data);
		$this->load->view('home/comments', $view_data);
		$this->load->view('home/article_panel_right', $view_data);
		$this->load->view('home/footer', $view_data);
	}

	public function add_comment() //try to add article comment
	{
		$article_id = $this->uri->segment(3);
		if(!$article_id) redirect('home/timeline');
		$article = $this->home_model->get_article($this->user->userID, $article_id);
		if(!$article) redirect('home');

		$post = $this->input->post();
		if($post) {
			$this->load->library('form_validation');

			$this->form_validation->set_rules('comment' , 'Comment', 'trim|required|xss_clean|callback_comments_words_count');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
					'user_id' => $this->user->userID,
					'article_id' => $article_id,
					'text' => set_value('comment')
				);
				if ($this->home_model->add_comment($data)) {
					$this->session->set_flashdata('success', 'Your comment added to article.');
				} else {
					$this->session->set_flashdata('error', 'Database problem');
				}
			} else {
				$this->session->set_flashdata('error', validation_errors());
			}
		}

		redirect('home/comments/'.$article_id);

	}

	public function comments_words_count() //check number if words in comments - validation function
	{
		$text = set_value('comment');
		$count = str_word_count($text);

		if($count < $this->comments_words_min || $count > $this->comments_words_max) {
			$this->form_validation->set_message('comments_words_count', 'Allowed only comments with a number of words from '.$this->comments_words_min.' to '.$this->comments_words_max.'.');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function remove() //remove article
	{
		$article_id = $this->uri->segment(3);
		if(!$article_id) redirect('home/timeline');
		$article = $this->home_model->get_article($this->user->userID, $article_id);
		if(!$article) redirect('home');

		if ($this->home_model->remove_article($this->user->userID, $article_id)) { //try to remove article. works only if active user - article author

			if($article->image) { //remove image if available
				unlink(FCPATH . 'images/articles/'.$article->image);
			}

			$this->session->set_flashdata('success', 'Your article successfully removed');
		} else {
			$this->session->set_flashdata('error', 'Database problem');
		}

		redirect('home/timeline');
	}

	public function remove_comment() //try to remove comment
	{
		$article_id = $this->uri->segment(3);
		$comment_id = $this->uri->segment(4);
		if(!($article_id&& $comment_id)) redirect('home/timeline');
		$article = $this->home_model->get_article($this->user->userID, $article_id);
		if(!$article) redirect('home');

		if ($this->home_model->remove_comment($this->user->userID, $article_id, $comment_id)) { //try to remove comment. works only if active user - comment author
			$this->session->set_flashdata('success', 'Your comment successfully removed');
		} else {
			$this->session->set_flashdata('error', 'Database problem');
		}

		redirect('home/comments/'.$article_id);
	}

	public function search()
	{
		$this->load->helper('text');

		$articles = array();

		$post = $this->input->post();
		if($post) { //if request received - get articles using filter
			$query = $this->input->post('query', TRUE);
			if($query) {
				$articles = $this->home_model->search($this->user->userID, $query);
			} else {
				$this->session->set_flashdata('error', 'Empty Request');
				redirect('home/search');
			}
		}

		$view_data = array(
			'user' => $this->user,
			'articles' => $articles
		);

		$this->load->view('home/header', $view_data);
		$this->load->view('home/search', $view_data);
		$this->load->view('home/panel_right', $view_data);
		$this->load->view('home/footer', $view_data);
	}

	public function follow() // add following data
	{
		$user_id = $this->uri->segment(3);
		if(!$user_id) redirect('home');
		if($user_id == $this->user->userID) redirect('home');
		$user_details = $this->home_model->get_user_details_by_id($user_id);
		if(!$user_details) redirect('home');

		$is_follower = $this->home_model->is_follower($this->user->userID, $user_id); //checking is already following or not
		if($is_follower) {
			$this->session->set_flashdata('error', 'You already following this user.');
		} else {
			if($this->home_model->follow($this->user->userID, $user_id)) {
				$this->session->set_flashdata('success', 'You start following this user');
			} else {
				$this->session->set_flashdata('error', 'Database error');
			}
		}

		redirect('home/profile/'.$user_id);
	}

	public function unfollow() //remove following data
	{
		$user_id = $this->uri->segment(3);
		if(!$user_id) redirect('home');
		if($user_id == $this->user->userID) redirect('home');
		$user_details = $this->home_model->get_user_details_by_id($user_id);
		if(!$user_details) redirect('home');

		$is_follower = $this->home_model->is_follower($this->user->userID, $user_id); //checking is already following or not
		if(!$is_follower) {
			$this->session->set_flashdata('error', 'You are not following this user.');
		} else {
			if($this->home_model->unfollow($this->user->userID, $user_id)) {
				$this->session->set_flashdata('success', 'You have unfollowed this user');
			} else {
				$this->session->set_flashdata('error', 'Database error');
			}
		}

		redirect('home/profile/'.$user_id);
	}

	public function leagues(){

		$view_data = array(
			'user' => $this->user
		);
		$this->load->view('home/header', $view_data);
		$this->load->view('home/leagues', $view_data);

		/* */
	}
	/* Get table by League ID */
	public function getTable(){
		$leagueId = $_GET['leagueId'];
		if(isset($leagueId)){
			$this->curl->create('http://api.football-data.org/v1/soccerseasons/'.$leagueId.'/leagueTable');
			$tableData = $this->curl->execute();
			$data = json_decode($tableData);
			$standing = $data->standing; 
			$leagueCaption = $data->leagueCaption;
			$table = $this->load->view('home/table',array("standing" => $standing, "caption"=> $leagueCaption));
			return $table;
		}
	}
	/* Create Live Results block by League ID */
	public function getResults(){
		$leagueId = $_GET['leagueId'];
		$pastLimit = 'p10';
		if(isset($leagueId)){
			$this->curl->create('http://api.football-data.org/v1/soccerseasons/'.$leagueId.'/fixtures?timeFrame='.$pastLimit);
			$past = json_decode($this->curl->execute());
			if($past){
				$pastData = $past->fixtures;
				foreach($pastData as $item){
					$date = substr($item->date,0,10);
					$categories[$date][] = $item;
				}	
			}
			return $this->load->view('home/fixturesResults',array('categories'=>$categories));
		}
	}
	/* Create Fixtures & Results block by League ID */
	public function getFixtures(){
		$leagueId = $_GET['leagueId'];
		$feautureLimit = 'n7';
		$categories = array();
		$today = date("Y-m-d");
		if(isset($leagueId)){
			$this->curl->create('http://api.football-data.org/v1/soccerseasons/'.$leagueId.'/fixtures?timeFrame='.$feautureLimit);
			$feauture = json_decode($this->curl->execute());
			if($feauture){
				$feautureData = $feauture->fixtures;
				foreach($feautureData as $item){
					$date = substr($item->date,0,10);
					if($date == $today){
						$item->time = substr($item->date,11,15);	
					}
					$categories[$date][] = $item;
				}	
			}
			$fixtuessResults = $this->load->view('home/fixturesResults',array('categories'=>$categories,'today' => $today));
			return $fixtuessResults;
		}
	}
	public function edit_profile() //edit profile page
	{
		$upload_error = false;
		$this->load->library('form_validation');

		$post = $this->input->post();
		if($post) { //if request received
			$action = $this->input->post('action', TRUE);
			if($action) { //get action - change info or password
				switch($action){
					case 'password':
						$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[50]|matches[password2]|xss_clean');
						$this->form_validation->set_rules('password2', 'Confirmed Password', 'trim|required|min_length[6]|max_length[50]|xss_clean');

						if ($this->form_validation->run() == TRUE) {
							$data = array(
								"password" => sha1($this->config->item('salt') . set_value('password'))
							);

							if($this->home_model->update_user($this->user->userID, $data)) {
								$this->session->set_flashdata('success', 'Profile password updated');
							} else {
								$this->session->set_flashdata('error', 'Database Error');
							}

							redirect('home/edit_profile');
						}

						break;
					case 'info':
						$this->form_validation->set_rules('firstname', 'First Name', 'trim|required|min_length[3]|max_length[14]|xss_clean');
						$this->form_validation->set_rules('lastname', 'Last Name', 'trim|required|min_length[2]|max_length[14]|xss_clean');

						if ($this->form_validation->run() == TRUE) {

							if(isset($_FILES) && count($_FILES) > 0 && $_FILES['file'] && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) { //try to upload user image

								$config['upload_path'] = FCPATH . 'images/users/';
								$config['allowed_types'] = 'gif|jpg|png';
								$config['max_size'] = '1024'; //kb
								$config['max_width'] = '512'; //px
								$config['max_height'] = '512'; //px
								$config['encrypt_name'] = TRUE;

								$this->load->library('upload', $config);

								if (!$this->upload->do_upload('file')) {
									$upload_error = $this->upload->display_errors();
								} else {
									$img_data = $this->upload->data();

									if(file_exists(FCPATH . 'images/users/'.$this->user->image)) {
										unlink(FCPATH . 'images/users/'.$this->user->image);
									}

									$data = array(
										"firstname" => set_value('firstname'),
										"lastname" => set_value('lastname'),
										'image' => $img_data['file_name']
									);
								}
							} else {
								$data = array(
									"firstname" => set_value('firstname'),
									"lastname" => set_value('lastname')
								);
							}

							if(isset($data) && count($data) == 3) {
								if($this->home_model->update_user($this->user->userID, $data)) {
									$this->session->set_flashdata('success', 'Profile Details updated');
								} else {
									$this->session->set_flashdata('error', 'Database Error');
								}

								redirect('home/edit_profile');
							}
						}
						break;
				}
			}
		}

		$view_data = array(
			'user' => $this->user,
			'upload_error' => $upload_error
		);

		$this->load->view('home/header', $view_data);
		$this->load->view('home/edit_profile', $view_data);
		$this->load->view('home/panel_right', $view_data);
		$this->load->view('home/footer', $view_data);
	}


}
?>