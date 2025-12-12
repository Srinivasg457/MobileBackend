<?php
defined('BASEPATH') or exit('No direct script access allowed');

class GarageService extends Home_Controller
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

    public function createGarageItem()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            show_error('Invalid Method', 405);
        }

        $user_id = trim($this->input->post('user_id'));   // 🔥 TRIM ADDED
        $item_type = $this->input->post('item_type'); // vehicle / accessory

        if (!$user_id || !$item_type) {
            return $this->jsonError("user_id and item_type are required");
        }

        if (!in_array($item_type, ['vehicle', 'accessory'])) {
            return $this->jsonError("Invalid item_type. Allowed: vehicle, accessory");
        }

        // Transaction start
        $this->db->trans_start();

        $reference_id = null;

        // ------------------------------------------
        //  CREATE VEHICLE
        // ------------------------------------------
        if ($item_type === 'vehicle') {

            $vehicleData = [
                'user_id' => $user_id,
                'brand'   => $this->input->post('brand'),
                'model'   => $this->input->post('model'),
                'cc' => $this->input->post('cc'),
                'mileage'  => $this->input->post('mileage'),
                'model_year' => $this->input->post('model_year'),
                'created_at' => date('Y-m-d H:i:s'),
                'status' => 1
            ];

            $this->db->insert('garage_vehicles', $vehicleData);
            $reference_id = $this->db->insert_id();

            // Insert images
            if (!empty($_FILES['images']['name'][0])) {

                $uploadPath = FCPATH . 'uploads/garage/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                foreach ($_FILES['images']['tmp_name'] as $key => $tmp) {
                    if (!$tmp) continue;

                    $fileName = time() . '_' . uniqid() . '_' . $_FILES['images']['name'][$key];
                    move_uploaded_file($tmp, $uploadPath . $fileName);

                    $this->db->insert('garage_vehicle_images', [
                        'vehicle_id' => $reference_id,
                        'image_url'  => 'uploads/garage/' . $fileName,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }

        // ------------------------------------------
        //  CREATE ACCESSORY
        // ------------------------------------------
        if ($item_type === 'accessory') {

            $connected_vehicle_id = $this->input->post('connected_vehicle_id');
            // Validate connected_vehicle_id
            if ($connected_vehicle_id) {
                $vehicleExists = $this->db->get_where('garage_vehicles', [
                    'vehicle_id' => $connected_vehicle_id,
                    'status'     => 1
                ])->row_array();

                if (!$vehicleExists) {
                    return $this->jsonError("Connected vehicle ID is not present");
                }
            }

            $accData = [
                'user_id' => $user_id,
                'brand'   => $this->input->post('brand'),
                'model'   => $this->input->post('model'),
                'modelYear' => $this->input->post('modelYear'),
                'connected_vehicle_id' => $connected_vehicle_id ? $connected_vehicle_id : null,
                'created_at' => date('Y-m-d H:i:s'),
                'status' => 1
            ];

            $this->db->insert('garage_accessories', $accData);
            $reference_id = $this->db->insert_id();

            // Insert images
            if (!empty($_FILES['images']['name'][0])) {

                $uploadPath = FCPATH . 'uploads/garage/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                foreach ($_FILES['images']['tmp_name'] as $key => $tmp) {
                    if (!$tmp) continue;

                    $fileName = time() . '_' . uniqid() . '_' . $_FILES['images']['name'][$key];
                    move_uploaded_file($tmp, $uploadPath . $fileName);

                    $this->db->insert('garage_accessory_images', [
                        'accessory_id' => $reference_id,
                        'image_url'  => 'uploads/garage/' . $fileName,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }

        /* =====================================================
         * INSERT MASTER
         * ===================================================== */
        $this->db->insert('garage_items', [
            'user_id'      => $user_id,
            'item_type'    => $item_type,
            'reference_id' => $reference_id,
            'created_at'   => date('Y-m-d H:i:s'),
            'status'       => 1
        ]);

        $item_id = $this->db->insert_id();

        // Transaction complete
        $this->db->trans_complete();

        if (!$this->db->trans_status()) {
            return $this->jsonError("Failed to create item");
        }

        return $this->jsonSuccess("Garage item created successfully", [
            'item_id' => $item_id,
            'reference_id' => $reference_id,
            'item_type' => $item_type
        ]);
    }

    /* =====================================================
     * GET SINGLE ITEM
     * ===================================================== */
    public function getGarageItem($user_id, $item_id)
    {
        $user_id = trim($user_id);   // 🔥 TRIM ADDED

        $item = $this->db->get_where('garage_items', [
            'item_id'       => $item_id,
            'user_id'  => $user_id,
            'status'   => 1
        ])->row_array();

        if (!$item) {
            return $this->jsonError("Garage item not found");
        }

        // If item type = vehicle
        if ($item['item_type'] == 'vehicle') {

            $vehicle = $this->db->get_where('garage_vehicles', [
                'vehicle_id' => $item['reference_id'],
                'status'     => 1
            ])->row_array();

            if (!$vehicle) return $this->jsonError("Vehicle data missing");

            // Add images
            $imgs = $this->db->order_by('position', 'ASC')
                ->get_where('garage_vehicle_images', ['vehicle_id' => $vehicle['vehicle_id']])
                ->result_array();

            $vehicle['images'] = array_map(fn($i) => base_url($i['image_url']), $imgs);

            $vehicle['item_type'] = 'vehicle';
            $vehicle['item_id'] = $item['id'];

            return $this->jsonSuccess("Vehicle fetched", $vehicle);
        }

        // If item type = accessory
        if ($item['item_type'] == 'accessory') {

            $acc = $this->db->get_where('garage_accessories', [
                'accessory_id' => $item['reference_id'],
                'status'       => 1
            ])->row_array();

            if (!$acc) return $this->jsonError("Accessory data missing");

            // Add images
            $imgs = $this->db
                ->get_where('garage_accessory_images', ['accessory_id' => $acc['accessory_id']])
                ->result_array();

            $acc['images'] = array_map(fn($i) => base_url($i['image_url']), $imgs);

            $acc['item_type'] = 'accessory';
            $acc['item_id'] = $item['id'];

            return $this->jsonSuccess("Accessory fetched", $acc);
        }

        return $this->jsonError("Invalid item type");
    }

    /* =====================================================
     * UPDATE WRAPPER
     * ===================================================== */
    public function updateGarageItem($item_id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            show_error('Invalid Method', 405);
        }

        // Check master item
        $item = $this->db->get_where('garage_items', [
            'item_id'     => $item_id,
            'status' => 1
        ])->row_array();

        if (!$item) return $this->jsonError("Garage item not found");

        return ($item['item_type'] === 'vehicle')
            ? $this->updateVehicle($item['reference_id'])
            : $this->updateAccessory($item['reference_id']);
    }

    /* =====================================================
     * UPDATE ACCESSORY
     * ===================================================== */
    public function updateAccessory($accessory_id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') show_error('Invalid Method', 405);

        $user_id  = trim($this->input->post('user_id', true));  // 🔥 TRIM ADDED
        $brand    = $this->input->post('brand', true);
        $model    = $this->input->post('model', true);
        $modelYear = $this->input->post('modelYear', true);
        $category = $this->input->post('category', true);
        $connected_vehicle_id = $this->input->post('connected_vehicle_id');

        if (!$user_id || !$brand || !$category)
            return $this->jsonError("user_id, brand, and category are required");

        $accessory = $this->db->get_where('garage_accessories', [
            'accessory_id' => $accessory_id,
            'user_id'      => $user_id,
            'status'       => 1
        ])->row_array();

        if (!$accessory) return $this->jsonError("Accessory not found");

        $this->db->update('garage_accessories', [
            'brand'      => $brand,
            'model'      => $model,
            'modelYear'  => $modelYear,
            'category'   => $category,
            'connected_vehicle_id' => $connected_vehicle_id ?: null,
            'updated_at' => date('Y-m-d H:i:s')
        ], ['accessory_id' => $accessory_id]);

        return $this->jsonSuccess("Accessory updated successfully");
    }

    /* =====================================================
     * UPDATE VEHICLE
     * ===================================================== */
    public function updateVehicle($vehicle_id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') show_error('Invalid Method', 405);

        $user_id  = trim($this->input->post('user_id', true));  // 🔥 TRIM ADDED
        $brand    = $this->input->post('brand', true);
        $model    = $this->input->post('model', true);
        $year     = $this->input->post('model_year', true);
        $cc       = $this->input->post('cc', true);
        $mileage  = $this->input->post('mileage', true);

        if (!$user_id) return $this->jsonError("user_id is required");
        if (!$brand) return $this->jsonError("brand is required");

        $vehicle = $this->db->get_where('garage_vehicles', [
            'vehicle_id' => $vehicle_id,
            'user_id'    => $user_id,
            'status'     => 1
        ])->row_array();

        if (!$vehicle) return $this->jsonError("Vehicle not found");

        $this->db->update('garage_vehicles', [
            'brand'      => $brand,
            'model'      => $model,
            'model_year' => $year,
            'cc'         => $cc,
            'mileage'    => $mileage,
            'updated_at' => date('Y-m-d H:i:s')
        ], ['vehicle_id' => $vehicle_id]);

        return $this->jsonSuccess("Vehicle updated successfully");
    }
    public function deleteGarageItem($item_id, $type = 'soft')
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            show_error('Invalid Method', 405);
        }

        $item = $this->db->get_where('garage_items', [
            'item_id' => $item_id
        ])->row_array();

        if (!$item) {
            return $this->jsonError("Garage item not found");
        }

        if ($item['item_type'] === 'vehicle') {
            return $this->deleteVehicle($item['reference_id'], $type);
        }

        if ($item['item_type'] === 'accessory') {
            return $this->deleteAccessory($item['reference_id'], $type);
        }

        return $this->jsonError("Invalid item type");
    }

    // ✅ DELETE VEHICLE
    public function deleteVehicle($vehicle_id, $type = 'soft')
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            show_error('Invalid Method', 405);
        }
        // STEP 1: Disconnect all accessories linked to this vehicle
        $this->db->update(
            'garage_accessories',
            ['connected_vehicle_id' => null, 'updated_at' => date('Y-m-d H:i:s')],
            ['connected_vehicle_id' => $vehicle_id]
        );
        if ($type === 'hard') {
            // Delete vehicle images from filesystem
            $images = $this->db->get_where('garage_vehicle_images', ['vehicle_id' => $vehicle_id])->result_array();
            foreach ($images as $img) {
                $file = FCPATH . $img['image_url'];
                if (file_exists($file)) unlink($file);
            }

            // Delete from DB
            $this->db->delete('garage_vehicle_images', ['vehicle_id' => $vehicle_id]);
            $this->db->delete('garage_vehicles', ['vehicle_id' => $vehicle_id]);
            $this->db->delete('garage_items', ['item_type' => 'vehicle', 'reference_id' => $vehicle_id]);

            return $this->jsonSuccess("Vehicle permanently deleted");
        } else {
            // Soft delete
            $this->db->update('garage_vehicles', [
                'status' => 0,
                'updated_at' => date('Y-m-d H:i:s')
            ], ['vehicle_id' => $vehicle_id]);

            $this->db->update('garage_items', [
                'status' => 0,
                'updated_at' => date('Y-m-d H:i:s')
            ], [
                'item_type'    => 'vehicle',
                'reference_id' => $vehicle_id
            ]);

            return $this->jsonSuccess("Vehicle soft deleted");
        }
    }

    // ✅ DELETE ACCESSORY
    public function deleteAccessory($accessory_id, $type = 'soft')
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            show_error('Invalid Method', 405);
        }

        if ($type === 'hard') {
            // Delete accessory images from filesystem
            $images = $this->db->get_where('garage_accessory_images', ['accessory_id' => $accessory_id])->result_array();
            foreach ($images as $img) {
                $file = FCPATH . $img['image_url'];
                if (file_exists($file)) unlink($file);
            }

            // Delete from DB
            $this->db->delete('garage_accessory_images', ['accessory_id' => $accessory_id]);
            $this->db->delete('garage_accessories', ['accessory_id' => $accessory_id]);
            $this->db->delete('garage_items', ['item_type' => 'accessory', 'reference_id' => $accessory_id]);

            return $this->jsonSuccess("Accessory permanently deleted");
        } else {
            // Soft delete
            $this->db->update('garage_accessories', [
                'status' => 0,
                'updated_at' => date('Y-m-d H:i:s')
            ], ['accessory_id' => $accessory_id]);

            $this->db->update('garage_items', [
                'status' => 0,
                'updated_at' => date('Y-m-d H:i:s')
            ], [
                'item_type'    => 'accessory',
                'reference_id' => $accessory_id
            ]);

            return $this->jsonSuccess("Accessory soft deleted");
        }
    }


    /* =====================================================
 * GET ALL GARAGE ITEMS (VEHICLES + ACCESSORIES)
 * ===================================================== */
    public function getAllGarageItems($user_id)
    {
        $user_id = trim($user_id);   //  TRIM ADDED
        $items = [];

        /* ============================
       FETCH VEHICLES
    ============================ */
        $vehicles = $this->db
            ->where('user_id', $user_id)
            ->where('status', 1)
            ->order_by('created_at', 'DESC')
            ->get('garage_vehicles')
            ->result_array();

        foreach ($vehicles as &$v) {

            // Fetch garage_item record
            $itemRow = $this->db->get_where('garage_items', [
                'reference_id' => $v['vehicle_id'],
                'item_type'    => 'vehicle',
                'status'       => 1
            ])->row_array();

            $v['item_id'] = $itemRow['item_id'] ?? null;

            // Fetch images
            $imgs = $this->db
                ->order_by('position', 'ASC')
                ->get_where('garage_vehicle_images', ['vehicle_id' => $v['vehicle_id']])
                ->result_array();

            $v['images'] = array_map(fn($i) => base_url($i['image_url']), $imgs);
            $v['item_type'] = 'vehicle';
            $items[] = $v;
        }

        /* ============================
       FETCH ACCESSORIES
    ============================ */
        $accessories = $this->db
            ->where('user_id', $user_id)
            ->where('status', 1)
            ->order_by('created_at', 'DESC')
            ->get('garage_accessories')
            ->result_array();

        foreach ($accessories as &$a) {
            $itemRow = $this->db->get_where('garage_items', [
                'reference_id' => $a['accessory_id'],
                'item_type'    => 'accessory',
                'status'       => 1
            ])->row_array();

            $a['item_id'] = $itemRow['item_id'] ?? null;

            // Fetch images
            $imgs = $this->db
                ->get_where('garage_accessory_images', ['accessory_id' => $a['accessory_id']])
                ->result_array();

            $a['images'] = array_map(fn($i) => base_url($i['image_url']), $imgs);
            $a['item_type'] = 'accessory';

            $items[] = $a;
        }

        /* ============================
       SORT AND RETURN
    ============================ */
        usort($items, fn($a, $b) => strtotime($b['created_at']) - strtotime($a['created_at']));

        return $this->jsonSuccess("All garage items fetched", $items);
    }

    public function getGarageItemsPaginated($user_id)
    {
        $user_id = trim($user_id);   // TRIM ADDED
        $limit   = $this->input->get('limit') ?? 10;
        $lastId  = $this->input->get('lastId'); // now this is item_id from garage_items

        $itemQuery = $this->db
            ->where('user_id', $user_id)
            ->where('status', 1)
            ->order_by('created_at', 'DESC');

        if ($lastId) {
            $lastItem = $this->db
                ->select('created_at')
                ->where('item_id', $lastId)
                ->get('garage_items')
                ->row_array();

            if ($lastItem) {
                $itemQuery->where('created_at <', $lastItem['created_at']);
            }
        }

        $items = $itemQuery
            ->limit($limit)
            ->get('garage_items')
            ->result_array();

        foreach ($items as &$item) {
            if ($item['item_type'] === 'vehicle') {
                $data = $this->db
                    ->get_where('garage_vehicles', ['vehicle_id' => $item['reference_id']])
                    ->row_array();

                if ($data) {
                    $imgs = $this->db
                        ->order_by('position', 'ASC')
                        ->get_where('garage_vehicle_images', ['vehicle_id' => $data['vehicle_id']])
                        ->result_array();

                    $item = array_merge($item, $data);
                    $item['images'] = array_map(fn($i) => base_url($i['image_url']), $imgs);
                }
            } elseif ($item['item_type'] === 'accessory') {
                $data = $this->db
                    ->get_where('garage_accessories', ['accessory_id' => $item['reference_id']])
                    ->row_array();

                if ($data) {
                    $imgs = $this->db
                        ->get_where('garage_accessory_images', ['accessory_id' => $data['accessory_id']])
                        ->result_array();

                    $item = array_merge($item, $data);
                    $item['images'] = array_map(fn($i) => base_url($i['image_url']), $imgs);
                }
            }
        }

        return $this->jsonSuccess("Garage items fetched", $items);
    }

    public function getUserVehicles($user_id)
    {
        $user_id = trim($user_id);   //  TRIM ADDED

        if (!$user_id) return $this->jsonError("user_id is required");

        $vehicles = $this->db
            ->where('user_id', $user_id)
            ->where('status', 1)
            ->order_by('created_at', 'DESC')
            ->get('garage_vehicles')
            ->result_array();

        if (!$vehicles) {
            return $this->jsonSuccess("No vehicles found", []);
        }

        $result = [];

        foreach ($vehicles as $v) {

            // Fetch item_id from master table
            $itemRow = $this->db->get_where('garage_items', [
                'reference_id' => $v['vehicle_id'],
                'item_type'    => 'vehicle',
                'status'       => 1
            ])->row_array();

            $item_id = $itemRow['item_id'] ?? null;

            // Fetch images
            $imgs = $this->db
                ->order_by('position', 'ASC')
                ->get_where('garage_vehicle_images', ['vehicle_id' => $v['vehicle_id']])
                ->result_array();

            $v['images'] = array_map(fn($i) => base_url($i['image_url']), $imgs);

            $result[] = [
                'vehicle_id' => $v['vehicle_id'],
                'item_id'    => $item_id,
                'brand'      => $v['brand'],
                'model'      => $v['model'],
                'model_year' => $v['model_year'],
                'cc'         => $v['cc'],
                'mileage'    => $v['mileage'],
                'images'     => $v['images'],
                'created_at' => $v['created_at']
            ];
        }

        return $this->jsonSuccess("All vehicles fetched", $result);
    }


    /* =====================================================
        * COUNT ITEMS
        * ===================================================== */

    public function getGarageItemCount($user_id)
    {
        $user_id = trim($user_id);   //  TRIM ADDED

        $count = $this->db
            ->where('user_id', $user_id)
            ->where('status', 1)
            ->count_all_results('garage_items');

        return $this->jsonSuccess("Total items", ['count' => $count]);
    }

    /* =====================================================
     * HELPERS
     * ===================================================== */

    private function jsonSuccess($msg, $data = [])
    {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status'  => 'success',
                'message' => $msg,
                'data'    => $data
            ]));
    }

    private function jsonError($msg)
    {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode([
                'status'  => 'error',
                'message' => $msg
            ]));
    }
}
