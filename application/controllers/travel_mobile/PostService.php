<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PostService extends Home_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'form']);
    }
    

    // ✅ CREATE POST
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            show_error('Invalid Method', 405);
        }

        if (empty($_FILES['image']['tmp_name'])) {
            return $this->jsonError("Image required");
        }

        $user_id   = $this->input->post('user_id');
        $username  = $this->input->post('username');
        $userImage = $this->input->post('user_image');
        $desc      = $this->input->post('description');

        // Upload Image
        $path =  FCPATH . "uploads/posts/";
        if (!is_dir($path)) {
            mkdir($$path, 0755, true);
            chown($path, 'www-data');
            chgrp($path, 'www-data');
        }
        $fileName = time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $path . $fileName);

        $data = [
            "user_id" => $user_id,
            "username" => $username,
            "user_image" => $userImage,
            "image_url" => $path . $fileName,
            "description" => $desc,
            "likes" => json_encode([]),
            "comments" => json_encode([]),
            "created_at" => date('Y-m-d H:i:s'),
            "updated_at" => date('Y-m-d H:i:s')
        ];

        $this->db->insert("posts", $data);
        $data['post_id'] = $this->db->insert_id();

        return $this->jsonSuccess("Post created", $data);
    }

    // ✅ LIST POSTS
    public function list()
    {
        $posts = $this->db
            ->order_by('created_at', 'DESC')
            ->get("posts")
            ->result_array();

        foreach ($posts as &$p) {
            $p['likes'] = json_decode($p['likes'], true) ?: [];
            $p['comments'] = json_decode($p['comments'], true) ?: [];

            // ✅ convert image path to full URL
            if (!empty($p['image_url'])) {
                $p['image_url'] = base_url($p['image_url']);
            }

        }

        return $this->jsonSuccess("Posts fetched", $posts);
    }


    // ✅ LIKE / UNLIKE
    public function like($post_id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            show_error('Invalid Method', 405);
        }

        // ✅ THIS WORKS WITH form-data
        $user_id = $this->input->post('user_id', true);

        if (!$user_id) {
            return $this->jsonError("user_id is required");
        }

        $post = $this->db->get_where("posts", ["post_id" => $post_id])->row_array();
        if (!$post) {
            return $this->jsonError("Post not found");
        }

        $likes = json_decode($post['likes'], true) ?: [];

        if (in_array($user_id, $likes)) {
            $likes = array_values(array_diff($likes, [$user_id]));
        } else {
            $likes[] = $user_id;
        }

        $this->db->update("posts", [
            "likes" => json_encode($likes),
            "updated_at" => date('Y-m-d H:i:s')
        ], ["post_id" => $post_id]);

        return $this->jsonSuccess("Like updated", $likes);
    }


    // ✅ ADD COMMENT
    public function comment($post_id)
    {
        $user_id  = $this->input->post('user_id');
        $username = $this->input->post('username');
        $text     = $this->input->post('text');

        $post = $this->db->get_where("posts", ["post_id" => $post_id])->row_array();
        if (!$post) return $this->jsonError("Post not found");

        $comments = json_decode($post['comments'], true) ?: [];

        $comments[] = [
            "id" => time(),
            "user_id" => $user_id,
            "username" => $username,
            "text" => $text,
            "created_at" => date('Y-m-d H:i:s')
        ];

        $this->db->update("posts", [
            "comments" => json_encode($comments),
            "updated_at" => date('Y-m-d H:i:s')
        ], ["post_id" => $post_id]);

        return $this->jsonSuccess("Comment added", $comments);
    }

    // ✅ DELETE POST
    public function delete($post_id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            show_error('Invalid Method', 405);
        }

        $post = $this->db->get_where('posts', ['post_id' => $post_id])->row_array();
        if (!$post) {
            return $this->jsonError('Post not found');
        }

        $this->db->delete('posts', ['post_id' => $post_id]);

        return $this->jsonSuccess('Post deleted');
    }


    // 🔹 HELPERS
    private function jsonSuccess($msg, $data = [])
    {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                "status" => "success",
                "message" => $msg,
                "data" => $data
            ]));
    }

    private function jsonError($msg)
    {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode([
                "status" => "error",
                "message" => $msg
            ]));
    }
}
