<?php

/**
 * Fired during plugin activation
 *
 * @link       http://iv-dev.com
 * @since      1.0.0
 *
 * @package    Iv_postman
 * @subpackage Iv_postman/includes
 */

/**
 * Ajax functionality of the plugin.
 *
 * Defines and handles ajax hooks in a specific place
 *
 * @package    Iv_postman
 * @subpackage Iv_postman/ajax
 * @author     IV-dev.com <contact@iv-dev.com>
 */
class Iv_postman_Ajax
{
    /**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;


      /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
	public function __construct( $plugin_name, $version ) 
    {
        
		$this->plugin_name = $plugin_name;
		$this->version = $version;


        //register hooks
        $this->register_public_hooks();
        $this->register_private_hooks();
    }

    /**
     * Registers public ajax hooks
     * 
     * @since    1.0.0
     */
    public function register_public_hooks()
    {
        add_action('wp_ajax_nopriv_SaveAnswer', array(&$this, 'AnswerSaver'));
    }

    /**
     * Registers private ajax hooks
     * 
     * @since    1.0.0
     */
    public function register_private_hooks()
    {
        add_action('wp_ajax_SaveAnswer', array(&$this, 'AnswerSaver'));
        add_action('wp_ajax_savequestions', array(&$this, 'SaveQuestions'));
        add_action('wp_ajax_saveredirects', array(&$this, 'SaveRedirects'));

        
    }


    public function SaveRedirects() {
        $values = array();
        parse_str($_POST['data'], $values);
        $result = [];
        if (isset($values['url_contains'])) {
            foreach ($values['url_contains'] as $key => $url) {
                $result[$url] = $values['destination_selector'][$key];
            }
        }
        $res = update_option('iv_dev_redirects', json_encode($result));
        if ($res) {
            $response['status'] = 'success';
        } else {
            $response['status'] = 'error';
        }
        header('Content-type: application/json');

        echo json_encode($response);
        wp_die();
    }

    /**
     * Function to handle ActionName
     * 
     * 
     */
    public function SaveQuestions() {
        if (current_user_can('manage_options')) {
            $res = update_option('iv_dev_questions', json_encode($_POST['question']));
            if ($res) {
                $response['status'] = 'success';
            } else {
                $response['status'] = 'error';
            }
            header('Content-type: application/json');

            echo json_encode($response);
            wp_die();
        } else {
            wp_die('huh?');
        }
    }

