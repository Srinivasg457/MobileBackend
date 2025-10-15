<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Application_Tracker extends Home_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'form']);
        $this->load->library('session');
    }
    public function index()
    {
        if (!is_org_admin()) {
            redirect(base_url());
        }
        
        require_feature(11);
        if (!is_subscribed()) {
            redirect('/admin/subscription/upgrade_plan');
        }
        $data = array();
        $data['is_employee_admin'] = true;
        $data['page_title'] = 'application_tracker';

        // Get employees first
        $employees = list_employees_by_user();
        $data['employees'] = $employees;

        // Safe employee_id selection
        $employee_id = $this->input->post('employee_id', true);

        if (empty($employee_id) && !empty($employees)) {
            // fallback: pick random or first employee if list not empty
            $employee_id = get_random_employee_id();
        }

        $start_date = $this->input->post('start_date', true) ?: date('Y-m-d');
        $end_date   = $this->input->post('end_date', true) ?: date('Y-m-d');
        $order      = $this->input->post('order', true) ?: 'ascending';

        // $date  = $this->input->post('date', true) ?? date('Y-m-d');
        // $order = $this->input->post('order', true) ?? "descending";

        $data['employee_id'] = $employee_id;
        $data['start_date']     = $start_date;
        $data['end_date '] = $end_date;
        $data['order']       = $order;
        $data['can_edit']    = $this->auth_model->get_permission(2);

        // Only fetch response if employee exists
        if (!empty($employee_id)) {
            // Get productive and unproductive usage data
            $data['productive_usage'] = $this->get_app_productive_usage($employee_id, $start_date, $end_date, $order);
            $data['unproductive_usage'] = $this->get_app_unproductive_usage($employee_id, $start_date, $end_date, $order);

            // Calculate overall combined data
            $data['overall_usage'] = [
                'status' => 'success',
                'data' => [
                    'total_applications' => $data['productive_usage']['data']['total_applications'] + $data['unproductive_usage']['data']['total_applications'],
                    'total_usage_time' => $this->seconds_to_time(
                        $data['productive_usage']['data']['raw_total_usage_seconds'] +
                            $data['unproductive_usage']['data']['raw_total_usage_seconds']
                    ),
                    'raw_total_usage_seconds' => $data['productive_usage']['data']['raw_total_usage_seconds'] + $data['unproductive_usage']['data']['raw_total_usage_seconds'],
                ]
            ];
        } else {
            // If no employee_id is provided, set default responses for both productive and unproductive data
            $data['productive_usage'] = $data['unproductive_usage'] = [
                'status' => 'success',
                'data' => [
                    'total_applications' => 0,
                    'total_usage_time'   => '0s',
                    'raw_total_usage_seconds' => 0,
                    'applications'       => []
                ]
            ];

            // Overall usage will also be empty in this case
            $data['overall_usage'] = $data['productive_usage']; // Same as productive usage as no data is available
        }

        $data['main_content'] = $this->load->view('admin/application_tracker', $data, TRUE);
        $this->load->view('admin/index', $data);
    }
