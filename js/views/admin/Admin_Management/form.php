  <div class="box-body">
<?php
foreach($columns_data  			 as $title_data => $title_data_value)	:
  foreach($title_data_value  as $name_data  => $name_data_value)	:
    foreach($name_data_value as $input_type => $constraint)       :
?>
      <div class="form-group">
        <input type="hidden" name="variable" value="Always" />
        <label for="<?php echo $name_data; ?>" class="col-sm-2 control-label"><?php echo $title_data; ?></label>
        <div class="col-sm-10">
          <?php
          if(strpos($input_type,"[[val]]"))
          {
            if(isset($details)):
                if(method_exists($details, 'row')):
                    $value = $details->row()->$name_data;
                else:
                    $value = $details->$name_data;
                endif;
            else:
              $value = set_value($name_data);
            endif;
            echo str_replace('[[val]]',$value,$input_type);
          }
          else if(strpos($input_type,"[[option]]"))
          {
            if(isset($details)):
                if(method_exists($details, 'row')):
                    $value_selected = $details->row()->$name_data;
                else:
                    $value_selected = $details->$name_data;
                endif;
            else:
              $value_selected = set_value($name_data);
            endif;

            $opt = "";
            foreach($options as $option => $value):
              if($option == $value_selected):
                $opt .= '<option selected value="'.$option.'" >'.$value.'</option> ';
              else:
                $opt .= '<option value="'.$option.'" >'.$value.'</option> ';
              endif;
            endforeach;
            echo str_replace('[[option]]',$opt,$input_type);
          }
          else if(strpos($input_type,"[[orderTypeOption]]"))
          {
            if(isset($details)):
                if(method_exists($details, 'row')):
                    $value_selected = $details->row()->$name_data;
                else:
                    $value_selected = $details->$name_data;
                endif;
            else:
              $value_selected = set_value($name_data);
            endif;

            $opt = "";
            foreach($orderTypeOptions as $option => $value):
              if($option == $value_selected):
                $opt .= '<option selected value="'.$option.'" >'.$value.'</option> ';
              else:
                $opt .= '<option value="'.$option.'" >'.$value.'</option> ';
              endif;
            endforeach;
            echo str_replace('[[orderTypeOption]]',$opt,$input_type);
          }
          else if( $input_type == "[[textarea]]" )
          {
            if(isset($details)):
                if(method_exists($details, 'row')):
                    $value    = $details->row()->$name_data;
                else:
                    $value    = $details->$name_data;
                endif;
              $textarea = $this->ckeditor->editor($name_data,$value);
            else:
              $value    = set_value($name_data);
              $textarea = $this->ckeditor->editor($name_data,html_entity_decode($value));
            endif;

            echo str_replace('[[textarea]]',$textarea,$input_type);
          }
          else
          {
            if(isset($details)):
                if(method_exists($details, 'row')):
                    $value    = $details->row()->$name_data;
                else:
                    $value    = $details->$name_data;
                endif;
            else:
              $value = "";
            endif;
            echo $input_type."<br/>";

            if($value != ""):
              echo "<img width='100' src='".base_url().$value."' ";
            endif;
          }
          ?>

        </div>
      </div>
<?php
    endforeach;
  endforeach;
endforeach;

?>

    <div class="form-group">
      <center><?php echo validation_errors(); ?></center>
    </div>

<!--
    <div class="form-group">
      <label for="inputEmail3" class="col-sm-2 control-label">Email</label>

      <div class="col-sm-10">
        <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
      </div>
    </div>

    <div class="form-group">
      <label for="inputPassword3" class="col-sm-2 control-label">Password</label>

      <div class="col-sm-10">
        <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
      </div>
    </div>
  </div>
-->
  <!-- /.box-body -->
  <div class="box-footer">
    <button type="submit" class="btn btn-default" onclick="window.history.back()">Cancel</button>
    <button type="submit" class="btn btn-success pull-right"><?php echo $method; ?></button>
  </div>
  <!-- /.box-footer -->
