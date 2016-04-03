<?php

class home_model extends CI_Model{

	public function __construct(){
		parent:: __construct();
		$this->load->database();
	}

	/**
	 * Get user details using email
	 * @param $email
	 * @return bool
	 */
	public function get_user_details($email) {
		$sql = "SELECT *, (SELECT COUNT(*) FROM followers WHERE followers.follower_id = users.userID) as following, (SELECT COUNT(*) FROM followers WHERE followers.following_id = users.userID) as followers, (SELECT COUNT(*) FROM articles WHERE articles.user_id = users.userID) as articles FROM users WHERE email = '$email' LIMIT 1;";
		$result = $this->db->query($sql);
		if($result) {
			return $result->row();
		} else {
			return false;
		}
	}

	/**
	 * Get user's details using ID
	 * @param $id
	 * @return bool
	 */
	public function get_user_details_by_id($id) {
		$sql = "SELECT *, (SELECT COUNT(*) FROM followers WHERE followers.follower_id = users.userID) as following, (SELECT COUNT(*) FROM followers WHERE followers.following_id = users.userID) as followers, (SELECT COUNT(*) FROM articles WHERE articles.user_id = users.userID) as articles FROM users WHERE userID = '$id' LIMIT 1;";
		$result = $this->db->query($sql);
		if($result) {
			return $result->row();
		} else {
			return false;
		}
	}