// Convert time (hh:mm:ss) to total seconds
public function time_to_seconds($time) {
    // Split the time into hours, minutes, and seconds
    list($hours, $minutes, $seconds) = explode(':', $time);

    // Convert hours and minutes to seconds and sum them up
    return ($hours * 3600) + ($minutes * 60) + $seconds;
}


    public function employee_application_tracker()
    {
        $employee_id = $this->session->userdata('employee_id');  // temporary static
        $data['page_title'] = "employee_application_tracker";
        $start_date = $this->input->post('start_date', true) ?: date('Y-m-d');
        $end_date   = $this->input->post('end_date', true) ?: date('Y-m-d');
        $order      = $this->input->post('order', true) ?: 'ascending';

        // $date  = $this->input->post('date', true) ?? date('Y-m-d');
        // $order = $this->input->post('order', true) ?? "descending";

        $data['employee_id'] = $employee_id;
        $data['start_date']     = $start_date;
        $data['end_date '] = $end_date;
        $data['order']       = $order;

        if (!empty($employee_id)) {
            // Get productive and unproductive usage data
            $data['productive_usage'] = $this->get_app_productive_usage($employee_id, $start_date, $end_date, $order);
            $data['unproductive_usage'] = $this->get_app_unproductive_usage($employee_id, $start_date, $end_date, $order);

            // Calculate overall combined data
            $data['overall_usage'] = [
                'status' => 'success',
                'data' => [
                    'total_applications' => $data['productive_usage']['data']['total_applications'] + $data['unproductive_usage']['data']['total_applications'],
                    'total_usage_time' => $this->seconds_to_time(
                        $data['productive_usage']['data']['raw_total_usage_seconds'] +
                            $data['unproductive_usage']['data']['raw_total_usage_seconds']
                    ),
                    'raw_total_usage_seconds' => $data['productive_usage']['data']['raw_total_usage_seconds'] + $data['unproductive_usage']['data']['raw_total_usage_seconds'],
                ]
            ];
        } else {
            // If no employee_id is provided, set default responses for both productive and unproductive data
            $data['productive_usage'] = $data['unproductive_usage'] = [
                'status' => 'success',
                'data' => [
                    'total_applications' => 0,
                    'total_usage_time'   => '0s',
                    'raw_total_usage_seconds' => 0,
                    'applications'       => []
                ]
            ];

            // Overall usage will also be empty in this case
            $data['overall_usage'] = $data['productive_usage']; // Same as productive usage as no data is available
        }

        // make sure this view exists: application/views/employee/application_tracker.php
        $data['main_content'] = $this->load->view('admin/employee/application_tracker', $data, TRUE);
        $this->load->view('admin/index', $data);
    }

    //  public function index()
    // {
    //     require_feature(13);
    //     if (!is_subscribed()) {
    //         redirect('/admin/subscription/upgrade_plan');
    //     }
    //     $data = array();
    //     $data['is_employee_admin'] = true;
    //     $data['page_title'] = 'application_tracker';
    //     $employee_id =  $this->input->post('employee_id', true) ?? get_random_employee_id();
    //     $date =  $this->input->post('date', true) ?? date('Y-m-d');
    //     $order =  $this->input->post('order', true) ?? "descending";
    //     $data['employees'] = list_employees_by_user();
    //     $data['employee_id'] = $employee_id;
    //     $data['date'] = $date;
    //     $data['order'] = $order;
    //     $data['can_edit'] = $this->auth_model->get_permission(2);
    //     // $response_data = $this->get_application_usage_logs($employee_id, $date, $order);
    //     // $data['response_data'] = $response_data; 
    //     $data['response'] = $this->get_application_usage_grouped_by_app($employee_id, $date, $order);
    //     $data['main_content'] = $this->load->view('admin/application_tracker', $data, TRUE);
    //     $this->load->view('admin/index', $data);
    // }


    /**
     * Normalize incoming date filters: accepts date or datetime string.
     * Returns 'YYYY-MM-DD' (for comparing to log_date) or null.
     */
    private function normalize_date_filter($input, $which = 'start')
    {
        if (empty($input)) return null;

        // Try strtotime; supports 'YYYY-MM-DD' and 'YYYY-MM-DD HH:MM:SS'
        $ts = strtotime($input);
        if ($ts === false) {
            return null; // invalid date — caller treats as no filter
        }
        // For log_date compare, return only date part
        return date('Y-m-d', $ts);
    }

    /**
     * Compute seconds difference between start and end datetimes.
     * If invalid values, return 0.
     */
    // private function compute_duration_seconds($start_time, $end_time)
    // {
    //     if (empty($start_time) || empty($end_time)) return 0;
    //     $s = strtotime($start_time);
    //     $e = strtotime($end_time);
    //     if ($s === false || $e === false || $e < $s) return 0;
    //     return $e - $s;
    // }

    /**
     * Convert seconds to H:i:s string.
     */
    // private function seconds_to_time($seconds)
    // {
    //     $seconds = max(0, (int)$seconds);
    //     $hours = floor($seconds / 3600);
    //     $minutes = floor(($seconds % 3600) / 60);

    //     $parts = [];
    //     if ($hours > 0) {
    //         $parts[] = "{$hours}h";
    //     }
    //     if ($minutes > 0 || empty($parts)) {
    //         $parts[] = "{$minutes}min";
    //     }

    //     return implode(' ', $parts);
    // }
    private function seconds_to_time($seconds)
    {
        $seconds = max(0, (int)$seconds);
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;

        $parts = [];
        if ($hours > 0) {
            $parts[] = "{$hours}h";
        }
        if ($minutes > 0) {
            $parts[] = "{$minutes}m";
        }
        if ($secs > 0 || empty($parts)) {
            $parts[] = "{$secs}s";
        }

        return implode(' ', $parts);
    }



    /**
     * Sort apps by usage (desc) and format durations as H:i:s.
     * Returns keyed array: [ 'AppName' => 'HH:MM:SS', ... ]
     */
    // private function sort_and_format_apps($app_breakdown)
    // {
    //     arsort($app_breakdown);
    //     $formatted = [];
    //     foreach ($app_breakdown as $app => $seconds) {
    //         $formatted[$app] = $this->seconds_to_time($seconds);
    //     }
    //     return $formatted;
    // }

    /**
     * Format daily breakdown durations to H:i:s (keeps structure)
     */
    // private function format_daily_breakdown($daily_breakdown, $order)
    // {
    //     $out = [];
    //     foreach ($daily_breakdown as $date => $apps) {
    //         // Sort the apps by seconds in ascending order
    //         if ($order) {
    //             arsort($apps);
    //         } else {
    //             asort($apps);
    //         }
    //         $out[$date] = [];
    //         foreach ($apps as $app => $seconds) {
    //             $out[$date][$app] = $this->seconds_to_time($seconds);
    //         }
    //     }
    //     return $out;
    // }


    // productive usage
    public function get_app_productive_usage($employee_id, $start_date, $end_date,  $listOrder)
    {
        try {
            // --- Get inputs (POST or GET) ---
            $order = $listOrder == "descending" ? true : false;
            $user_id = $this->session->userdata('id') ?? $this->session->userdata('employee_org_id');

            // $start_date_raw = $this->input->get_post('start_date');
            // $end_date_raw = $this->input->get_post('end_date');
            $application_name = $this->input->get_post('application_name');

            $limit = (int) $this->input->get_post('limit', TRUE) ?: 2000;
            $offset = (int) $this->input->get_post('offset', TRUE) ?: 0;

            $debug = (int) $this->input->get_post('debug', TRUE);

            // --- Basic validation for employee/user ---
            if (empty($employee_id) || empty($user_id) || !is_numeric($employee_id) || !is_numeric($user_id)) {
                return $this->output
                    ->set_status_header(400)
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'Invalid or missing employee_id or user_id'
                    ]));
            }

            // --- Normalize / parse date filters ---
            // $start_date = $this->normalize_date_filter($start_date_raw, 'start');
            // $end_date = $this->normalize_date_filter($end_date_raw, 'end');

            // Define productive applications and browser work-related keywords
            $productive_apps = [
                // Development Tools
                'code',
                'visual studio code',
                'phpstorm',
                'webstorm',
                'sublime text',
                'atom',
                'eclipse',
                'intellij idea',
                'android studio',
                'pycharm',
                'netbeans',
                'xcode',
                'postman',
                'git',
                'docker',
                'terminal',
                'gnome-terminal',
                'command prompt',
                'powershell',

                // Database & Server Tools
                'mysql workbench',
                'phpmyadmin',
                'pgadmin',
                'mongodb compass',
                'heidisql',
                'dbeaver',
                'redis desktop manager',
                'xampp',
                'wamp',
                'laragon',

                // Project Management & Collaboration
                'jira',
                'trello',
                'asana',
                'clickup',
                'notion',
                'monday.com',
                'basecamp',
                'microsoft teams',
                'teams-for-linux',
                'slack',
                'zoom',
                'google meet',
                'skype',

                // Documentation / Office Work
                'microsoft word',
                'microsoft excel',
                'microsoft powerpoint',
                'google docs',
                'google sheets',
                'google slides',
                'adobe acrobat',
                'notepad++',
                'onenote',
                'evernote',
                'gnome-text-editor',

                // Design & Creative Tools
                'figma',
                'adobe xd',
                'photoshop',
                'illustrator',
                'canva',
                'coreldraw',

                // Cloud / Hosting / DevOps
                'aws console',
                'google cloud console',
                'azure portal',
                'digitalocean',
                'filezilla',
                'putty',
                'cyberduck',
                'xlsx',

                // Work-related applications from your data
                'work-room'
            ];

            $browser_work_keywords = [
                'xlsx',
                'jira',
                'github',
                'stack overflow',
                'gitlab',
                'bitbucket',
                'confluence',
                'trello',
                'asana',
                'clickup',
                'notion',
                'monday.com',
                'basecamp',
                'aws',
                'google cloud',
                'azure',
                'digitalocean',
                'workroom',
                'dashboard',
                'atlassian',
                'outlook',
                'mail',
                'figma',
                'adobe xd',
                'localhost',
                '127.0.0.1',
                'dev',
                'development',
                'mysql',
                'postgresql',
                'mongodb',
                'redis',
                'visual studio code',
                'postman',
                'docker',
                'kubernetes'
            ];

            // Build main query using Query Builder
            $this->db->start_cache();
            $this->db->select('application_name, window_title, SUM(duration_seconds) AS total_seconds')
                ->from('application_usage_logs')
                ->where('employee_id', (int)$employee_id)
                ->where('user_id', (int)$user_id);

            // Apply productive apps filter
            $this->db->group_start();

            // Direct productive apps match
            foreach ($productive_apps as $app) {
                $this->db->or_like('LOWER(application_name)', strtolower($app));
            }

            // Browser with work-related content
            $this->db->or_group_start();
            $this->db->group_start();
            $this->db->like('LOWER(application_name)', 'chrome');
            $this->db->or_like('LOWER(application_name)', 'firefox');
            $this->db->or_like('LOWER(application_name)', 'edge');
            $this->db->or_like('LOWER(application_name)', 'safari');
            $this->db->or_like('LOWER(application_name)', 'browser');
            $this->db->group_end();

            // Check window title for work-related content
            $this->db->group_start();
            foreach ($browser_work_keywords as $keyword) {
                $this->db->or_like('LOWER(window_title)', strtolower($keyword));
            }
            $this->db->group_end();
            $this->db->group_end();

            $this->db->group_end();

            // Apply date filter
            if (!empty($date)) {
                $this->db->where('log_date', $date);
            } else {
                if ($start_date !== null) {
                    $this->db->where('log_date >=', $start_date);
                }
                if ($end_date !== null) {
                    $this->db->where('log_date <=', $end_date);
                }
            }

            if (!empty($application_name)) {
                $this->db->like('application_name', $application_name);
            }

            $this->db->group_by(['application_name', 'window_title'])
                ->order_by('total_seconds', 'DESC');

            $this->db->stop_cache();

            // Debug - show compiled SQL and exit if requested
            if ($debug === 1) {
                $sql = $this->db->get_compiled_select();
                $this->db->flush_cache();
                echo $sql;
                exit;
            }

            // Get total matching groups (without limit) for pagination metadata
            $count_query = $this->db->select('COUNT(DISTINCT CONCAT(application_name, window_title)) AS total')->get();
            $total_rows = (int) $count_query->row()->total;

            // Add limit and offset for actual data fetch
            if ($limit > 0) {
                $this->db->limit($limit, $offset);
            }

            $query = $this->db->get();
            $result = $query->result_array();

            // Clear cached where/selects
            $this->db->flush_cache();

            // Format time and prepare data structure
            $grouped = [];
            $total_usage_seconds = 0;

            foreach ($result as $row) {
                $seconds = (int)$row['total_seconds'];
                $total_usage_seconds += $seconds;

                $row['formatted_time'] = $this->seconds_to_time($seconds);

                if (!isset($grouped[$row['application_name']])) {
                    $grouped[$row['application_name']] = [
                        'total_seconds' => 0,
                        'formatted_time' => '',
                        'windows' => []
                    ];
                }

                $grouped[$row['application_name']]['total_seconds'] += $seconds;
                $grouped[$row['application_name']]['formatted_time'] = $this->seconds_to_time(
                    $grouped[$row['application_name']]['total_seconds']
                );
                $grouped[$row['application_name']]['windows'][] = [
                    'window_title' => $row['window_title'],
                    'total_seconds' => $seconds,
                    'formatted_time' => $row['formatted_time']
                ];
            }

            $order ? arsort($grouped) : asort($grouped);

            // Format output
            $response = [
                'status' => 'success',
                'meta' => [
                    'total_rows' => $total_rows,
                    'limit' => $limit,
                    'offset' => $offset,
                ],
                'data' => [
                    'total_applications' => count($grouped),
                    'total_usage_time' => $this->seconds_to_time($total_usage_seconds),
                    'raw_total_usage_seconds' => $total_usage_seconds,
                    'applications' => $grouped
                ]
            ];

            return $response;
        } catch (Exception $e) {
            log_message('error', 'get_app_productive_usage failed: ' . $e->getMessage());

            return $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Failed to get productive application usage data',
                    'error_details' => $e->getMessage()
                ]));
        }
    }



    public function get_app_unproductive_usage($employee_id, $start_date, $end_date, $listOrder)
    {
        try {
            // --- Get inputs (POST or GET) ---
            $order = $listOrder == "descending" ? true : false;
            $user_id = $this->session->userdata('id') ?? $this->session->userdata('employee_org_id');

            // $start_date_raw = $this->input->get_post('start_date');
            // $end_date_raw = $this->input->get_post('end_date');
            $application_name = $this->input->get_post('application_name');

            $limit = (int) $this->input->get_post('limit', TRUE) ?: 2000;
            $offset = (int) $this->input->get_post('offset', TRUE) ?: 0;

            $debug = (int) $this->input->get_post('debug', TRUE);

            // --- Basic validation for employee/user ---
            if (empty($employee_id) || empty($user_id) || !is_numeric($employee_id) || !is_numeric($user_id)) {
                return $this->output
                    ->set_status_header(400)
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'Invalid or missing employee_id or user_id'
                    ]));
            }

            // --- Normalize / parse date filters ---
            // $start_date = $this->normalize_date_filter($start_date_raw, 'start');
            // $end_date = $this->normalize_date_filter($end_date_raw, 'end');

            // Define productive applications and browser work-related keywords
            $productive_apps = [
                'youtube',
                'netflix',
                'amazon prime video',
                'disney+',
                'hotstar',
                'spotify',
                'soundcloud',
                'vlc',
                'mx player',
                'gom player',
                'windows media player',
                'itunes',
                'facebook',
                'instagram',
                'twitter',
                'snapchat',
                'tiktok',
                'linkedin',
                'pinterest',
                'reddit',
                'epic games launcher',
                'valorant',
                'pubg',
                'fortnite',
                'call of duty',
                'free fire',
                'minecraft',
                'roblox',
                'amazon',
                'flipkart',
                'myntra',
                'ajio',
                'meesho',
                'ebay',
                'olx',
                'whatsapp',
                'telegram',
                'discord',
                'signal',
                'facebook messenger',
                'opera gx',
                'brave',
                'uc browser',
                'chrome://games',
                'photos',
                'gallery',
                'camera',
                'music',
                'movies',
                'video player',
                'media player',
                'news',
                'weather'
            ];

            $browser_work_keywords = [
                'youtube',
                'netflix',
                'prime video',
                'disney+',
                'hotstar',
                'mx player',
                'vlc',
                'spotify',
                'soundcloud',
                'apple music',
                'youtube music',
                'facebook',
                'instagram',
                'threads',
                'snapchat',
                'reddit',
                'tiktok',
                'pinterest',
                'tumblr',
                'discord',
                'whatsapp',
                'telegram',
                'messenger',
                'amazon',
                'flipkart',
                'myntra',
                'ajio',
                'meesho',
                'snapdeal',
                'ebay',
                'olx',
                'google news',
                'times of india',
                'moneycontrol',
                'cricbuzz',
                'espn',
                'ndtv',
                'weather',
                'movies',
                'music',
                'entertainment'
            ];
            // Build main query using Query Builder
            $this->db->start_cache();
            $this->db->select('application_name, window_title, SUM(duration_seconds) AS total_seconds')
                ->from('application_usage_logs')
                ->where('employee_id', (int)$employee_id)
                ->where('user_id', (int)$user_id);

            // Apply productive apps filter
            $this->db->group_start();

            // Direct productive apps match
            foreach ($productive_apps as $app) {
                $this->db->or_like('LOWER(application_name)', strtolower($app));
            }

            // Browser with work-related content
            $this->db->or_group_start();
            $this->db->group_start();
            $this->db->like('LOWER(application_name)', 'chrome');
            $this->db->or_like('LOWER(application_name)', 'firefox');
            $this->db->or_like('LOWER(application_name)', 'edge');
            $this->db->or_like('LOWER(application_name)', 'safari');
            $this->db->or_like('LOWER(application_name)', 'browser');
            $this->db->group_end();

            // Check window title for work-related content
            $this->db->group_start();
            foreach ($browser_work_keywords as $keyword) {
                $this->db->or_like('LOWER(window_title)', strtolower($keyword));
            }
            $this->db->group_end();
            $this->db->group_end();

            $this->db->group_end();

            // Apply date filter
            if (!empty($date)) {
                $this->db->where('log_date', $date);
            } else {
                if ($start_date !== null) {
                    $this->db->where('log_date >=', $start_date);
                }
                if ($end_date !== null) {
                    $this->db->where('log_date <=', $end_date);
                }
            }

            if (!empty($application_name)) {
                $this->db->like('application_name', $application_name);
            }

            $this->db->group_by(['application_name', 'window_title'])
                ->order_by('total_seconds', 'DESC');

            $this->db->stop_cache();

            // Debug - show compiled SQL and exit if requested
            if ($debug === 1) {
                $sql = $this->db->get_compiled_select();
                $this->db->flush_cache();
                echo $sql;
                exit;
            }

            // Get total matching groups (without limit) for pagination metadata
            $count_query = $this->db->select('COUNT(DISTINCT CONCAT(application_name, window_title)) AS total')->get();
            $total_rows = (int) $count_query->row()->total;

            // Add limit and offset for actual data fetch
            if ($limit > 0) {
                $this->db->limit($limit, $offset);
            }

            $query = $this->db->get();
            $result = $query->result_array();

            // Clear cached where/selects
            $this->db->flush_cache();

            // Format time and prepare data structure
            $grouped = [];
            $total_usage_seconds = 0;

            foreach ($result as $row) {
                $seconds = (int)$row['total_seconds'];
                $total_usage_seconds += $seconds;

                $row['formatted_time'] = $this->seconds_to_time($seconds);

                if (!isset($grouped[$row['application_name']])) {
                    $grouped[$row['application_name']] = [
                        'total_seconds' => 0,
                        'formatted_time' => '',
                        'windows' => []
                    ];
                }

                $grouped[$row['application_name']]['total_seconds'] += $seconds;
                $grouped[$row['application_name']]['formatted_time'] = $this->seconds_to_time(
                    $grouped[$row['application_name']]['total_seconds']
                );
                $grouped[$row['application_name']]['windows'][] = [
                    'window_title' => $row['window_title'],
                    'total_seconds' => $seconds,
                    'formatted_time' => $row['formatted_time']
                ];
            }

            $order ? arsort($grouped) : asort($grouped);

            // Format output
            $response = [
                'status' => 'success',
                'meta' => [
                    'total_rows' => $total_rows,
                    'limit' => $limit,
                    'offset' => $offset,
                ],
                'data' => [
                    'total_applications' => count($grouped),
                    'total_usage_time' => $this->seconds_to_time($total_usage_seconds),
                    'raw_total_usage_seconds' => $total_usage_seconds,
                    'applications' => $grouped
                ]
            ];

            return $response;
        } catch (Exception $e) {
            log_message('error', 'get_app_productive_usage failed: ' . $e->getMessage());

            return $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Failed to get productive application usage data',
                    'error_details' => $e->getMessage()
                ]));
        }
    }
}
