


<?php
  $options = get_option('iv_dev_questions');
  if (strlen($options) > 3) {
    $questions = json_decode($options, true);
  }

$question_html = '<div class="meta-box-sortables ui-sortable question_div">

<div class="postbox">

  <h2><span>Question options</span></h2>

  <div class="inside">
    <div class="single">
        <div class="options_container">
        <div class="row">
              <div class="col-md-5" style="text-align:right">
                  Human readable form name:
              </div>
              <div class="col-md-5">
                  <input type="text" placeholder="Form name" name="form_name[]">
              </div>
          </div>
        
        <div class="row">
              <div class="col-md-5" style="text-align:right">
                  Form slug:
              </div>
              <div class="col-md-5">
                  <input type="text" placeholder="Form slug (required if you want to show the form, easier if it\' a single word)" name="form_slug[]">
              </div>
          </div>
          <div class="row">
              <div class="col-md-5" style="text-align:right">
                  CSS class:
              </div>
              <div class="col-md-5">
                  <input type="text" placeholder="CSS class" name="class[]">
              </div>
          </div>
          <div class="row">
          <div class="col-md-5" style="text-align:right">
             Redirect after finish:
          </div>
          <div class="col-md-5">
              <input type="text" placeholder="'.get_site_url().'" name="form_redirect[]">
          </div>
      </div>
          <div class="row">
              <div class="col-md-5" style="text-align:right">
                  Full width:
              </div>
              <div class="col-md-5">
                <input type="checkbox" style="width: auto" name="full_width[]">
              </div>
          </div>
        </div>  
        <hr />
        <div class="container steps">
        
        </div>
        
        <div class="button step_adder" >
        <span class="dashicons dashicons-plus-alt"></span>Add a new step
        </div>

    </div>
  </div>
  <!-- .inside -->

</div>
<!-- .postbox -->

</div>
<!-- .meta-box-sortables .ui-sortable -->
';

$step_html = '<div class="step row">
          
<div class="col-md-12 fields">

</div>
<div class="col-md-12">
  <div class="button field_adder">
    <span class="dashicons dashicons-plus-alt"></span>Add a new field
  </div>
  <div class="button step_remover">
  <span class="dashicons dashicons-dismiss"></span>Remove the step
</div>
</div></div>';

  $splitter_html = '<div class="field container">
  <input type="hidden" name="field_type" value="divider">
    <div class="row">
        <div class="col-md-5" style="text-align:right">
            Row:
        </div>
        <div class="col-md-5">
            <select name="row[]">
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
            </select>
        </div>
    </div>

    <div class="row">
    <div class="col-md-5" style="text-align:right">
        Width:
    </div>
    <div class="col-md-5">
        <select name="width[]">
          <option value="one">1</option>
          <option value="two">2</option>
          <option value="three">3</option>
          <option value="four">4</option>
          <option value="five">5</option>
          <option value="six">6</option>
          <option value="seven">7</option>
          <option value="eight">8</option>
          <option value="nine">9</option>
          <option value="ten">10</option>
          <option value="eleven">11</option>
          <option value="twelve">12</option>
          <option value="thirteen">13</option>
          <option value="fourteen">14</option>
          <option value="fifteen">15</option>
          <option value="sixteen">16</option>
        </select>
    </div>
  </div>
  
  <div class=" col-md-12">
    <div class="button field_remover" >
    <span class="dashicons dashicons-dismiss"></span>Remove me!
    </div>
  </div>
  
  </div>
  ';

  $textfield_html ='<div class="field container">
  <input type="hidden" name="field_type" value="text_field">
  <div class="row">
      <div class="col-md-5" style="text-align:right">
          Title:
      </div>
      <div class="col-md-5">
          <input type="text" placeholder="Title" name="title[]">
      </div>
  </div>
  
  <div class="row">
      <div class="col-md-5" style="text-align:right">
          Row:
      </div>
      <div class="col-md-5">
          <select name="row[]">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
          </select>
      </div>
  </div>

  <div class="row">
  <div class="col-md-5" style="text-align:right">
      Width:
  </div>
  <div class="col-md-5">
      <select name="width[]">
        <option value="one">1</option>
        <option value="two">2</option>
        <option value="three">3</option>
        <option value="four">4</option>
        <option value="five">5</option>
        <option value="six">6</option>
        <option value="seven">7</option>
        <option value="eight">8</option>
        <option value="nine">9</option>
        <option value="ten">10</option>
        <option value="eleven">11</option>
        <option value="twelve">12</option>
        <option value="thirteen">13</option>
        <option value="fourteen">14</option>
        <option value="fifteen">15</option>
        <option value="sixteen">16</option>
      </select>
  </div>
