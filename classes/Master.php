<?php
require_once('../config.php');
class Master extends DBConnection {
    private $settings;
    public function __construct(){
        global $_settings;
        $this->settings = $_settings;
        parent::__construct();
    }
    public function __destruct(){
        parent::__destruct();
    }

    /**
     * Return false if no error else return JSON error response string
     */
    function capture_err(){
        if(!$this->conn->error)
            return false;
        else{
            $resp = [];
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
            return json_encode($resp);
        }
    }

    /* ==============================
       BRANDS
       ============================== */
function save_mini_product() {
    extract($_POST);
    $data = "";
    foreach ($_POST as $k => $v) {
        if (!in_array($k, ['id']) && !is_array($v)) {
            $data .= " `{$k}`='" . addslashes(trim($v)) . "',";
        }
    }
    $data = rtrim($data, ',');

    if (empty($id)) {
        $sql = "INSERT INTO `mini_products` SET {$data}";
    } else {
        $sql = "UPDATE `mini_products` SET {$data} WHERE id = '{$id}'";
    }

    $save = $this->conn->query($sql);
    if (!$save) {
        return json_encode(['status' => 'failed', 'error' => $this->conn->error]);
    }

    $mini_product_id = empty($id) ? $this->conn->insert_id : $id;

    /* ---------- IMAGE UPLOAD FIX ---------- */
    if (isset($_FILES['image']) && $_FILES['image']['tmp_name'] != '') {
        $upload_dir = __DIR__ . '/../uploads/mini_products/mini_product_' . $mini_product_id . '/';
        $db_path = 'uploads/mini_products/mini_product_' . $mini_product_id . '/';

        // Create folder if missing
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Generate unique name
        $filename = time() . '_' . basename($_FILES['image']['name']);
        $target = $upload_dir . $filename;

        // Move the file
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            // Save DB path
            $img_path = $db_path . $filename;
            $this->conn->query("UPDATE mini_products SET image_path = '{$img_path}' WHERE id = '{$mini_product_id}'");
        }
    }

    return json_encode(['status' => 'success', 'id' => $mini_product_id]);
}

function delete_mini_product(){
    extract($_POST);
    $qry = $this->conn->query("SELECT image_path FROM mini_products WHERE id = '{$id}'");
    if($qry && $qry->num_rows > 0){
        $path = $qry->fetch_assoc()['image_path'];
        if(!empty($path) && file_exists('../'.$path)) unlink('../'.$path);
    }

    $delete = $this->conn->query("UPDATE mini_products SET delete_flag = 1 WHERE id = '{$id}'");
    if($delete)
        return json_encode(['status'=>'success']);
    else
        return json_encode(['status'=>'failed','msg'=>'Database Error: '.$this->conn->error]);
}

