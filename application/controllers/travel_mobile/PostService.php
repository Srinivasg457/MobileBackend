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

        $uploadPath = FCPATH . 'uploads/posts/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
            chown($uploadPath, 'www-data');
            chgrp($uploadPath, 'www-data');
        }
        $fileName = time() . '_' . $_FILES['image']['name'];

        move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath . $fileName);

        // ✅ store RELATIVE path
        $imagePath = 'uploads/posts/' . $fileName;

        $data = [
            "user_id" => $user_id,
            "username" => $username,
            "user_image" => $userImage,
            "image_url" => $imagePath,
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

    // ✅ UPDATE POST
    // Allowed: Post owner only
    // Can update: description + optional image
    public function update($post_id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            show_error('Invalid Method', 405);
        }

        $user_id     = $this->input->post('user_id', true);
        $description = $this->input->post('description', true);

        if (empty($user_id)) {
            return $this->jsonError("user_id is required");
        }

        // ✅ Fetch existing post
        $post = $this->db->get_where('posts', [
            'post_id' => $post_id,
            'status'  => 1
        ])->row_array();

        if (!$post) {
            return $this->jsonError("Post not found or inactive");
        }

        // ✅ Permission check (only post owner)
        if ((string)$post['user_id'] !== (string)$user_id) {
            return $this->jsonError("Not allowed to update this post");
        }

        $updateData = [
            'description' => $description,
            'updated_at'  => date('Y-m-d H:i:s')
        ];

        // ✅ If new image is uploaded
        if (!empty($_FILES['image']['tmp_name'])) {

            $uploadPath = FCPATH . 'uploads/posts/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $fileName = time() . '_' . $_FILES['image']['name'];
            move_uploaded_file(
                $_FILES['image']['tmp_name'],
                $uploadPath . $fileName
            );

            $newImagePath = 'uploads/posts/' . $fileName;

            // ✅ Delete old image
            if (!empty($post['image_url'])) {
                $oldImagePath = FCPATH . $post['image_url'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $updateData['image_url'] = $newImagePath;
        }

        // ✅ Update DB
        $this->db->update('posts', $updateData, [
            'post_id' => $post_id
        ]);

        // ✅ Prepare response
        if (!empty($updateData['image_url'])) {
            $updateData['image_url'] = base_url($updateData['image_url']);
        }

        return $this->jsonSuccess("Post updated successfully", $updateData);
    }
    
    // ✅ LIST POSTS
    public function list()
    {
        $posts = $this->db
            ->order_by('created_at', 'DESC')
            ->where('status', 1)
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

    // Get paginated posts for pagenation
    public function getPosts()
    {
        $limit  = $this->input->get('limit') ?? 10;
        $lastId = $this->input->get('lastId');

        if ($lastId) {
            $lastPost = $this->db
                ->select('created_at')
                ->where('post_id', $lastId)
                >where('status', 1)
                ->get('posts')
                ->row_array();

            if ($lastPost) {
                $this->db->where('created_at <', $lastPost['created_at']);
            }
        }

        $this->db->order_by('created_at', 'DESC');
        $posts = $this->db->get('posts', $limit)->result_array();

        // ✅ Add base_url to image paths
        $posts = $this->formatPostImages($posts);

        echo json_encode([
            'status' => 'success',
            'posts'  => $posts
        ]);
    }



    // Get a single post by ID
    public function getPost($postId)
    {
        $post = $this->db->get_where('posts', [
            'post_id' => $postId,
            'status'  => 1
        ])->row_array();

        if ($post) {
            if (!empty($post['image_url']) && strpos($post['image_url'], 'http') !== 0) {
                $post['image_url'] = base_url($post['image_url']);
            }

            echo json_encode([
                'status' => 'success',
                'post'   => $post
            ]);
        } else {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Post not found'
            ]);
        }
    }


    // Get posts by user
    public function getUserPosts($userId)
    {
        $this->db->where('user_id', $userId);
        $this->db->where('status', 1);
        $this->db->order_by('created_at', 'DESC');

        $posts = $this->db->get('posts')->result_array();

        // ✅ Add base_url
        $posts = $this->formatPostImages($posts);

        echo json_encode([
            'status' => 'success',
            'posts'  => $posts
        ]);
    }

    //perform soft and hard delete by specifing the type "soft" or "hard"
    public function deleteAllPosts()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            show_error('Invalid Method', 405);
        }

        $type = $this->input->get('type') ?? 'soft';

        if ($type === 'soft') {

            // ✅ Soft delete (status = 0)
            $this->db->update('posts', [
                'status'     => 0,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            return $this->jsonSuccess("All posts soft deleted");
        } elseif ($type === 'hard') {

            // ✅ Fetch posts to delete images
            $posts = $this->db->select('image_url')->get('posts')->result_array();

            foreach ($posts as $p) {
                if (!empty($p['image_url'])) {
                    $filePath = FCPATH . str_replace(base_url(), '', $p['image_url']);

                    // Handle relative paths safely
                    if (!file_exists($filePath)) {
                        $filePath = FCPATH . $p['image_url'];
                    }

                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }

            // ✅ Hard delete from DB
            $this->db->empty_table('posts');

            return $this->jsonSuccess("All posts permanently deleted");
        } else {
            return $this->jsonError("Invalid delete type. Use soft or hard");
        }
    }

    // Allowed delete scenarios
    // Comment owner deletes their comment
    // Post owner deletes any comment
    public function deleteComment($post_id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            show_error('Invalid Method', 405);
        }

        $comment_id = $this->input->post('comment_id', true);
        $user_id    = $this->input->post('user_id', true);

        if (empty($comment_id) || empty($user_id)) {
            return $this->jsonError("comment_id and user_id are required");
        }

        // ✅ Fetch only ACTIVE post
        $post = $this->db->get_where('posts', [
            'post_id' => $post_id,
            'status'  => 1
        ])->row_array();

        if (!$post) {
            return $this->jsonError("Post not found or inactive");
        }

        $comments = json_decode($post['comments'], true);

        if (!is_array($comments) || empty($comments)) {
            return $this->jsonError("No comments found");
        }

        $commentFound = false;

        foreach ($comments as $index => $comment) {

            if ((string)$comment['id'] === (string)$comment_id) {

                // ✅ Permission: comment owner OR post owner
                if (
                    (string)$comment['user_id'] !== (string)$user_id &&
                    (string)$post['user_id'] !== (string)$user_id
                ) {
                    return $this->jsonError("Not allowed to delete this comment");
                }

                unset($comments[$index]);
                $commentFound = true;
                break;
            }
        }

        if (!$commentFound) {
            return $this->jsonError("Comment not found");
        }

        // ✅ Reindex comments array
        $comments = array_values($comments);

        // ✅ Save changes
        $this->db->update('posts', [
            'comments'   => json_encode($comments),
            'updated_at' => date('Y-m-d H:i:s')
        ], [
            'post_id' => $post_id
        ]);

        return $this->jsonSuccess("Comment deleted successfully", $comments);
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

    private function formatPostImages(array $posts)
    {
        foreach ($posts as &$p) {
            if (!empty($p['image_url'])) {
                // ✅ prevent double base_url
                if (strpos($p['image_url'], 'http') !== 0) {
                    $p['image_url'] = base_url($p['image_url']);
                }
            }
        }
        return $posts;
    }
}