</div>

  <div class="row">
      <div class="col-md-5" style="text-align:right">
          Type:
      </div>
      <div class="col-md-5">
          <select name="type[]">
            <option value="text">Text</option>
            <option value="number">Number</option>
            <option value="currency">Currency</option>

            <option value="email">E-mail</option>
          </select>
      </div>
  </div>

  
 


  <div class="row">
      <div class="col-md-5" style="text-align:right">
          Minimum (if text/email this is minimum length, if number/currency this is minimal value):
      </div>
      <div class="col-md-5">
          <input type="number" placeholder="0" name="min[]">
      </div>
  </div>

  <div class="row">
      <div class="col-md-5" style="text-align:right">
          Maximum (if text/email this is maximum length, if number/currency this is maximal value):
      </div>
      <div class="col-md-5">
          <input type="number" placeholder="0" name="max[]">
      </div>
  </div>

  <div class="row">
      <div class="col-md-5" style="text-align:right">
          CSS class:
      </div>
      <div class="col-md-5">
          <input type="text" placeholder="css class" name="class[]" >
      </div>
  </div>

  <div class="row">
      <div class="col-md-5" style="text-align:right">
          Additional options:
      </div>
      <div class="col-md-5">
        <input type="checkbox" style="width: auto" name="skip_validation[]">Skip validation<br />
        <input type="checkbox" style="width: auto" name="required[]">Required<br />
      </div>
  </div>


  <div class=" col-md-12">
    <div class="button field_remover" >
    <span class="dashicons dashicons-dismiss"></span>Remove me!
    </div>
  </div>
  
</div>';


$dropdown_html ='<div class="field container">
<input type="hidden" name="field_type" value="dropdown_field">
<div class="row">
    <div class="col-md-5" style="text-align:right">
        Title:
    </div>
    <div class="col-md-5">
        <input type="text" placeholder="Title" name="title[]">
    </div>
</div>

<div class="row">
    <div class="col-md-5" style="text-align:right">
        Row:
    </div>
    <div class="col-md-5">
        <select name="row[]">
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
        </select>
    </div>
</div>

<div class="row">
<div class="col-md-5" style="text-align:right">
    Width:
</div>
<div class="col-md-5">
    <select name="width[]">
      <option value="one">1</option>
      <option value="two">2</option>
      <option value="three">3</option>
      <option value="four">4</option>
      <option value="five">5</option>
      <option value="six">6</option>
      <option value="seven">7</option>
      <option value="eight">8</option>
      <option value="nine">9</option>
      <option value="ten">10</option>
      <option value="eleven">11</option>
      <option value="twelve">12</option>
      <option value="thirteen">13</option>
      <option value="fourteen">14</option>
      <option value="fifteen">15</option>
      <option value="sixteen">16</option>
    </select>
</div>
</div>

<div class="row">
    <div class="col-md-5" style="text-align:right">
        Please enter comma separated values
    </div>
    <div class="col-md-5">
        <input type="text" placeholder="Example,Value,Etc" name="values[]" >
    </div>
</div>

<div class="row">
    <div class="col-md-5" style="text-align:right">
        Additional options:
    </div>
    <div class="col-md-5">
      <input type="checkbox" style="width: auto" name="skip_validation[]">Skip validation<br />
      <input type="checkbox" style="width: auto" name="required[]">Required<br />
    </div>
</div>


<div class=" col-md-12">
  <div class="button field_remover" >
  <span class="dashicons dashicons-dismiss"></span>Remove me!
  </div>
</div>

</div>';




?>








