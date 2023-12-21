<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Custom
{

    function session_validation($session)
    {
        $ci = &get_instance();
        if ($ci->session->userdata($session) == "") {
            $AuthController       = 'AuthController/';
            $admin_dir_controller = $ci->config->item('admin_dir_controller');
           // redirect('/' . $admin_dir_controller . $AuthController . 'login');
        }
    }

    function selected_language($language)
    {
        $ci = &get_instance();
        foreach ($language as $code => $lang):
            $ci->config->load('content_' . $code);
            $ci->config->set_item('language', $lang);
        endforeach;
    }

    function text_editor($width)
    {
        $ci = &get_instance();
        $ci->load->library('Ckeditor');
        $ci->ckeditor->basePath           = base_url() . 'asset/ckeditor/';
        $ci->ckeditor->config['language'] = 'en';
        $ci->ckeditor->config['width']    = '100%';
        $ci->ckeditor->config['height']   = $width;
    }

    function folder_exist($folder_dir)
    {
        if (!file_exists($folder_dir)):
            mkdir($folder_dir, 0755);
        endif;
    }

    function upload_image($name, $destination, $encrypt, $col_image, $model, $model_function, $data, $table, $condition = '', $number_array = '')
    {
        $ci          = &get_instance();
        $upload_type = $ci->config->item('upload_type');
        $upload_size = $ci->config->item('upload_size');

        $config['upload_path']   = $destination;
        $config['encrypt_name']  = $encrypt;
        $config['allowed_types'] = $upload_type;
        $config['max_size']      = $upload_size;


        if ($_FILES[$name]['name'] != ''):
            $ci->load->library('upload', $config);

            if ($number_array != ""):
                $number_array   = $number_array - 1;
                $file           = $ci->session->userdata('files_session');
                $files_name     = $file['name'][$number_array];
                $files_type     = $file['type'][$number_array];
                $files_tmp_name = $file['tmp_name'][$number_array];
                $files_error    = $file['error'][$number_array];
                $files_size     = $file['size'][$number_array];
                $_FILES[$name]  = [];

                $_FILES[$name]['name']     = $files_name;
                $_FILES[$name]['type']     = $files_type;
                $_FILES[$name]['tmp_name'] = $files_tmp_name;
                $_FILES[$name]['error']    = $files_error;
                $_FILES[$name]['size']     = $files_size;
                //print_r($_FILES);exit();

                if (!$ci->upload->do_upload($name)):
                    $message   = $ci->upload->display_errors();
                    $file_name = "";
                else:
                    $message          = "Upload Success";
                    $file_name        = $ci->upload->file_name;
                    $file_path        = $destination . '/' . $file_name;
                    $data[$col_image] = $file_path;

                    $ci->load->model($model);
                    if ($condition == ""):
                        $ci->$model->$model_function($table, $data);
                    else:
                        $ci->$model->$model_function($table, $data, $condition);
                    endif;
                endif;
            else:
                if (!$ci->upload->do_upload($name)):
                    $message   = $ci->upload->display_errors();
                    $file_name = "";
                else:
                    $message          = "Upload Success";
                    $file_name        = $ci->upload->file_name;
                    $file_path        = $destination . '/' . $file_name;
                    $data[$col_image] = $file_path;

                    $ci->load->model($model);
                    if ($condition == ""):
                        if ($table == 'product'):
                            $ci->load->model('Age_group_model', 'ageGroup');
                            $ci->load->model('Product_price_model', 'productPrice');
                            $ageGroups = $ci->ageGroup->getAgeGroup();

                            $productPrices = [];
                            foreach ($ageGroups as $age_group) {
                                $inputName = 'product_price_' . $age_group->age_group_id;
                                if ($value = $ci->input->post($inputName)) {
                                    $productPrices[$age_group->age_group_id] = $value;
                                }

                                unset($data[$inputName]);
                            }

                            $productId = $ci->$model->$model_function($table, $data);

                            $ci->productPrice->updateProductPrice($productId, $productPrices);
                        else:
                            $ci->$model->$model_function($table, $data);
                        endif;
                    else:
                        $ci->$model->$model_function($table, $data, $condition);
                    endif;
                endif;
            endif;
        endif;

        if (isset($message)):
            $result = [
                'message'   => $message,
                'file_name' => $file_name,
            ];
            return $result;
        endif;
    }

    function resize_crop($filename, $file_dir, $master_dim, $size, $destination)
    {
        $ci = &get_instance();
        $ci->load->library('image_lib');

        if ($master_dim == 'height'):
            $other_dim = 'width';
        else:
            $other_dim = 'height';
        endif;

        //image resize configuration
        $config['image_library']  = 'GD2';
        $config['source_image']   = $file_dir;
        $config['create_thumb']   = FALSE;
        $config['maintain_ratio'] = TRUE;
        $config['master_dim']     = $master_dim;
        $config[$master_dim]      = $size;
        $config[$other_dim]       = $size;
        $config['new_image']      = $destination . '/' . $filename;

        $ci->image_lib->initialize($config);
        if (!$ci->image_lib->resize()):
            $message_resize = $ci->image_lib->display_errors();
        else:
            $message_resize = "Resize Success";
        endif;

        // Crop the thumbnail
        $config['source_image']   = $destination . '/' . $filename;
        $config['create_thumb']   = FALSE;
        $config['maintain_ratio'] = FALSE;

        $imageSize = $ci->image_lib->get_image_properties($config['source_image'], TRUE);

        if ($imageSize['width'] > $imageSize['height']):
            $config['y_axis'] = (($imageSize['height'] / 2) - ($config['height'] / 2));
            $config['x_axis'] = (($imageSize['width'] / 2) - ($config['width'] / 2));
        else:
            $config['x_axis'] = (($imageSize['height'] / 2) - ($config['height'] / 2));
            $config['y_axis'] = (($imageSize['width'] / 2) - ($config['width'] / 2));
        endif;

        $ci->image_lib->initialize($config);
        if (!$ci->image_lib->crop()):
            $message_crop = $ci->image_lib->display_errors();
        else:
            $message_crop = "Crop Success";
        endif;

        $ci->image_lib->clear();
        unset($config);

        $result = [
            'message_resize' => $message_resize,
            'message_crop'   => $message_crop,
        ];

        return $result;
    }

    function pagination_link($offset, $segment, $link, $model, $model_function, $table, $join_table = '', $like = '')
    {
        $ci = &get_instance();
        $ci->load->model($model);

        $pagination_limit = $ci->config->item('pagination_limit');
        $limit            = [
            $pagination_limit => $offset,
        ];

        $config['cur_page']       = $ci->uri->segment($segment);
        $config['base_url']       = base_url() . $link;
        $config['total_rows']     = $ci->$model->$model_function($table, $join_table, '', $like)->num_rows();
        $config['per_page']       = $pagination_limit;
        $choice                   = $config['total_rows'] / $config['per_page'];
        $config['num_links']      = round($choice);
        $config['next_link']      = '<span class="fa fa-chevron-right"></span>';
        $config['next_tag_open']  = '<div class="pagging" >';
        $config['next_tag_close'] = '</div>';
        $config['prev_link']      = '<span class="fa fa-chevron-left"></span>';

        $ci->pagination->initialize($config);
        $result = [
            "link"     => $ci->pagination->create_links(),
            "resource" => $ci->$model->$model_function($table, $join_table, '', $like, $limit)->result(),
        ];
        return $result;
    }


}
