<?php
use Carbon\Carbon;

defined('BASEPATH') or exit('No direct script access allowed');

class AdminController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->custom->session_validation('admin_id');
        $this->custom->session_validation('admin_name');
        $this->load->model($this->config->item('admin_dir_model') . 'admin', 'admin');
        $this->load->model('Order_type_model', 'orderType');
        $this->load->model('Order_model', 'order');
        $this->load->model('Product_price_model', 'productPrice');
        $this->load->model('Age_group_model', 'ageGroup');
        $this->load->model('Package_model',"packageModel");
        $this->load->model('Product_model',"productModel");
        $this->load->model('System2');
        $this->custom->text_editor('300px');

        if (strstr($this->uri->segment(2), "Management")) {
            $main_url = str_replace("_Management", "", $this->uri->segment(2));
            $this->system2->validateAccess(strtolower($main_url) . "_list");
        }
    }

    public function uri_segment()
    {
        return $this->uri->segment(2);
    }

    public function index()
    {
    }

    public function page($page)
    {
        
        $table          = strtolower(str_replace('_Management', '', $page));
        $admin_dir_view = $this->config->item('admin_dir_view');
        
        $data['admin_route']         = $this->config->item('admin_dir_controller');
        $data['title']               = $this->uri_segment();
        $data['content_managements'] = $this->config->item('content_management');

        if ($table == 'admin'):
            $order_by            = ["last_login" => "desc"];
        $data['Managements'] = $this->admin->select($table, '', '', '', '', $order_by); else:
            $order_by            = [$table . "_id" => "desc"];
        $data['Managements'] = $this->admin->select($table, '', '', '', '', $order_by);

        endif;
        $data['Management_List'] = $this->config->item($page . '_List');
        $data['page']            = 'Admin_Management';
        $data['function']        = 'index';

        $data['table'] = $table;
        //CUSTOMIZE PRODUCT JOIN TABLE
        
        if ($table == 'product') {
            $table                    = 'product';
            $join                     = [
                "package" => [
                    "package_id" => 'left join',
                ]
            ];
            $order_by                 = [$table . "_id" => "desc"];
            $data['tr_product_image'] = $this->admin->select('tr_product_image');
            $data['table'] = $table;

            $products = $this->admin->select($table, $join, '', '', '', $order_by)->result();

            foreach ($products as $product) {
                $productOrderTypes = $this->orderType->getOrderTypeFromProductOrderType($product->product_order_type);

                $productOrderTypeNames = [];
                foreach ($productOrderTypes as $productOrderType) {
                    $productOrderTypeNames[] = $productOrderType->order_type_name;
                }

                $product->product_order_type = implode(", ", $productOrderTypeNames);

                $productPrices      = $this->productPrice->getProductPrice($product->product_id);
                $productPriceString = [];
                foreach ($productPrices as $productPrice) {
                    $productPriceString[] = $productPrice->age_group_name . ' : ' . currency_format($productPrice->product_price, false);
                }

                $product->product_display_price = currency_format($product->product_price, false);

                $product->product_price = implode("<br>", $productPriceString);
            }

            $data['Managements'] = $products;
        } elseif ($table == 'voucher') {
            $join = [
                "product" => [
                    "product_id" => 'join',
                ]
            ];

            $condition = [
                "product_deactivated_at" => null,
            ];

            $data['voucher_detail']  = $this->admin->select('voucher_detail', $join, $condition);
            $data['hide_delete_btn'] = true;
        } elseif ($table == 'order') {
            $orders = $this->order->getOrder();

            foreach ($orders as $order) {
                $orderDetails = $this->order->getOrderDetail($order->order_id);

                $orderParticipant = [];
                foreach ($orderDetails as $orderDetail) {
                    $orderParticipant[] = $orderDetail->age_group_name . ' : ' . $orderDetail->order_detail_quantity;
                }

                $order->order_participant = implode('<br>', $orderParticipant);

                $order->order_price = $order->order_price ? currency_format($order->order_price, false) : null;
            }

            $data['Managements'] = $orders;
            $data['hide_action'] = true;
        } elseif ($table == 'tnc') {
            $data['hide_delete_btn'] = true;
            $data['hide_add_btn'] = true;
        }

        $this->load->view($admin_dir_view . 'index', $data);
    }

    public function update_form($page, $method = 'add', $id = '')
    {
        $upload_image = '';
        $table        = strtolower(str_replace('_Management', '', $page));
        $columns_data = $this->config->item($page . '_Form');
        $collection   = '';
        
        foreach ($columns_data as $title_data => $title_data_value)    :
            foreach ($title_data_value as $name_data => $name_data_value)    :
                foreach ($name_data_value as $input_type => $constraint)       :
                    $check_image = strpos($name_data, 'image');

        if (!$check_image):
                        $this->form_validation->set_rules($name_data, $title_data, $constraint);
        endif;
        
        $this->form_validation->set_rules('variable', 'Variable', 'required');

        if ($name_data == 'password'):
                        $collection[$name_data] = md5($this->input->post($name_data)); else:
                        if (!$check_image):
                            $collection[$name_data] = $this->input->post($name_data); else:
                            $upload_image = $name_data;
        endif;
        endif;
        endforeach;
        endforeach;
        endforeach;
        if ($this->form_validation->run()==false||$this->form_validation->run()==0):
        
            if ($method == 'edit' && $id != ''):
                $join            = '';
            $condition       = [
                        $table . "_id" => $id,
                    ];
            $data['id']      = $id;
            
            $data['details'] = $this->admin->select($table, $join, $condition);
            endif;

            //CUSTOMIZE CAROUSEL
            if ($table == 'carousel') {
                $data['options'] = $this->config->item('carousel_option');
            }
            //CUSTOMIZE PRODUCT
            if ($table == 'product') {
                
                $package = $this->admin->select('package')->result();
                $options = [];
                foreach ($package as $pack):
                        $options[$pack->package_id] = $pack->package_name;
                endforeach;
                
                $data['options'] = $options;

                $data['orderTypeOptions'] = $this->orderType->getProductOrderTypeOption();

                $ageGroups = $this->ageGroup->getAgeGroup();
                foreach ($ageGroups as $age_group) {
                    $inputName                                                   = 'product_price_' . $age_group->age_group_id;
                    $columns_data["Product Price " . $age_group->age_group_name] = [
                            $inputName => [
                                '<input type="text" name="' . $inputName . '" class="form-control"
                                    id="' . $inputName . '" placeholder="1000000" value="[[val]]" />' => 'numeric'
                            ]
                        ];
                }

                if (array_key_exists('details', $data)) {
                    $product = $data['details']->row();

                    $productPrices = $this->productPrice->getProductPrice($product->product_id);
                    foreach ($productPrices as $productPrice) {
                        $product->{'product_price_' . $productPrice->age_group_id} = $productPrice->product_price;
                    }

                    $data['details'] = $product;
                }
            }

            $data['admin_route']         = $this->config->item('admin_dir_controller');
            $data['title']               = $this->uri_segment();
            $data['content_managements'] = $this->config->item('content_management');
            $data['columns_data']        = $columns_data;
            $data['method']              = $method;
            $data['page']                = 'Admin_Management';
            $data['function']            = $method . '-form';
            
            $this->load->view($this->config->item('admin_dir_view') . 'index', $data); else:         
                      
                if ($upload_image != ""&&$upload_image!=null):
                    // UPLOAD IMAGE

                    $name       = $upload_image;
                    $upload_dir = $this->config->item('upload_dir');
                    $this->custom->folder_exist($upload_dir);

                    $folder     = $table;
                    $folder_dir = $upload_dir . '/' . $folder;
                    $this->custom->folder_exist($folder_dir);

                    $destination    = $folder_dir;
                    $encrypt        = 'false';
                    $col_image      = $upload_image;
                    $model          = 'admin';
                    $model_function = $method;


                    if ($method == 'edit' && $id != ''):
                        
                                $condition = [
                                    $table . "_id" => $id,
                                ];

                        if ($table == 'product'):
                            $ageGroups = $this->ageGroup->getAgeGroup();
                            $productPrices = [];
                            foreach ($ageGroups as $age_group) {
                                $inputName = 'product_price_' . $age_group->age_group_id;
                                if ($value = $this->input->post($inputName)) {
                                    $productPrices[$age_group->age_group_id] = $value;
                                }
                                //unset($collection[$inputName]);
                            }
                            $this->productPrice->updateProductPrice($id, $productPrices);
                            
                    endif;
                    
            
            if($method=="edit"&&stripos(strtolower($table),"experience")>-1){
            
                $collection = [
                    "experience_name" => $this->input->post("experience_name"),
                    "experience_description" => $this->input->post("experience_description")
                ];
            }
            if($method=="edit"&&stripos(strtolower($table),"product")>-1){
                
                $dt = $this->input->post();
                
                unset($dt["variable"]);
                foreach ($dt as $key => $value) {
                    if(stripos($key,"product_price_")>-1){
                        unset($dt[$key]);
                    }
                }
                $collection =$dt;
            }
            else{
                $dt = $this->input->post();
                unset($dt["variable"]);
                $collection =$dt;
                
            }
           
            if ($table != 'footer'):
                // echo $table;exit;     
                
                $this->admin->edit($table, $collection, $condition);
                
            endif; else:
                        $condition = '';
            endif;
            if (is_array($_FILES[$name]['name'])):
                
                        $total_array = count($_FILES[$name]['name']);
                         $this->session->set_userdata('files_session', $_FILES[$name]);
                        for ($i = 1; $i <= $total_array; $i++):
                                        $result_image = $this->custom->upload_image($name, $destination, $encrypt, $col_image, $model, $model_function, $collection, $table, $condition, $i);
                        endfor;
                        
                        $this->session->unset_userdata('files_session');else:
                            $dt = $this->input->post();
                            unset($dt["variable"]);
                            
                            foreach ($dt as $key => $value) {
                                if(stripos($key,"product_price_")>-1){
                                    unset($dt[$key]);
                                }
                            }
                            $collection =$dt;
                
                        $result_image = $this->custom->upload_image($name, $destination, $encrypt, $col_image, $model, $model_function, $collection, $table, $condition);
                        //RESIZE IMAGE
                        
                        if ($result_image['file_name'] != "" && $table == 'product') {
                            
                            $file_name  = $result_image['file_name'];
                            $file_dir   = $destination . '/' . $file_name;
                            $master_dim = 'height';
                            $size       = '100';

                            $upload_dir = $this->config->item('upload_dir');
                            $this->custom->folder_exist($upload_dir);

                            $folder     = $table;
                            $folder_dir = $upload_dir . '/' . $folder;
                            $this->custom->folder_exist($folder_dir);

                            $folder     = 'thumbnail';
                            $folder_dir = $folder_dir . '/' . $folder;
                            $this->custom->folder_exist($folder_dir);

                            $new_destination = $folder_dir;

                            $result_resize = $this->custom->resize_crop($file_name, $file_dir, $master_dim, $size, $new_destination);
                            //print_r($result_resize);
                        
                        }
                        endif; else:
                                
                                if ($method == 'add'):
                                    $this->admin->add($table, $collection);
                                endif;
                                if ($method == 'edit' && $id != ''):
                                    $condition = [
                                        $table . "_id" => $id,
                                    ];
                                    
                                if($method=="edit"&&stripos(strtolower($table),"tnc")>-1){
                                
                                    $collection = [
                                        "tnc_content" => $this->input->post("tnc_content")
                                    ];
                                }
                                
                                $this->admin->edit($table, $collection, $condition);
                            endif;
                            
            endif;
            
            $this->page($page);
        endif;
    }

    public function delete($page, $id)
    {
        $table     = strtolower(str_replace('_Management', '', $page));
        $condition = [
            $table . "_id" => $id,
        ];

        if ($table == 'product') {
            $this->admin->edit($table, ['product_deactivated_at' => Carbon::now()->toDateTimeString()], $condition);
        } elseif ($table == 'age_group') {
            $this->admin->edit($table, ['age_group_deactivated_at' => Carbon::now()->toDateTimeString()], $condition);
        } elseif ($table == 'payment_method') {
            $this->admin->edit($table, ['payment_method_deactivated_at' => Carbon::now()->toDateTimeString()], $condition);
        } else {
            $this->admin->delete($table, $condition);
        }

        if ($page == 'Tr_Product_Image_Management'):
            redirect('/' . $this->config->item('admin_dir_controller') . 'AdminController/page/Product_Management', 'refresh'); else:
            $this->page($page);
        endif;
        //echo 'deleting on model ' . $page;
        //echo ' and with key ' . $id;
    }

    public function add_product_image($id)
    {
        // UPLOAD IMAGE
        $data['admin_route']         = $this->config->item('admin_dir_controller');
        $data['title']               = $this->uri_segment();
        $data['content_managements'] = $this->config->item('content_management');
        if (isset($_FILES['product_image']['name'])):
            $name       = 'product_image';
        $table      = 'tr_product_image';
        $upload_dir = $this->config->item('upload_dir');
        $this->custom->folder_exist($upload_dir);

        $folder     = 'product';
        $folder_dir = $upload_dir . '/' . $folder;
        $this->custom->folder_exist($folder_dir);

        $destination    = $folder_dir;
        $encrypt        = 'false';
        $col_image      = 'product_image';
        $model          = 'admin';
        $model_function = 'add';
        $condition      = '';
        $collection     = [
                "product_id" => $id,
            ];

        if (is_array($_FILES['product_image']['name'])):
                $total_array = count($_FILES['product_image']['name']);
        $this->session->set_userdata('files_session', $_FILES['product_image']);
        for ($i = 1; $i <= $total_array; $i++):
                    $result_image = $this->custom->upload_image($name, $destination, $encrypt, $col_image, $model, $model_function, $collection, $table, $condition, $i);

        //RESIZE IMAGE
        if ($result_image['file_name'] != "") {
            $file_name  = $result_image['file_name'];
            $file_dir   = $destination . '/' . $file_name;
            $master_dim = 'height';
            $size       = '100';

            $upload_dir = $this->config->item('upload_dir');
            $this->custom->folder_exist($upload_dir);

            $folder     = 'product';
            $folder_dir = $upload_dir . '/' . $folder;
            $this->custom->folder_exist($folder_dir);

            $folder     = 'thumbnail';
            $folder_dir = $folder_dir . '/' . $folder;
            $this->custom->folder_exist($folder_dir);

            $new_destination = $folder_dir;

            $result_resize = $this->custom->resize_crop($file_name, $file_dir, $master_dim, $size, $new_destination);
            //print_r($result_resize);
        }

        endfor;
        $this->session->unset_userdata('files_session'); else:
                $result_image = $this->custom->upload_image($name, $destination, $encrypt, $col_image, $model, $model_function, $collection, $table, $condition);
        endif;

        redirect('/' . $this->config->item('admin_dir_controller') . 'AdminController/page/Product_Management', 'refresh'); else:
            //$message 				= "You have no Select Picture.";
            $data['product_id'] = $id;
        $admin_dir_view     = $this->config->item('admin_dir_view');
        $this->load->view('/' . $admin_dir_view . 'add_product_image', $data);
        endif;
    }
    public function pushPackage($id,$status)
    {
         $this->packageModel->pushPackage($id,$status);
         return redirect($_SERVER['HTTP_REFERER']);
    }
    public function productPackage($id,$status)
    {
         $this->productModel->pushProduct($id,$status);
         return redirect($_SERVER['HTTP_REFERER']);
    }
}