<div class="wrap">

	<div id="icon-options-general" class="icon32"></div>
	<h1><?php esc_attr_e( 'PostMan form editor', 'WpAdminStyle' ); ?></h1>

	<div id="poststuff">

		<div id="post-body" class="metabox-holder columns-2">

			<!-- main content -->
			<div id="post-body-content">
            <?php
        if (!empty($questions)) {

          $html = '';
          foreach ($questions as $q) {
            $temp_html = '';
            $temp_html = $question_html;
            if (!empty($q['options'])) {
              if (isset($q['options']['arrays'])) {
                $o_arrays = $q['options']['arrays'];
              } else {
                $o_arrays = [];
              }
              if (isset( $q['options']['bools'])) {
                $o_booleans = $q['options']['bools'];
              } else {
                $o_booleans = [];
              }
              unset($q['options']['arrays']);
              unset($q['options']['bools']);
              foreach ($q['options'] as $k => $s) {
                //foreach option
                //$temp_html = str_replace($k.'[]"', $k.'[]" value="'.$s.'"', $temp_html);


                if (!in_array($k, $o_arrays)) {
                  //its not an array it's a single
                  if (!in_array($k, $o_booleans)) {
                    $temp_html = str_replace('name="'.$k.'[]"', 'name="'.$k.'[]" value="'.$s.'"', $temp_html);
                  } else {
                    if ($s == 1) {

                      $temp_html = str_replace('name="'.$k.'[]"','name="'.$k.'[]" checked="checked"', $temp_html);
                    }
                  }
                } else {
                  $temp_html = str_replace('value="'.$s.'"','value="'.$s.'" selected', $temp_html);
                }
              }
            }


            $temp_steps ='';
            //get all steps
            if (!empty($q['steps'])) {
              $step = 1;
              foreach ($q['steps'] as $f) {
                $temp_fields = '';
                $temp_step = $step_html;

                $content = 'Step shortcode: <input type="text" value="[iv_postman_display slug=\''.$q['options']['form_name'].'\' step=\''.$step.'\']" class="regular-text code stepcode"> <br /> <br /><div class="col-md-12 fields">';
                $temp_step = str_replace('<div class="col-md-12 fields">', $content, $temp_step);
                //add 
                if (!empty($f)) {
                  //has fields
                  foreach ($f as $ss) {
                    if ($ss['field_type'] == 'text_field') {
                      $field = $textfield_html;
                    } elseif ($ss['field_type'] == 'divider') {
                      $field = $splitter_html;
                    } elseif ($ss['field_type'] == 'dropdown_field') {
                      $field = $dropdown_html;
                    }
                    if (isset($ss['arrays'])) {
                      $arrays = $ss['arrays'];
                      unset($ss['arrays']);
                    } else {
                      $arrays =[];
                    }

                    if (isset($ss['bools'])) {
                      $booleans = $ss['bools'];
                      unset($ss['bools']);
                    } else {
                      $booleans = [];
                    }
                    unset($ss['field_type']);
    
                    foreach ($ss as $k => $s) {
                      //step through each field option
                      if (!in_array($k, $arrays)) {
                        //its not an array it's a single
                        if (!in_array($k, $booleans)) {
                          $field = str_replace('name="'.$k.'[]"', 'name="'.$k.'[]" value="'.$s.'"', $field);
                        } else {
                          if ($s == 1) {

                            $field = str_replace('name="'.$k.'[]"','name="'.$k.'[]" checked="checked"', $field);
                          }
                        }
                      } else {
                        $field = str_replace('value="'.$s.'"','value="'.$s.'" selected', $field);
                      }
                    } 
                    $temp_fields .= $field;
                  }
                  //fields read
                  $temp_steps.= str_replace('fields">', 'fields">'.$temp_fields, $temp_step);
                }
                $step ++;
              }
              
            }
            $temp_html = str_replace('steps">', 'steps">'.$temp_steps, $temp_html);
            $html .= $temp_html;
          }
          echo $html;
        }
            ?>
			

			</div>
			<!-- post-body-content -->

			<!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container">

				<div class="meta-box-sortables">

					<div class="postbox">

						<h2><span><?php esc_attr_e(
									'Sidebar Content Header', 'WpAdminStyle'
								); ?></span></h2>

						<div class="inside">
                <p>
                  <b>Notice:</b> You can sort individual fields inside a step by simply dragging and dropping them, but their position on frontend is determined by their row.
                </p>
							<p>  
                <?=submit_button( $text = 'Create new form', $type = '', $name = 'create_new_form', $wrap = true, $other_attributes = null )?>
                <?=submit_button( $text = 'Save forms', $type = 'primary', $name = 'save_forms', $wrap = true, $other_attributes = null )?>
              </p>
						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables -->

			</div>
			<!-- #postbox-container-1 .postbox-container -->

		</div>
		<!-- #post-body .metabox-holder .columns-2 -->

		<br class="clear">
	</div>
	<!-- #poststuff -->

</div> <!-- .wrap -->






<div style="display:none">

  <div id="question_template_holder">
    <?=$question_html?>
  </div>

<div id="single_step_template">
  <?=$step_html?>
</div>
  
  <div id="text_field_template">
    <?=$textfield_html?>
  </div>

  <div id="splitter_template">
    <?=$splitter_html?>
  </div>

  <div id="dropdown_template">
    <?=$dropdown_html?>
  </div>

  
</div>





<script>
  jQuery(document).ready(function($) {/*
    toastr.info(
      "<?=__('You can review and edit your settings here', 'iv-pythia')?>",
      "<?=__('Welcome to Pythia control panel!', 'iv-pythia')?>",
      {
        "closeButton": true,
        "positionClass": "toast-top-center",
        "timeOut": "0",
        "extendedTimeOut": "0",
      });*/
    });
</script>
