
<?php 
    $total_steps = count($form['steps']);

?>
<div class="question_wrapper <?php
    if (isset($form['options']['full_width']) && ($form['options']['full_width'] == 1)) {
        echo ('full_width');
    }
    ?>">
   
    <form class="ui form postmanform" action= "<?=admin_url( 'admin-ajax.php')?>"  >
        <?php echo wp_nonce_field( 'iv_dev_answer_form'); ?>
        <input type="hidden" name="form_name" value ="<?=$form['options']['form_slug']?>" >
        <div class="question_form <?php
        if (isset($form['options']['class'])) {
            echo $form['options']['class'];
        }
        ?>"> 
            <?php

$steps = [];
                if (isset($form['steps']) && !empty($form['steps'])) {
                    
                    foreach ($form['steps'] as $k=> $s) {
                        //check if only one step 
                        if ($step !== false) {
                            if (($step - 1) !== $k) {
                                //not this step
                                $s = [];
                            }                            
                        }
                        if (is_array($s) && !empty($s)) {
                            foreach ($s as $f) {
                               
                                $steps[$k][$f['row']][] = $f;
                            }
                        }
                    }
                    if (!empty($steps)) {
                        foreach ($steps as $k=> $s) {
                            echo '<div class="step">';
                            $counter = 0;
                            while ($counter < 6) {
                                echo '<div class="field">';
                                echo '<div class="fields">';
                                if (!empty ($s[$counter]) && is_array($s[$counter])) {
                                    //row not empty
                                    foreach ($s[$counter] as $f) {
                                        //iterate fields
                                        echo '<div class="'.$f['width'].' wide field">';
                                        echo '<div class="ui labeled input">';
                                        if ($f['field_type'] == 'text_field') {
                                            $attrs ='';
                                            $ftype= '';
                                            $pretext = '';
                                            switch ($f['type']) {
                                                case "number":
                                                    if (!empty($f['min'])) {
                                                        $attrs .= 'min="'.$f['min'].'" ';
                                                    }
                                                    if (!empty($f['max'])) {
                                                        $attrs .= 'max="'.$f['max'].'" ';
                                                    }
                                                    $ftype = 'number';
                                                break;
                                                case "currency":
                                                    if (!empty($f['min'])) {
                                                        $attrs .= 'min="'.$f['min'].'" ';
                                                    }
                                                    if (!empty($f['max'])) {
                                                        $attrs .= 'max="'.$f['max'].'" ';
                                                    }
                                                    $ftype = 'number';
                                                    $pretext = '<label for="'.sanitize_title($f['title']).'" class="ui label">'.of_get_option('currency_symbol').'</label>';
                                                break;
                                                case "text":
                                                    if (!empty($f['min'])) {
                                                        $attrs .= 'minlength="'.$f['min'].'" ';
                                                    }
                                                    if (!empty($f['max'])) {
                                                        $attrs .= 'maxlength="'.$f['max'].'" ';
                                                    }
                                                    $ftype = 'text';
                                                break;
                                                case "email":
                                                    if (!empty($f['min'])) {
                                                        $attrs .= 'minlength="'.$f['min'].'" ';
                                                    }
                                                    if (!empty($f['max'])) {
                                                        $attrs .= 'maxlength="'.$f['max'].'" ';
                                                    }
                                                    $ftype = 'email';
                                                break;
                                            }
                                            if ($f['required'] == 1) {
                                                $attrs .= ' required';
                                            }
                                            echo $pretext.'<input type="'.$ftype.'" name="'.sanitize_title($f['title']).'" class="postman_input" placeholder="'.$f['title'].'" '.$attrs.'>';
                                        } elseif ($f['field_type'] == 'dropdown_field') {
                                            echo '<select name="'.sanitize_title($f['title']).'" class="ui fluid dropdown">';
                                            $array = explode(',',$f['values']);
                                            if (!empty($array) && is_array($array)) {
                                                foreach ($array as $s) {
                                                    if (strlen(trim($s)) > 1) {
                                                        echo '<option>'.$s.'</option>';
                                                    }
                                                }
                                            }
                                          echo '</select>';
                                        }
                                        echo '</div>';
                                        echo '</div>';
                                    }
                                }
                                echo '</div>';
                                echo '</div>';
                                $counter ++;
                            }

                            echo "</div>";
                        }
                    }
                    $counter = 1;
                }
            ?>
        <?php
            if ($step == $total_steps) {
                echo '<button class="ui button basic">Follow</button>';
            }
        ?>
        </div>   
    </div> 
</form>

