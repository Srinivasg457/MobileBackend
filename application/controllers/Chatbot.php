<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Chatbot extends CI_Controller
{
    public function index()
    {
        $this->load->view('chatbot'); // Load the view file
    }

    public function get_response()
    {
        $user_input = strtolower(trim($this->input->post('message', true)));
        $response = "⚠ Please ask only related to Workroom website!";

        // Predefined replies
        if ($user_input == "hello" || $user_input == "hi") {
            $response = "👋 Hello! Welcome to Workroom. How can I assist you today?";
        } elseif ($user_input == "how are you") {
            $response = "😊 I'm doing great! Thanks for asking. How about you?";
        } elseif ($user_input == "can you help me?" || $user_input == "help") {
            $response = "✅ Sure! You can ask me anything about projects, employees, or Workroom features.";
        } elseif (strpos($user_input, "services") !== false) {
            $response = "💡 We provide employee tracking, project management, and collaboration tools.";
        } elseif (strpos($user_input, "contact") !== false) {
            $response = "📧 You can reach our support team at <b>support@workroom.com</b>.";
        } elseif (strpos($user_input, "time") !== false || strpos($user_input, "hours") !== false) {
            $response = "⏰ Our support team is available Monday–Friday, 9AM to 6PM.";
        } elseif (strpos($user_input, "bye") !== false) {
            $response = "👋 Goodbye! Have a productive day with Workroom 🚀";
        } elseif (strpos($user_input, "thanks") !== false || strpos($user_input, "thank you") !== false) {
            $response = "🙏 You're welcome! Happy to help.";
        } elseif (strpos($user_input, "who are you") !== false) {
            $response = "🤖 I'm your Workroom Assistant Bot! I can answer your questions about the website.";
        } elseif (strpos($user_input, "joke") !== false) {
            $response = "😂 Okay! Why don’t programmers like nature? — It has too many bugs!";
        }

        // Unwanted / nonsense messages
        $unwanted = ["stupid", "idiot", "sex", "12345", "asdf", "lol", "hacker"];
        if (in_array($user_input, $unwanted)) {
            $response = "⚠ Please avoid unrelated messages. Ask only about Workroom website.";
        }

        echo json_encode(['reply' => $response]);
    }
}