// ===== SAVE SUB PRODUCT ===== //
function save_sub_product(){
    extract($_POST);
    $data = "";

    foreach($_POST as $k => $v){
        if(!in_array($k, ['id']) && !is_array($v)){
            if(!empty($data)) $data .= ", ";
            $data .= " `{$k}` = '{$this->conn->real_escape_string($v)}' ";
        }
    }

    // --- Insert or Update ---
    if(empty($id)){
        $sql = "INSERT INTO sub_products SET {$data}";
    } else {
        $sql = "UPDATE sub_products SET {$data} WHERE id = {$id}";
    }

    $save = $this->conn->query($sql);
    if(!$save){
        return json_encode(['status'=>'failed','error'=>$this->conn->error]);
    }

    // Get the new ID if inserting
    $sid = empty($id) ? $this->conn->insert_id : $id;

    // --- Handle image uploads ---
    if(isset($_FILES['img']) && count($_FILES['img']['tmp_name']) > 0){
        $upload_path = "uploads/sub_products/sub_product_" . $sid;
        if(!is_dir(base_app.$upload_path)){
            mkdir(base_app.$upload_path, 0755, true);
        }

        foreach($_FILES['img']['tmp_name'] as $k => $tmp_name){
            if(!empty($_FILES['img']['tmp_name'][$k])){
                $file_name = time().'_'.basename($_FILES['img']['name'][$k]);
                $move = move_uploaded_file($tmp_name, base_app.$upload_path.'/'.$file_name);
                if($move){
                    $path = $this->conn->real_escape_string($upload_path.'/'.$file_name);
                    $this->conn->query("UPDATE sub_products SET image_path = '{$path}' WHERE id = '{$sid}'");
                }
            }
        }
    }

    return json_encode(['status'=>'success','id'=>$sid]);
}
function delete_sub_product() {
    extract($_POST);
    $id = isset($id) ? intval($id) : 0;

    if ($id <= 0) {
        return json_encode(['status' => 'failed', 'msg' => 'Invalid sub-product ID']);
    }

    // Mark as deleted (soft delete)
    $delete = $this->conn->query("UPDATE sub_products SET delete_flag = 1 WHERE id = '{$id}'");
    if (!$delete) {
        return json_encode(['status' => 'failed', 'msg' => 'Database error: ' . $this->conn->error]);
    }

    // Remove images folder if exists
    $upload_path = base_app . "uploads/sub_products/sub_product_" . $id;
    if (is_dir($upload_path)) {
        $files = scandir($upload_path);
        foreach ($files as $file) {
            if (!in_array($file, ['.', '..'])) {
                unlink($upload_path . '/' . $file);
            }
        }
        rmdir($upload_path);
    }

    return json_encode(['status' => 'success']);
}

       function save_brand(){
        $_POST = array_map(function($v){ return is_string($v)?trim($v):$v; }, $_POST);
        $id = isset($_POST['id'])?$_POST['id']:'';
        $name = isset($_POST['name'])? $this->conn->real_escape_string($_POST['name']):'';

        $data = [];
        foreach($_POST as $k =>$v){
            if($k === 'id') continue;
            $data[$k] = $this->conn->real_escape_string($v);
        }

        $check_q = $this->conn->query("SELECT * FROM `brands` WHERE `name` = '{$name}' ".(!empty($id)?" AND id != {$id}":""));
        if($this->capture_err()) return $this->capture_err();
        if($check_q->num_rows > 0){
            return json_encode(['status'=>'failed','msg'=>'Brand Name already exist.']);
        }

        if(empty($id)){
            $cols = implode(',', array_map(function($c){return "`$c`";}, array_keys($data)));
            $vals = implode(',', array_map(function($v){return "'$v'";}, array_values($data)));
            $sql = "INSERT INTO `brands` ($cols) VALUES ($vals)";
        }else{
            $sets = [];
            foreach($data as $k=>$v) $sets[] = "`$k`='$v'";
            $sql = "UPDATE `brands` SET ".implode(',', $sets)." WHERE id='{$id}'";
        }

        $save = $this->conn->query($sql);
        if(!$save){
            return json_encode(['status'=>'failed','err'=>$this->conn->error,'sql'=>$sql]);
        }

        $bid = !empty($id) ? $id : $this->conn->insert_id;
        $resp = ['status'=>'success','msg'=> empty($id)?'New Brand successfully saved.':'Brand successfully updated.','id'=>$bid];

        // Handle single image upload (optional)
        if(!empty($_FILES['img']['tmp_name'])){
            if(!is_dir(base_app."uploads/brands")) mkdir(base_app."uploads/brands", 0777, true);
            $ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
            $fname = "uploads/brands/$bid.$ext";
            $accept = array('image/jpeg','image/png');
            if(!in_array($_FILES['img']['type'],$accept)){
                $resp['msg'] .= " Image file type is invalid";
            } else {
                if($_FILES['img']['type'] == 'image/jpeg')
                    $uploadfile = @imagecreatefromjpeg($_FILES['img']['tmp_name']);
                elseif($_FILES['img']['type'] == 'image/png')
                    $uploadfile = @imagecreatefrompng($_FILES['img']['tmp_name']);
                else
                    $uploadfile = false;

                if(!$uploadfile){
                    $resp['msg'] .= " Image is invalid";
                } else {
                    $temp = imagescale($uploadfile,200,200);
                    if(is_file(base_app.$fname)) unlink(base_app.$fname);
                    if($_FILES['img']['type'] == 'image/jpeg')
                        $upload = imagejpeg($temp,base_app.$fname,85);
                    else
                        $upload = imagepng($temp,base_app.$fname);
                    if($upload){
                        $this->conn->query("UPDATE brands SET `image_path` = CONCAT('{$fname}', '?v=',unix_timestamp(CURRENT_TIMESTAMP)) WHERE id = '{$bid}'");
                    }
                    imagedestroy($temp);
                    imagedestroy($uploadfile);
                }
            }
        }

        $this->settings->set_flashdata('success',$resp['msg']);
        return json_encode($resp);
    }

    function delete_brand(){
        extract($_POST);
        $del = $this->conn->query("UPDATE `brands` SET `delete_flag` = 1 WHERE id = '{$id}'");
        if($del){
            $this->settings->set_flashdata('success','Brand successfully deleted.');
            return json_encode(['status'=>'success']);
        }else{
            return json_encode(['status'=>'failed','error'=>$this->conn->error]);
        }
    }



    /* ==============================
       CATEGORY
       ============================== */
    function save_category(){
        $_POST = array_map(function($v){ return is_string($v)?trim($v):$v; }, $_POST);
        $id = isset($_POST['id'])?$_POST['id']:'';
        $category = isset($_POST['category'])? $this->conn->real_escape_string($_POST['category']):'';
        $description = isset($_POST['description'])? addslashes(htmlentities($_POST['description'])):'';

        $data = [];
        foreach($_POST as $k =>$v){
            if(in_array($k,['id','description'])) continue;
            $data[$k] = $this->conn->real_escape_string($v);
        }
        if(!empty($description)) $data['description'] = $description;

        $check_q = $this->conn->query("SELECT * FROM categories WHERE category = '{$category}' ".(!empty($id)?" AND id != {$id}":""));
        if($this->capture_err()) return $this->capture_err();
        if($check_q->num_rows > 0) return json_encode(['status'=>'failed','msg'=>'Category already exist.']);

        if(empty($id)){
            $cols = implode(',', array_map(function($c){return "`$c`";}, array_keys($data)));
            $vals = implode(',', array_map(function($v){return "'$v'";}, array_values($data)));
            $sql = "INSERT INTO categories ($cols) VALUES ($vals)";
        } else {
            $sets = [];
            foreach($data as $k=>$v) $sets[] = "`$k`='$v'";
            $sql = "UPDATE categories SET ".implode(',', $sets)." WHERE id='{$id}'";
        }

        $save = $this->conn->query($sql);
        if(!$save) return json_encode(['status'=>'failed','err'=>$this->conn->error,'sql'=>$sql]);
        $this->settings->set_flashdata('success', empty($id)?'New Category successfully saved.':'Category successfully updated.');
        return json_encode(['status'=>'success']);
    }

    function delete_category(){
        extract($_POST);
        $del = $this->conn->query("UPDATE categories SET delete_flag = 1 WHERE id = '{$id}'");
        if($del){
            $this->settings->set_flashdata('success','Category successfully deleted.');
            return json_encode(['status'=>'success']);
        }else{
            return json_encode(['status'=>'failed','error'=>$this->conn->error]);
        }
    }

    /* ==============================
       PRODUCTS, INVENTORY, CART, ORDERS - cleaned and kept behavior
       Note: these functions assume table/column names exist as in original app.
       ============================== */

    function save_product(){
        // simple wrapper around generic save (not implemented in original snippet fully)
        // For brevity, keep using the user's original pattern if they call this route.
        if(empty($_POST)) return json_encode(['status'=>'failed','msg'=>'No data provided']);
        $id = isset($_POST['id'])?$_POST['id']:'';
        $data = [];
        foreach($_POST as $k=>$v){ if($k=='id') continue; $data[$k]=$this->conn->real_escape_string($v);} 
        if(empty($id)){
            $cols = implode(',', array_map(function($c){return "`$c`";}, array_keys($data)));
            $vals = implode(',', array_map(function($v){return "'$v'";}, array_values($data)));
            $sql = "INSERT INTO products ($cols) VALUES ($vals)";
        }else{
            $sets=[]; foreach($data as $k=>$v) $sets[]="`$k`='$v'";
            $sql = "UPDATE products SET ".implode(',', $sets)." WHERE id='{$id}'";
        }
        $save = $this->conn->query($sql);
        if($save){
            $this->settings->set_flashdata('success', empty($id)?'Product successfully saved.':'Product successfully updated.');
            return json_encode(['status'=>'success']);
        }else{
            return json_encode(['status'=>'failed','error'=>$this->conn->error,'sql'=>$sql]);
        }
    }

    function delete_product(){
        extract($_POST);
        $del = $this->conn->query("UPDATE products SET delete_flag = 1 WHERE id = '{$id}'");
        if($del){ $this->settings->set_flashdata('success','Product successfully deleted.'); return json_encode(['status'=>'success']); }
        return json_encode(['status'=>'failed','error'=>$this->conn->error]);
    }

    function save_inventory(){
        extract($_POST);
        $product_id = $this->conn->real_escape_string($product_id);
        $variant = $this->conn->real_escape_string($variant);
        $id = isset($id)?$id:'';
        $check = $this->conn->query("SELECT * FROM inventory WHERE product_id='{$product_id}' AND variant='{$variant}' ".(!empty($id)?" AND id != {$id}":""))->num_rows;
        if($this->capture_err()) return $this->capture_err();
        if($check > 0) return json_encode(['status'=>'failed','msg'=>'Inventory already exist.']);
        $data = [];
        foreach($_POST as $k=>$v){ if(in_array($k,['id','description'])) continue; $data[$k]=$this->conn->real_escape_string($v);} 
        if(isset($_POST['description'])) $data['description']=$this->conn->real_escape_string($_POST['description']);
        if(empty($id)){
            $cols = implode(',', array_map(function($c){return "`$c`";}, array_keys($data)));
            $vals = implode(',', array_map(function($v){return "'$v'";}, array_values($data)));
            $sql = "INSERT INTO inventory ($cols) VALUES ($vals)";
        }else{
            $sets=[]; foreach($data as $k=>$v) $sets[]="`$k`='$v'";
            $sql = "UPDATE inventory SET ".implode(',', $sets)." WHERE id='{$id}'";
        }
        $save = $this->conn->query($sql);
        if($save){
            $this->settings->set_flashdata('success', empty($id)?'New Inventory successfully saved.':'Inventory successfully updated.');
            return json_encode(['status'=>'success']);
        }else{
            return json_encode(['status'=>'failed','err'=>$this->conn->error,'sql'=>$sql]);
        }
    }

    function delete_inventory(){
        extract($_POST);
        $del = $this->conn->query("DELETE FROM inventory WHERE id = '{$id}'");
        if($del){ $this->settings->set_flashdata('success','Inventory successfully deleted.'); return json_encode(['status'=>'success']); }
        return json_encode(['status'=>'failed','error'=>$this->conn->error]);
    }

    /* ==============================
       CLIENT / AUTH
       ============================== */
    function register(){
        if(empty($_POST)) return json_encode(['status'=>'failed','msg'=>'No data provided']);
        $_POST = array_map(function($v){ return is_string($v)?trim($v):$v; }, $_POST);
        $id = isset($_POST['id'])?$_POST['id']:'';
        $_POST['password'] = md5($_POST['password']);
        $email = $this->conn->real_escape_string($_POST['email']);
        $check = $this->conn->query("SELECT * FROM clients WHERE email = '{$email}' ".(!empty($id)?" AND id != {$id}":""))->num_rows;
        if($this->capture_err()) return $this->capture_err();
        if($check > 0) return json_encode(['status'=>'failed','msg'=>'Email already taken.']);
        $data = [];
        foreach($_POST as $k=>$v){ if($k=='id') continue; $data[$k]=$this->conn->real_escape_string($v);} 
        if(empty($id)){
            $cols = implode(',', array_map(function($c){return "`$c`";}, array_keys($data)));
            $vals = implode(',', array_map(function($v){return "'$v'";}, array_values($data)));
            $sql = "INSERT INTO clients ($cols) VALUES ($vals)";
        }else{
            $sets=[]; foreach($data as $k=>$v) $sets[]="`$k`='$v'";
            $sql = "UPDATE clients SET ".implode(',', $sets)." WHERE id='{$id}'";
        }
        $save = $this->conn->query($sql);
        if($save){
            $cid = empty($id)? $this->conn->insert_id : $id;
            $this->settings->set_flashdata('success', empty($id)?'Account successfully created.':'Account successfully updated.');
            $this->settings->set_userdata('login_type',2);
            foreach($_POST as $k=>$v) $this->settings->set_userdata($k,$v);
            $this->settings->set_userdata('id',$cid);
            return json_encode(['status'=>'success','id'=>$cid]);
        }else{
            return json_encode(['status'=>'failed','err'=>$this->conn->error,'sql'=>$sql]);
        }
    }

    /* ==============================
       CART & ORDER
       ============================== */
    function add_to_cart(){
        extract($_POST);
        $client_id = $this->settings->userdata('id');
        if(!$client_id) return json_encode(['status'=>'failed','msg'=>'Not logged in']);
        $_POST['price'] = isset($_POST['price'])? str_replace(',','',$_POST['price']):0;
        $data = [ 'client_id'=>$client_id ];
        foreach($_POST as $k=>$v){ if($k=='id') continue; $data[$k] = $this->conn->real_escape_string($v);} 
        $check = $this->conn->query("SELECT * FROM cart WHERE inventory_id = '{$data['inventory_id']}' AND client_id = {$client_id}")->num_rows;
        if($this->capture_err()) return $this->capture_err();
        if($check > 0){
            $sql = "UPDATE cart SET quantity = quantity + {$data['quantity']} WHERE inventory_id = '{$data['inventory_id']}' AND client_id = {$client_id}";
        }else{
            $cols = implode(',', array_map(function($c){return "`$c`";}, array_keys($data)));
            $vals = implode(',', array_map(function($v){return "'$v'";}, array_values($data)));
            $sql = "INSERT INTO cart ($cols) VALUES ($vals)";
        }
        $save = $this->conn->query($sql);
        if($this->capture_err()) return $this->capture_err();
        if($save){
            $items = $this->conn->query("SELECT SUM(quantity) as items FROM cart WHERE client_id = {$client_id}")->fetch_assoc()['items'];
            return json_encode(['status'=>'success','cart_count'=>$items]);
        }else{
            return json_encode(['status'=>'failed','err'=>$this->conn->error,'sql'=>$sql]);
        }
    }

    function update_cart_qty(){
        extract($_POST);
        $save = $this->conn->query("UPDATE cart SET quantity = '{$quantity}' WHERE id = '{$id}'");
        if($this->capture_err()) return $this->capture_err();
        if($save) return json_encode(['status'=>'success']);
        return json_encode(['status'=>'failed','err'=>$this->conn->error]);
    }

    function empty_cart(){
        $client_id = $this->settings->userdata('id');
        $delete = $this->conn->query("DELETE FROM cart WHERE client_id = {$client_id}");
        if($this->capture_err()) return $this->capture_err();
        if($delete) return json_encode(['status'=>'success']);
        return json_encode(['status'=>'failed','err'=>$this->conn->error]);
    }

    function delete_cart(){
        extract($_POST);
        $delete = $this->conn->query("DELETE FROM cart WHERE id = '{$id}'");
        if($this->capture_err()) return $this->capture_err();
        if($delete) return json_encode(['status'=>'success']);
        return json_encode(['status'=>'failed','err'=>$this->conn->error]);
    }

    /* place_order kept largely unchanged but tightened */
    function place_order(){
        if(empty($_POST)) return json_encode(['status'=>'failed','msg'=>'No data provided']);
        $client_id = $this->settings->userdata('id');
        if(!$client_id) return json_encode(['status'=>'failed','msg'=>'Not logged in']);

        // generate ref if not provided
        if(empty($_POST['id'])){
            $prefix = date('Ym');
            $code = sprintf("%'.05d",1);
            while(true){
                $check = $this->conn->query("SELECT * FROM orders WHERE ref_code = '{$prefix}{$code}'")->num_rows;
                if($check > 0) $code = sprintf("%'.05d", intval($code) + 1);
                else break;
            }
            $_POST['ref_code'] = $prefix.$code;
        }

        extract($_POST);
        $data = "client_id = '{$client_id}'";
        if(isset($ref_code)) $data .= ", ref_code = '{$this->conn->real_escape_string($ref_code)}'";
        $data .= ", payment_method = '{$this->conn->real_escape_string($payment_method)}'";
        $data .= ", amount = '{$this->conn->real_escape_string($amount)}'";
        $data .= ", paid = '{$this->conn->real_escape_string($paid)}'";
        $data .= ", delivery_address = '{$this->conn->real_escape_string($delivery_address)}'";

        $order_sql = "INSERT INTO orders SET {$data}";
        $save_order = $this->conn->query($order_sql);
        if($this->capture_err()) return $this->capture_err();
        if(!$save_order) return json_encode(['status'=>'failed','err'=>$this->conn->error,'sql'=>$order_sql]);

        $order_id = $this->conn->insert_id;
        $cart = $this->conn->query("SELECT c.*, i.price, p.id as pid FROM cart c INNER JOIN inventory i ON i.id=c.inventory_id INNER JOIN products p ON p.id = i.product_id WHERE c.client_id = '{$client_id}'");
        $data_vals = [];
        while($row = $cart->fetch_assoc()){
            $total = $row['price'] * $row['quantity'];
            $inv_id = $this->conn->real_escape_string($row['inventory_id']);
            $price = $this->conn->real_escape_string($row['price']);
            $qty = $this->conn->real_escape_string($row['quantity']);
            $data_vals[] = "('{$order_id}','{$inv_id}','{$qty}','{$price}','{$total}')";
        }
        if(count($data_vals) > 0){
            $list_sql = "INSERT INTO order_list (order_id,inventory_id,quantity,price,total) VALUES ".implode(',', $data_vals);
            $save_olist = $this->conn->query($list_sql);
            if($this->capture_err()) return $this->capture_err();
            if(!$save_olist) return json_encode(['status'=>'failed','err'=>$this->conn->error,'sql'=>$list_sql]);

            $empty_cart = $this->conn->query("DELETE FROM cart WHERE client_id = '{$client_id}'");
            $sales_sql = "INSERT INTO sales SET order_id = '{$order_id}', total_amount = '{$this->conn->real_escape_string($amount)}'";
            $save_sales = $this->conn->query($sales_sql);
            if($this->capture_err()) return $this->capture_err();

            $this->settings->set_flashdata('success','Order has been placed successfully.');
            return json_encode(['status'=>'success','order_id'=>$order_id]);
        }else{
            return json_encode(['status'=>'failed','msg'=>'Cart is empty.']);
        }
    }

    function update_order_status(){
        extract($_POST);
        $update = $this->conn->query("UPDATE orders SET status = '{$this->conn->real_escape_string($status)}' WHERE id = '{$id}'");
        if($update){ $this->settings->set_flashdata('success','Order status successfully updated.'); return json_encode(['status'=>'success']); }
        return json_encode(['status'=>'failed','err'=>$this->conn->error]);
    }

    function pay_order(){
        extract($_POST);
        $update = $this->conn->query("UPDATE orders SET paid = 1 WHERE id = '{$id}'");
        if($update){ $this->settings->set_flashdata('success','Order payment status successfully updated.'); return json_encode(['status'=>'success']); }
        return json_encode(['status'=>'failed','err'=>$this->conn->error]);
    }

    /* ==============================
       ACCOUNT / CLIENT MANAGEMENT
       ============================== */
    function update_account(){
        if(!empty($_POST['password'])) $_POST['password'] = md5($_POST['password']); else unset($_POST['password']);
        extract($_POST);
        $data = '';
        if(md5($cpassword) != $this->settings->userdata('password')) return json_encode(['status'=>'failed','msg'=>'Current Password is Incorrect']);
        $check = $this->conn->query("SELECT * FROM clients WHERE email='{$this->conn->real_escape_string($email)}' AND id != {$id}")->num_rows;
        if($check > 0) return json_encode(['status'=>'failed','msg'=>'Email already taken.']);
        foreach($_POST as $k=>$v){ if($k=='cpassword') continue; if($k=='password' && empty($v)) continue; if(!empty($data)) $data .= ','; $data .= "`$k`='{$this->conn->real_escape_string($v)}'"; }
        $save = $this->conn->query("UPDATE clients SET {$data} WHERE id = {$id}");
        if($save){
            foreach($_POST as $k=>$v){ if($k != 'cpassword') $this->settings->set_userdata($k,$v); }
            $this->settings->set_flashdata('success','Your Account Details has been updated successfully.');
            return json_encode(['status'=>'success']);
        }
        return json_encode(['status'=>'failed','err'=>$this->conn->error]);
    }

    function update_client(){
        if(!empty($_POST['password'])) $_POST['password'] = md5($_POST['password']); else unset($_POST['password']);
        extract($_POST);
        $check = $this->conn->query("SELECT * FROM clients WHERE email='{$this->conn->real_escape_string($email)}' AND id != {$id}")->num_rows;
        if($check > 0) return json_encode(['status'=>'failed','msg'=>'Email already taken.']);
        $data=''; foreach($_POST as $k=>$v){ if($k=='id') continue; if(!empty($data)) $data .= ','; $data .= "`$k`='{$this->conn->real_escape_string($v)}'"; }
        $save = $this->conn->query("UPDATE clients SET {$data} WHERE id = {$id}");
        if($save){ $this->settings->set_flashdata('success','Client Details Successfully Updated.'); return json_encode(['status'=>'success']); }
        return json_encode(['status'=>'failed','err'=>$this->conn->error]);
    }

    function delete_client(){
        extract($_POST);
        $delete = $this->conn->query("UPDATE clients SET delete_flag = 1 WHERE id = '{$id}'");
        if($delete){ $this->settings->set_flashdata('success','Client successfully deleted'); return json_encode(['status'=>'success']); }
        return json_encode(['status'=>'failed','err'=>$this->conn->error]);
    }

    /* ==============================
       IMAGES
       ============================== */
    function delete_img(){
        extract($_POST);
        $path = isset($path)? $path : '';
        $full = base_app.$path;
        if(is_file($full)){
            if(unlink($full)) return json_encode(['status'=>'success']);
            return json_encode(['status'=>'failed','error'=>'Failed to delete '.$full]);
        }
        return json_encode(['status'=>'failed','error'=>'Unknown path: '.$full]);
    }
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
    case 'save_brand':
        echo $Master->save_brand();
    break;
 
    case 'save_sub_product':
        echo $Master->save_sub_product();
    break;
    case 'save_mini_product':
        echo $Master->save_mini_product();
    break;
    case 'delete_sub_product':
        echo $Master->delete_sub_product();
    break;
    case 'delete_brand':
        echo $Master->delete_brand();
    break;
    case 'save_category':
        echo $Master->save_category();
    break;
    case 'delete_category':
        echo $Master->delete_category();
    break;
    case 'save_product':
        echo $Master->save_product();
    break;
    case 'delete_product':
        echo $Master->delete_product();
    break;
    case 'save_inventory':
        echo $Master->save_inventory();
    break;
    case 'delete_inventory':
        echo $Master->delete_inventory();
    break;
    case 'register':
        echo $Master->register();
    break;
    case 'add_to_cart':
        echo $Master->add_to_cart();
    break;
    case 'update_cart_qty':
        echo $Master->update_cart_qty();
    break;
    case 'delete_cart':
        echo $Master->delete_cart();
    break;
    case 'empty_cart':
        echo $Master->empty_cart();
    break;
    case 'delete_img':
        echo $Master->delete_img();
    break;
    case 'place_order':
        echo $Master->place_order();
    break;
    case 'update_order_status':
        echo $Master->update_order_status();
    break;
    case 'pay_order':
        echo $Master->pay_order();
    break;
    case 'update_account':
        echo $Master->update_account();
    break;
    
    case 'update_client':
        echo $Master->update_client();
    break;
    case 'delete_order':
        echo $Master->delete_order();
    break;
    case 'delete_client':
        echo $Master->delete_client();
    break;
    default:
        // no action
    break;
}

?>
