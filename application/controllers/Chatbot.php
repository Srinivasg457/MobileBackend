<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Chatbot extends CI_Controller
{
    public function index()
    {
        $this->load->view('chatbot'); // Load the view file
    }
    // public function get_response()
    // {
    //     $user_input = strtolower(trim($this->input->post('message', true)));


    //     // Menu items
    //     $menu = [
    //         "web features" => [
    //             "text" => "🌐 Workroom web features include project dashboards, employee application tracking, live chat, and easy reporting tools.",
    //             "url"  => base_url("features"),
    //             "features" => [
    //                 "Dashboards",
    //                 "Application Tracking",
    //                 "Live Chat",
    //                 "Reporting Tools"
    //             ]
    //         ],
    //         "tool features" => [
    //             "text" => "🛠️ Our tools include time tracking, task assignments, performance monitoring, and collaboration options.",
    //             "url"  => base_url("contact"),
    //             "features" => [
    //                 "Click the link ⬇"
    //             ]
    //         ],
    //         "subscription" => [
    //             "text" => "💳 We offer flexible subscription plans (Basic, Pro, and Enterprise) so you can choose the right package.",
    //             "url"  => base_url("pricing"),
    //             "features" => [
    //                 "Basic Plan",
    //                 "Standard",
    //                 "Premium"
    //             ]
    //         ],
    //         "how to signup?" => [
    //             "text" => "📝 Signing up is simple! Click on the 'Sign Up' button on the homepage, fill in your details, verify your email.",
    //             "url"  => base_url("register"),
    //             "features" => [
    //                 "Fill Personal Details",
    //                 "Verify Email",
    //                 "Verify mobile",
    //                 "Start Using Workroom"
    //             ]
    //         ]
    //     ];


    //     // Menu HTML
    //     $menu_html = "<div class='chat-bot-main-menu-tap'><span class='chat-bot-main-menu'>&#9776; Main Menu</span><hr>
    //     <table cellpadding='10' cellspacing='0' id='chat-menu'>
    //         <tr><td data-key='web features' class='chat-bot-menu-itme'><i class='fas fa-arrow-right'></i> Web Features</td></tr>
    //         <tr><td data-key='tool features' class='chat-bot-menu-itme'><i class='fas fa-arrow-right'></i> Tool Features</td></tr>
    //         <tr><td data-key='subscription' class='chat-bot-menu-itme'><i class='fas fa-arrow-right'></i> Subscription</td></tr>
    //         <tr><td data-key='how to signup?' class='chat-bot-menu-itme'><i class='fas fa-arrow-right'></i> How to signup?</td></tr>
    //     </table></div>";

    //     $response_text = "";
    //     // } elseif ($user_input == "main menu") {
    //     //     // Just show menu table, no warning
    //     //     $response_text = $menu_html;
    //     // } 

    //     // Replies
    //     if ($user_input == "hello" || $user_input == "hi") {
    //         $response_text = "👋 Hello! Welcome to Workroom. How can I assist you today?";
    //     } elseif ($user_input == "how are you") {
    //         $response_text = "😊 I'm doing great! Thanks for asking. How about you?";
    //     } elseif ($user_input == "can you help me?" || $user_input == "help") {
    //         $response_text = "✅ Sure! You can ask me anything about projects, employees, or Workroom features.";
    //     } elseif (strpos($user_input, "services") !== false) {
    //         $response_text = "💡 We provide employee tracking, project management, and collaboration tools.";
    //     } elseif (strpos($user_input, "contact") !== false) {
    //         $response_text = "📧 You can reach our support team at <b>support@workroom.com</b>.";
    //     } elseif (strpos($user_input, "time") !== false || strpos($user_input, "hours") !== false) {
    //         $response_text = "⏰ Our support team is available Monday–Friday, 9AM to 6PM.";
    //     } elseif (strpos($user_input, "bye") !== false) {
    //         $response_text = "👋 Goodbye! Have a productive day with Workroom 🚀";
    //     } elseif (strpos($user_input, "thanks") !== false || strpos($user_input, "thank you") !== false) {
    //         $response_text = "🙏 You're welcome! Happy to help.";
    //     } elseif (strpos($user_input, "who are you") !== false) {
    //         $response_text = "🤖 I'm your Workroom Assistant Bot! I can answer your questions about the website.";
    //     } elseif (strpos($user_input, "joke") !== false) {
    //         $response_text = "😂 Okay! Why don’t programmers like nature? — It has too many bugs!";
    //     } elseif (array_key_exists($user_input, $menu)) {
    //         // Base message
    //         $response_text = $menu[$user_input]['text'];

    //         // Features list for this menu item
    //         if (!empty($menu[$user_input]['features'])) {
    //             $response_text .= "<ul style='list-style-type: disc; padding-left: 20px;'>";
    //             foreach ($menu[$user_input]['features'] as $feature) {
    //                 $response_text .= "<li style='font-size:13px;'>" . $feature . "</li>";
    //             }
    //             $response_text .= "</ul>";
    //         }

    //         // Link at the end
    //         $response_text .= "<a href='" . $menu[$user_input]['url'] . "' target='_blank'>🔗 Learn more</a>";
    //     } else {
    //         $response_text = "❓ Sorry, I didn’t understand that. Please choose from the menu.";
    //     }

    //     // Send JSON
    //     $this->output
    //         ->set_content_type('application/json')
    //         ->set_output(json_encode([
    //             'reply' => $response_text,
    //             'menu'  => $menu_html
    //         ]));
    // }
    public function get_response()
    {
        $user_input = strtolower(trim($this->input->post('message', true)));

        // Menu items
        $menu = [
            "web features" => [
                "text" => "🌐 Workroom web features include project dashboards, employee application tracking, live chat, and easy reporting tools.",
                "url"  => base_url("features"),
                "features" => [
                    "Dashboards",
                    "Application Tracking",
                    "Live Chat",
                    "Reporting Tools"
                ]
            ],
            "tool features" => [
                "text" => "🛠️ Our tools include time tracking, task assignments, performance monitoring, and collaboration options.",
                "url"  => base_url("contact"),
                "features" => [
                    "Click the link ⬇"
                ]
            ],
            "subscription" => [
                "text" => "💳 We offer flexible subscription plans (Basic, Pro, and Enterprise) so you can choose the right package.",
                "url"  => base_url("pricing"),
                "features" => [
                    "Basic Plan",
                    "Standard",
                    "Premium"
                ]
            ],
            "how to signup?" => [
                "text" => "📝 Signing up is simple! Click on the 'Sign Up' button on the homepage, fill in your details, verify your email.",
                "url"  => base_url("register"),
                "features" => [
                    "Fill Personal Details",
                    "Verify Email",
                    "Verify mobile",
                    "Start Using Workroom"
                ]
            ]
        ];

        // Menu HTML
        $menu_html = "<div class='chat-bot-main-menu-tap'><span class='chat-bot-main-menu'>&#9776; Main Menu</span><hr>
    <table cellpadding='10' cellspacing='0' id='chat-menu'>
        <tr><td data-key='web features' class='chat-bot-menu-itme'><i class='fas fa-arrow-right fa fa-arrow-right'></i></i> Web Features</td></tr>
        <tr><td data-key='tool features' class='chat-bot-menu-itme'><i class='fas fa-arrow-right fa fa-arrow-right'></i> Tool Features</td></tr>
        <tr><td data-key='subscription' class='chat-bot-menu-itme'><i class='fas fa-arrow-right fa fa-arrow-right'></i> Subscription</td></tr>
        <tr><td data-key='how to signup?' class='chat-bot-menu-itme'><i class='fas fa-arrow-right fa fa-arrow-right'></i> How to signup?</td></tr>
    </table></div>";
        $response_text = "";
        // ✅ Add this at the top: Main Menu click handling




        // Replies
        if ($user_input === 'main menu') {
            $response_text = $menu_html;
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'reply' => null,
                    'menu'  => $menu_html
                ]));
            return;
        } elseif ($user_input == "hello" || $user_input == "hi") {
            $response_text = "👋 Hello! Welcome to Workroom. How can I assist you today?";
        } elseif ($user_input == "how are you") {
            $response_text = "😊 I'm doing great! Thanks for asking. How about you?";
        } elseif ($user_input == "can you help me?" || $user_input == "help") {
            $response_text = "✅ Sure! You can ask me anything about projects, employees, or Workroom features.";
        } elseif (strpos($user_input, "services") !== false) {
            $response_text = "💡 We provide employee tracking, project management, and collaboration tools.";
        } elseif (strpos($user_input, "contact") !== false) {
            $response_text = "📧 You can reach our support team at <br><b>🔗<a href='http://work-room.local/contact'> Contact</a></b>";
        } elseif (strpos($user_input, "time") !== false || strpos($user_input, "hours") !== false) {
            $response_text = "⏰ Our support team is available Monday–Friday, 9AM to 6PM.";
        } elseif (strpos($user_input, "bye") !== false) {
            $response_text = "👋 Goodbye! Have a productive day with Workroom 🚀";
        } elseif (strpos($user_input, "thanks") !== false || strpos($user_input, "thank you") !== false) {
            $response_text = "🙏 You're welcome! Happy to help.";
        } elseif (strpos($user_input, "who are you") !== false) {
            $response_text = "🤖 I'm your Workroom Assistant Bot! I can answer your questions about the website.";
        } elseif (strpos($user_input, "joke") !== false) {
            $response_text = "😂 Okay! Why don’t programmers like nature? — It has too many bugs!";
        } elseif (array_key_exists($user_input, $menu)) {
            // Base message
            $response_text = $menu[$user_input]['text'];

            // Features list for this menu item
            if (!empty($menu[$user_input]['features'])) {
                $response_text .= "<ul style='list-style-type: disc; padding-left: 20px;'>";
                foreach ($menu[$user_input]['features'] as $feature) {
                    $response_text .= "<li style='font-size:13px;'>" . $feature . "</li>";
                }
                $response_text .= "</ul>";
            }

            // Link at the end
            $response_text .= "<a href='" . $menu[$user_input]['url'] . "' target='_blank'>🔗 Learn more</a>";
        } else {
            $response_text = "❓ Sorry, I didn’t understand that. Please choose from the menu.";
        }

        // Send JSON
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'reply' => $response_text,
                'menu'  => $menu_html
            ]));
    }
}