	/**
	 * Check following data
	 * @param $follower_id
	 * @param $following_id
	 * @return bool
	 */
	public function is_follower($follower_id, $following_id){
		$sql = "SELECT COUNT(*) as total FROM followers WHERE follower_id = '$follower_id' AND following_id = '$following_id' LIMIT 1;";
		$result = $this->db->query($sql);
		if($result) {
			$row = $result->row();
			if($row->total == 1) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	/**
	 * Add following data
	 * @param $follower_id
	 * @param $following_id
	 * @return mixed
	 */
	public function follow($follower_id, $following_id){
		$sql = "INSERT INTO followers(follower_id, following_id) VALUES ('$follower_id', '$following_id');";
		return $this->db->query($sql);
	}

	/**
	 * Remove following data
	 * @param $follower_id
	 * @param $following_id
	 * @return mixed
	 */
	public function unfollow($follower_id, $following_id){
		$sql = "DELETE FROM followers WHERE follower_id = '$follower_id' AND following_id = '$following_id' LIMIT 1;";
		return $this->db->query($sql);
	}

	/**
	 * Add new articel
	 * @param $data
	 * @return mixed
	 */
	public function add_article($data) {
		$this->db->set(array( //here I used codeigniter query helper to build insert query
			'user_id' => $data['user_id'],
			'title' => $data['title'],
			'text' => $data['text'],
			'image' => $data['image']
		));
		return $this->db->insert('articles');
	}

	/**
	 * Get timeline for user. Also collected likes, comments number, is liked buy user
	 * @param $user_id
	 * @return bool
	 */
	public function get_timeline($user_id)
	{
		$sql = "SELECT
			a.*,
			u.username,
			DATE_FORMAT(a.date, ' %D %b %Y, %h:%i %p') AS date_formatted,
			(SELECT COUNT(*) FROM comments WHERE article_id = a.article_id) AS comments_total,
			(SELECT COUNT(*) FROM likes WHERE article_id = a.article_id) AS likes_total,
			(SELECT COUNT(*) FROM likes WHERE article_id = a.article_id AND user_id = '$user_id' LIMIT 1) AS liked
		  FROM
		  	articles a,
		  	users u
		  WHERE a.user_id = u.userID
		  AND (a.user_id =  '$user_id' OR a.user_id IN (SELECT following_id FROM followers WHERE follower_id = '$user_id'))
		  ORDER BY a.date DESC;";
		$result = $this->db->query($sql);
		if($result) {
			return $result->result();
		} else {
			return false;
		}
	}

	/**
	 * Get all articles on site
	 * @param $user_id
	 * @return bool
	 */
	public function get_articles($user_id)
	{
		$sql = "SELECT
			a.*,
			u.username,
			DATE_FORMAT(a.date, ' %D %b %Y, %h:%i %p') AS date_formatted,
			(SELECT COUNT(*) FROM comments WHERE article_id = a.article_id) AS comments_total,
			(SELECT COUNT(*) FROM likes WHERE article_id = a.article_id) AS likes_total,
			(SELECT COUNT(*) FROM likes WHERE article_id = a.article_id AND user_id = '$user_id' LIMIT 1) AS liked
		  FROM
		  	articles a,
		  	users u
		  WHERE a.user_id = u.userID
		  ORDER BY a.date DESC;";
		$result = $this->db->query($sql);
		if($result) {
			return $result->result();
		} else {
			return false;
		}
	}

	/**
	 * Search for article by article keywords or author username
	 * @param $user_id
	 * @param $filter
	 * @return bool
	 */
	public function search($user_id, $filter)
	{
		$sql = "SELECT
			a.*,
			u.username,
			DATE_FORMAT(a.date, ' %D %b %Y, %h:%i %p') AS date_formatted,
			(SELECT COUNT(*) FROM comments WHERE article_id = a.article_id) AS comments_total,
			(SELECT COUNT(*) FROM likes WHERE article_id = a.article_id) AS likes_total,
			(SELECT COUNT(*) FROM likes WHERE article_id = a.article_id AND user_id = '$user_id' LIMIT 1) AS liked
		  FROM
		  	articles a,
		  	users u
		  WHERE a.user_id = u.userID
		  AND (a.text LIKE '%$filter%' OR u.username LIKE '%$filter%')
		  ORDER BY a.date DESC;";
		$result = $this->db->query($sql);
		if($result) {
			return $result->result();
		} else {
			return false;
		}
	}

	/**
	 * Get user's articles only
	 * @param $user_id
	 * @return bool
	 */
	public function get_user_articles($user_id)
	{
		$sql = "SELECT
			a.*,
			u.username,
			DATE_FORMAT(a.date, ' %D %b %Y, %h:%i %p') AS date_formatted,
			(SELECT COUNT(*) FROM comments WHERE article_id = a.article_id) AS comments_total,
			(SELECT COUNT(*) FROM likes WHERE article_id = a.article_id) AS likes_total,
			(SELECT COUNT(*) FROM likes WHERE article_id = a.article_id AND user_id = '$user_id' LIMIT 1) AS liked
		  FROM
		  	articles a,
		  	users u
		  WHERE a.user_id = '$user_id' AND a.user_id = u.userID
		  ORDER BY a.date DESC;";
		$result = $this->db->query($sql);
		if($result) {
			return $result->result();
		} else {
			return false;
		}
	}

	/**
	 * Check is article liked by user
	 * @param $user_id
	 * @param $article_id
	 * @return bool
	 */
	public function is_liked($user_id, $article_id) {
		$sql = "SELECT COUNT(*) as total FROM likes WHERE article_id = '$article_id' AND user_id = '$user_id' LIMIT 1;";
		$result = $this->db->query($sql);
		if($result) {
			return $result->row()->total;
		} else {
			return false;
		}
	}

	/**
	 * Add like data
	 * @param $user_id
	 * @param $article_id
	 * @return mixed
	 */
	public function like($user_id, $article_id) {
		return $this->db->query("INSERT INTO likes(user_id, article_id) VALUES ('$user_id', '$article_id');");
	}

	/**
	 * Remove like data
	 * @param $user_id
	 * @param $article_id
	 * @return mixed
	 */
	public function dislike($user_id, $article_id) {
		return $this->db->query("DELETE FROM likes WHERE user_id = '$user_id' AND article_id = '$article_id' LIMIT 1;");
	}

	/**
	 * Get article text and details
	 * @param $user_id
	 * @param $article_id
	 * @return bool
	 */
	public function get_article($user_id, $article_id)
	{
		$sql = "SELECT
			a.*,
			u.username,
			DATE_FORMAT(a.date, ' %D %b %Y, %h:%i %p') AS date_formatted,
			(SELECT COUNT(*) FROM comments WHERE article_id = a.article_id) AS comments_total,
			(SELECT COUNT(*) FROM likes WHERE article_id = a.article_id) AS likes_total,
			(SELECT COUNT(*) FROM likes WHERE article_id = a.article_id AND user_id = '$user_id' LIMIT 1) AS liked
		  FROM
		  	articles a,
		  	users u
		  WHERE a.user_id = u.userID AND a.article_id = '$article_id' LIMIT 1;";
		$result = $this->db->query($sql);
		if($result) {
			return $result->row();
		} else {
			return false;
		}
	}

	/**
	 * Get article comments
	 * @param $article_id
	 * @return bool
	 */
	public function get_comments($article_id)
	{
		$sql = "SELECT
			c.*,
			u.username,
			DATE_FORMAT(c.date, ' %D %b %Y, %h:%i %p') AS date_formatted
		  FROM
		  	comments c,
		  	users u
		  WHERE c.user_id = u.userID AND c.article_id = '$article_id'
		  ORDER BY date ASC;";
		$result = $this->db->query($sql);
		if($result) {
			return $result->result();
		} else {
			return false;
		}
	}

	/**
	 * Add comment
	 * @param $data
	 * @return mixed
	 */
	public function add_comment($data) {
		$this->db->set($data);
		return $this->db->insert('comments');
	}

	/**
	 * Remove article if user - article owner
	 * @param $user_id
	 * @param $article_id
	 * @return bool
	 */
	public function remove_article($user_id, $article_id) {
		$sql1 = "DELETE FROM articles WHERE user_id = '$user_id' AND article_id = '$article_id' LIMIT 1;";
		$sql2 = "DELETE FROM comments WHERE article_id = '$article_id';";
		$sql3 = "DELETE FROM likes WHERE article_id = '$article_id';";
		return $this->db->query($sql1) && $this->db->query($sql2) && $this->db->query($sql3);
	}

	/**
	 * Remove comment if user - comment owner
	 * @param $user_id
	 * @param $article_id
	 * @param $comment_id
	 * @return mixed
	 */
	public function remove_comment($user_id, $article_id, $comment_id) {
		$sql = "DELETE FROM comments WHERE user_id = '$user_id' AND article_id = '$article_id' AND comment_id = '$comment_id' LIMIT 1;";
		return $this->db->query($sql);
	}

	/**
	 * Update user information
	 * @param $userID
	 * @param $data
	 * @return mixed
	 */
	public function update_user($userID, $data)
	{
		$this->db->where('userID', $userID);
		return $this->db->update('users', $data);
	}
}
?>