<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PostService extends Home_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'form']);

        date_default_timezone_set('Asia/Kolkata');
        $this->db->query("SET time_zone = '+05:30'");
        $this->db->query("SET NAMES 'utf8mb4'");
        $this->db->query("SET CHARACTER SET utf8mb4");
    }


    // 🔹 CLEAN user_id (ONLY trim, keep as string)
    private function cleanUserId($id)
    {
        return trim((string)$id);
    }


    // ✅ CREATE POST
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            show_error('Invalid Method', 405);
        }

        if (empty($_FILES['images']['name'][0])) {
            return $this->jsonError("At least one image is required");
        }

        $user_id   = $this->cleanUserId($this->input->post('user_id'));
        $username  = $this->input->post('username');
        $userImage = $this->input->post('user_image');
        $desc      = $this->input->post('description');

        /* ---------- UPLOAD DIRECTORY ---------- */
        $uploadPath = FCPATH . 'uploads/posts/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        /* ---------- INSERT POST ---------- */
        $postData = [
            "user_id"     => $user_id,
            "username"    => $username,
            "user_image"  => $userImage,
            "description" => $desc,
            "likes"       => json_encode([]),
            "comments"    => json_encode([]),
            "created_at"  => date('Y-m-d H:i:s'),
            "updated_at"  => date('Y-m-d H:i:s')
        ];

        $this->db->insert("posts", $postData);
        $post_id = $this->db->insert_id();

        /* ---------- UPLOAD IMAGES ---------- */
        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {

            if ($tmpName == '') continue;

            $fileName = time() . '_' . uniqid() . '_' . $_FILES['images']['name'][$key];
            move_uploaded_file($tmpName, $uploadPath . $fileName);

            $this->db->insert("post_images", [
                "post_id"    => $post_id,
                "image_url"  => 'uploads/posts/' . $fileName,
                "position"   => $key,
                "created_at" => date('Y-m-d H:i:s')
            ]);
        }

        /* ---------- FETCH FULL POST ---------- */
        $post = $this->db->get_where('posts', [
            'post_id' => $post_id
        ])->row_array();

        // Decode JSON fields
        $post['likes']    = json_decode($post['likes'], true) ?: [];
        $post['comments'] = json_decode($post['comments'], true) ?: [];

        // Attach images
        $images = $this->db
            ->order_by('position', 'ASC')
            ->get_where('post_images', ['post_id' => $post_id])
            ->result_array();

        $post['images'] = array_map(function ($img) {
            return base_url($img['image_url']);
        }, $images);

        return $this->jsonSuccess("Post created", $post);
    }


    // ✅ UPDATE POST
    // Allowed: Post owner only
    // Can update: description + optional image
    public function update($post_id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            show_error('Invalid Method', 405);
        }

        $user_id     = $this->cleanUserId($this->input->post('user_id', true));
        $description = $this->input->post('description', true);

        if (!$user_id) {
            return $this->jsonError("user_id is required");
        }

        $post = $this->db->get_where('posts', [
            'post_id' => $post_id,
            'status'  => 1
        ])->row_array();

        if (!$post) {
            return $this->jsonError("Post not found");
        }

        if ((string)$post['user_id'] !== (string)$user_id) {
            return $this->jsonError("Not allowed");
        }

        // ✅ Update description
        $this->db->update('posts', [
            'description' => $description,
            'updated_at'  => date('Y-m-d H:i:s')
        ], ['post_id' => $post_id]);

        /* ---------- OPTIONAL IMAGE UPDATE ---------- */
        if (!empty($_FILES['images']['name'][0])) {

            $uploadPath = FCPATH . 'uploads/posts/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // ✅ delete existing images (optional behavior)
            $oldImages = $this->db
                ->get_where('post_images', ['post_id' => $post_id])
                ->result_array();

            foreach ($oldImages as $img) {
                $file = FCPATH . $img['image_url'];
                if (file_exists($file)) unlink($file);
            }

            $this->db->delete('post_images', ['post_id' => $post_id]);

            // ✅ insert new images
            foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {

                if ($tmpName == '') continue;

                $fileName = time() . '_' . uniqid() . '_' . $_FILES['images']['name'][$key];
                move_uploaded_file($tmpName, $uploadPath . $fileName);

                $this->db->insert('post_images', [
                    'post_id'    => $post_id,
                    'image_url'  => 'uploads/posts/' . $fileName,
                    'position'   => $key,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        return $this->jsonSuccess("Post updated successfully");
    }


    // ✅ LIST POSTS
    public function list()
    {
        $posts = $this->db
            ->where('status', 1)
            ->order_by('created_at', 'DESC')
            ->get('posts')
            ->result_array();

        foreach ($posts as &$p) {
            $p['likes']    = json_decode($p['likes'], true) ?: [];
            $p['comments'] = json_decode($p['comments'], true) ?: [];

            $images = $this->db
                ->order_by('position', 'ASC')
                ->get_where('post_images', ['post_id' => $p['post_id']])
                ->result_array();

            $p['images'] = array_map(fn($img) => base_url($img['image_url']), $images);
        }

        return $this->jsonSuccess("Posts fetched", $posts);
    }



    // ✅ LIKE / UNLIKE
    public function like($post_id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            show_error('Invalid Method', 405);
        }

        $user_id = $this->cleanUserId($this->input->post('user_id', true));
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
        $user_id  = $this->cleanUserId($this->input->post('user_id'));
        $username = $this->input->post('username');
        $text     = $this->input->post('text');

        if (!$user_id) return $this->jsonError("user_id is required");

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

        // ✅ Check post exists
        $post = $this->db->get_where('posts', [
            'post_id' => $post_id
        ])->row_array();

        if (!$post) {
            return $this->jsonError('Post not found');
        }

        // ✅ 1. Fetch images for this post
        $images = $this->db
            ->select('image_url')
            ->get_where('post_images', ['post_id' => $post_id])
            ->result_array();

        // ✅ 2. Delete image files
        foreach ($images as $img) {
            $filePath = FCPATH . $img['image_url'];

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // ✅ 3. Delete post (CASCADE deletes post_images rows)
        $this->db->delete('posts', ['post_id' => $post_id]);

        return $this->jsonSuccess('Post deleted successfully');
    }


    // Get paginated posts for pagenation
    public function getPosts()
    {
        $limit  = $this->input->get('limit') ?? 10;
        $lastId = $this->input->get('lastId');

        /* ---------- PAGINATION ---------- */
        if ($lastId) {
            $lastPost = $this->db
                ->select('created_at')
                ->where('post_id', $lastId)
                ->where('status', 1)   // ✅ FIXED TYPO
                ->get('posts')
                ->row_array();

            if ($lastPost) {
                $this->db->where('created_at <', $lastPost['created_at']);
            }
        }

        /* ---------- FETCH POSTS ---------- */
        $posts = $this->db
            ->where('status', 1)
            ->order_by('created_at', 'DESC')
            ->limit($limit)
            ->get('posts')
            ->result_array();

        /* ---------- ATTACH IMAGES ---------- */
        foreach ($posts as &$p) {

            $p['likes']    = json_decode($p['likes'], true) ?: [];
            $p['comments'] = json_decode($p['comments'], true) ?: [];

            $images = $this->db
                ->order_by('position', 'ASC')
                ->get_where('post_images', [
                    'post_id' => $p['post_id']
                ])
                ->result_array();

            // ✅ convert to full URLs
            $p['images'] = array_map(function ($img) {
                return base_url($img['image_url']);
            }, $images);
        }

        return $this->jsonSuccess('Posts fetched', $posts);
    }




    // Get a single post by ID
    public function getPost($postId)
    {
        $post = $this->db->get_where('posts', [
            'post_id' => $postId,
            'status'  => 1
        ])->row_array();

        if (!$post) {
            return $this->jsonError("Post not found");
        }

        $post['likes']    = json_decode($post['likes'], true) ?: [];
        $post['comments'] = json_decode($post['comments'], true) ?: [];

        $images = $this->db
            ->order_by('position', 'ASC')
            ->get_where('post_images', ['post_id' => $postId])
            ->result_array();

        $post['images'] = array_map(fn($img) => base_url($img['image_url']), $images);

        return $this->jsonSuccess("Post fetched", $post);
    }



    // Get posts by user
    public function getUserPosts($userId)
    {
        $userId = $this->cleanUserId($userId);

        $posts = $this->db
            ->where('user_id', $userId)
            ->where('status', 1)
            ->order_by('created_at', 'DESC')
            ->get('posts')
            ->result_array();

        foreach ($posts as &$p) {
            $images = $this->db
                ->order_by('position', 'ASC')
                ->get_where('post_images', ['post_id' => $p['post_id']])
                ->result_array();

            $p['images'] = array_map(fn($img) => base_url($img['image_url']), $images);
        }

        return $this->jsonSuccess("User posts fetched", $posts);
    }


    //perform soft and hard delete by specifing the type "soft" or "hard"
    public function deleteAllPosts()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            show_error('Invalid Method', 405);
        }

        $type = $this->input->get('type') ?? 'soft';

        /* ---------- SOFT DELETE ---------- */
        if ($type === 'soft') {

            $this->db->update('posts', [
                'status'     => 0,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            return $this->jsonSuccess("All posts soft deleted");
        }

        /* ---------- HARD DELETE ---------- */
        if ($type === 'hard') {

            // ✅ 1. Fetch ALL image paths from post_images
            $images = $this->db
                ->select('image_url')
                ->get('post_images')
                ->result_array();

            // ✅ 2. Delete image files from storage
            foreach ($images as $img) {
                $filePath = FCPATH . $img['image_url'];

                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            // ✅ 3. Hard delete posts (CASCADE deletes post_images rows)
            $this->db->empty_table('posts');

            return $this->jsonSuccess("All posts permanently deleted");
        }

        return $this->jsonError("Invalid delete type. Use soft or hard");
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
}