    public function AnswerSaver() {
        header('Content-type: application/json');

        if ( ! wp_verify_nonce($_POST['_wpnonce'], 'iv_dev_answer_form')) {
            $response['status'] = 'error';
            $response['msg'] = "Invalid nonce";
            echo json_encode($response);
            wp_die();
        }
        
        $values = array();
        parse_str($_POST['data'], $values);


        $opts = get_option('iv_dev_questions');
		if (!empty($opts)) {
            $ops = json_decode($opts, true);
            $found = false;
            foreach ($ops as $o) {
                if ($o['options']['form_slug'] == $values['form_name']) {
                    $found = true;
                    $form_template = $o;
                }
            }
            if ($found == false) {
                $response['status'] = 'error';
                $response['msg'] = "Form not found!";
                echo json_encode($response);
                wp_die();
            } else {
                //form exists and is here
                $fields = [];
                if (is_array($form_template['steps']) && !empty($form_template['steps'])) {
                    foreach ($form_template['steps'] as $s) {
                        if (is_array($s) && !empty($s)) {
                            foreach ($s as $f) {
                                $fields[] = $f;
                            }
                        }
                    }
                }
                if (!empty($fields)) {
                    $validated_data = [];
                    $errors = [];
                    foreach ($fields as $f) {
                        if ($f['skip_validation'] == 1) {
                            if (isset($values[sanitize_title($f['title'])])) {
                                $validated_data[sanitize_title($f['title'])] = sanitize_text_field($values[sanitize_title($f['title'])]);
                            }
                        } else {
                            //requires validation
                            if ($f['field_type'] == 'text_field') {
                                if ((($f['required'] == 1) && !isset($values[sanitize_title($f['title'])])) OR (($f['required'] == 1) && empty($values[sanitize_title($f['title'])]))) {
                                    $errors[sanitize_title($f['title'])][] = "This field is required!";
                                } 
                                if (isset($values[sanitize_title($f['title'])])) {
                                    //is set, let's validate
                                    $th = sanitize_text_field($values[sanitize_title($f['title'])]);
                                    
                                    switch ($f['type']) {
                                        case "text":
                                            if (!empty($f['min']) && ($f['min'] > 0) ){
                                                if (strlen ($th) <= $f['min']) {
                                                    $errors[sanitize_title($f['title'])][] = "This field requires a minimum of ".$f['min'].' characters!';
                                                }
                                            }
                                            if (!empty($f['max']) && ($f['max'] > 0) ){
                                                if (strlen ($th) > $f['max']) {
                                                    $errors[sanitize_title($f['title'])][] = "This field is limited to ".$f['max'].' characters!';
                                                }
                                            }
                                        break;
                                        case "number":
                                            if (!is_numeric($th)) {
                                                $errors[sanitize_title($f['title'])][] = "Only numeric input allowed!";
                                            }

                                            if (!empty($f['min']) && ($f['min'] > 0) ){
                                                if ($th <= $f['min']) {
                                                    $errors[sanitize_title($f['title'])][] = "Please enter a number bigger than ".$f['min'];
                                                }
                                            }
                                            if (!empty($f['max']) && ($f['max'] > 0) ){
                                                if ($th > $f['max']) {
                                                    $errors[sanitize_title($f['title'])][] = "Please enter a number smaller than ".$f['max'];
                                                }
                                            }
                                        break;
                                        case "currency":
                                            if (!is_numeric($th)) {
                                                $errors[sanitize_title($f['title'])][] = "Only numeric input allowed!";
                                            }

                                            if (!empty($f['min']) && ($f['min'] > 0) ){
                                                if ($th <= $f['min']) {
                                                    $errors[sanitize_title($f['title'])][] = "Please enter a number bigger than ".$f['min'];
                                                }
                                            }
                                            if (!empty($f['max']) && ($f['max'] > 0) ){
                                                if ($th > $f['max']) {
                                                    $errors[sanitize_title($f['title'])][] = "Please enter a number smaller than ".$f['max'];
                                                }
                                            }
                                        break;
                                        case "email":
                                            if (!filter_var($th, FILTER_VALIDATE_EMAIL)) {
                                                $errors[sanitize_title($f['title'])][] = "Please enter a valid email!";
                                            }
                                            if (!empty($f['min']) && ($f['min'] > 0) ){
                                                if (strlen ($th) <= $f['min']) {
                                                    $errors[sanitize_title($f['title'])][] = "This field requires a minimum of ".$f['min'].' characters!';
                                                }
                                            }
                                            if (!empty($f['max']) && ($f['max'] > 0) ){
                                                if (strlen ($th) > $f['max']) {
                                                    $errors[sanitize_title($f['title'])][] = "This field is limited to ".$f['max'].' characters!';
                                                }
                                            }
                                        break;
                                    }
                                    if (empty($errors[sanitize_title($f['title'])])) {
                                        $validated_data[$f['title']] = $th;
                                    }
                                }

                            } elseif ($f['field_type']== 'dropdown_field') {
                                if ((($f['required'] == 1) && !isset($values[sanitize_title($f['title'])])) OR (($f['required'] == 1) && empty($values[sanitize_title($f['title'])]))) {
                                    $errors[sanitize_title($f['title'])][] = "This field is required!";
                                } else {
                                    $th = sanitize_text_field($values[sanitize_title($f['title'])]);

                                    $validated_data[$f['title']] = $th;
                                }
                            }
                        }
                    }
                    if (!empty($errors)) {
                        $response['status'] = 'error';
                        $response['msg'] = "Please validate your input!";
                        $response['data'] = $errors;

                        echo json_encode($response);
                        wp_die();
                    } else {
                        $response['status'] = 'success';
                        if (!empty($form_template['options']['form_redirect'])) {
                            $redir = $form_template['options']['form_redirect'];
                        } else {
                            $redir = false;
                        }
                        $response['redirect'] = $redir;
                        $content = '';
                        if (is_array($validated_data) && !empty($validated_data)) {
                            foreach ($validated_data as $k => $v) {
                                $content .= '<b>'.$k.':</b> '.$v.'<br />';
                            }
                        }
                        $id = wp_insert_post(array(
                            'post_title'=>'Form filled: '.$form_template['options']['form_name'].' on '.date('l, F jS, Y \a\t g:i A', time()) , 
                            'post_type'=>'questionnaire', 
                            'post_content'=>  $content,
                            'post_status' => 'publish',
                        ));
                        update_post_meta($id, 'data', $validated_data);
                        update_post_meta($id, 'form', $form_template['options']['form_slug']);
                        if (is_array($validated_data) && !empty($validated_data)) {
                            foreach ($validated_data as $k => $v) {
                                update_post_meta($id, sanitize_title($k), sanitize_text_field($v));
                            }
                        }
                        echo json_encode($response);
                        wp_die();
                    }
                } else {
                    //no fields to be verified/saved
                }

            }
        } else {
            $response['status'] = 'error';
            $response['msg'] = "No forms found!";
            echo json_encode($response);
            wp_die();
        }

        wp_die();

    }


    private function ThrowError($code, $head, $message)
    {
        status_header($code, $head);
        wp_send_json_error($message);
        wp_die();
    }
}
