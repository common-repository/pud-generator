<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.pudsoft.in/
 * @since      1.0.0
 *
 * @package    Pud_Generator
 * @subpackage Pud_Generator/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Pud_Generator
 * @subpackage Pud_Generator/admin
 * @author     Pud Quiz <pudquiz@gmail.com>
 */
class Pud_Generator_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $pud_generator    The ID of this plugin.
     */
    private $pud_generator;

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
     * @param      string    $pud_generator       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($pud_generator, $version)
    {
        $this->pud_generator = $pud_generator;
        $this->version       = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles($hook)
    {
        $pos = strpos($hook, 'generator');
        if ($pos === false)  {
                return;
        } 
        wp_enqueue_style($this->pud_generator . '_datatable', plugin_dir_url(__FILE__) . 'css/datatables.min.css', array(), $this->version, 'all');
        wp_enqueue_style($this->pud_generator . '_modal', plugin_dir_url(__FILE__) . 'css/jquery.modal.min.css', array(), $this->version, 'all');
        wp_enqueue_style($this->pud_generator, plugin_dir_url(__FILE__) . 'css/pud-generator-admin.css', array(), $this->version, 'all');
        wp_enqueue_style($this->pud_generator . '_tooltip', plugin_dir_url(__FILE__) . 'css/jquery.tooltip.css', array(), $this->version, 'all');
        wp_enqueue_style($this->pud_generator . 'font-awesome', plugin_dir_url(__FILE__) . 'css/font-awesome-4.7.0/css/font-awesome.min.css');

        if (is_rtl()) {
            wp_enqueue_style($this->pud_generator . '_rtl', plugin_dir_url(__FILE__) . 'css/pud-generator-rtl-admin.css', array(), $this->version, 'all');
        }
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts($hook)
    {
        $pos = strpos($hook, 'generator');
        if ($pos === false)  {
                return;
        } 
        wp_enqueue_script($this->pud_generator . '-datatable', plugin_dir_url(__FILE__) . 'js/datatables.min.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->pud_generator . '-tooltip', plugin_dir_url(__FILE__) . 'js/jquery.tooltip.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->pud_generator . '-modal', plugin_dir_url(__FILE__) . 'js/jquery.modal.min.js', array('jquery'), $this->version, false);
        
        wp_enqueue_script($this->pud_generator . '-timeago', plugin_dir_url(__FILE__) . 'js/jquery.timeago.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->pud_generator . '-textrange', plugin_dir_url(__FILE__) . 'js/jquery-textrange.js', array('jquery'), $this->version, false);
  
        wp_enqueue_script($this->pud_generator, plugin_dir_url(__FILE__) . 'js/pud-generator-admin.js', array('jquery'), $this->version, false);

         wp_enqueue_script($this->pud_generator . '-angular', plugin_dir_url(__FILE__) . 'js/angular.min.js', array('jquery'), $this->version, false);

    }

    public function menu()
    {
        add_menu_page(null, 'Pud Generator', 'edit_posts', 'pud_generator', 'pud_generator', 'dashicons-media-spreadsheet');
        add_submenu_page('pud_generator', 'New Generator', 'New Generator', 'edit_posts', 'pud_generator', 'pud_generator');
        add_submenu_page('pud_generator', 'General', 'General', 'edit_posts', 'pud_general', 'pud_general');
        add_submenu_page('pud_generator', 'All Generator', 'All Generator', 'edit_posts', 'pud_manage', 'pud_manage');
        add_submenu_page('pud_generator', 'Placeholders', 'Placeholders', 'edit_posts', 'pud_placeholder', 'pud_placeholder');

        add_action('admin_init', 'pud_generator_settings');
    }

    public function load_generator()
    {
        global $wpdb;
        $result['data'] = array();
        $myresult       = $wpdb->get_results("SELECT * FROM " . PUD_GENERATOR_TABLE . " where 1 ", OBJECT_K);
        $i              = 0;
        foreach ($myresult as $key => $value) {
            $result['data'][$i][] = $value->id;
            $result['data'][$i][] = esc_html($value->name);
            $result['data'][$i][] = esc_html($value->page_title);
            $result['data'][$i][] = intval( $value->page_combination);
            $result['data'][$i][] = intval( $value->max_page);
            $result['data'][$i][] = esc_html(ucfirst($value->post_type));
            $result['data'][$i][] = esc_html($this->get_display_name($value->author_id));
            $result['data'][$i][] = esc_html(get_pud_status($value->post_status));
            $result['data'][$i][] = esc_html(get_pud_visibility($value->visibility));
            $status               = esc_html(get_generator_status($value->status));
            if ($value->status == 'error') {
                $status = '<a href="#" class="tooltip generator_log" data-tooltip="Click Here For Log"    ><span class="pud-label pud-danger">' . $status . '</span></a>';
            } else if ($value->status == 'completed') {
                $status = '<a href="#" class="tooltip generator_log"  data-tooltip="Click Here For Log"   ><span class="pud-label pud-success">' . $status . '</span></a>';
            } else if ($value->status == 'progress') {
                $status = '<a href="#" data-tooltip="Click Here For Log" class="tooltip generator_log"  ><span class="pud-label pud-info">' . $status . '</span></a>';
            } else {
                $status = '<span class="pud-label pud-other">' . $status . '</span>';
            }
            $result['data'][$i][] = $status;
            $result['data'][$i][] = $value->created;
            $result['data'][$i][] = $value->updated;
            $result['data'][$i][] = '';
            $i++;
        }
        $this->json_return($result);

    }

    public function load_generator_log()
    {
        global $wpdb;
        $id      = $this->filterIntData('id');
        $last_id = $this->filterIntData('last_id');
        $page    = $this->filterIntData('page');

        $condition = " generator_id='" . $id . "' ";
        if (!empty($last_id)) {
            $condition .= " AND id > '" . $last_id . "' ";
        }
        $myresult = $wpdb->get_results("SELECT id,content, created FROM " . PUD_GENERATOR_LOG_TABLE . " where " . $condition . " ORDER BY id ASC ", OBJECT_K);

        $log_print = "";
        foreach ($myresult as $key => $value) {
            $value->content .= '<div class="pud-timeago" ><i class="fa fa-clock-o"></i>&nbsp;<time class="timeago" datetime="' . $value->created . '" ></time></div>';
            $log_print .= '<div class="pud_log_record" ><i class="fa fa-arrow-circle-right"></i>&nbsp;' . $value->content . "</div>";
            $last_id = $value->id;
        }
        $generator = $wpdb->get_row("SELECT status FROM " . PUD_GENERATOR_TABLE . " where id='" . $id . "'");
        $load_more = 0;

        if ($generator->status == "progress") {
            $load_more = 1;
        }

        if ($page == 1 && empty($log_print)) {
            $log_print = '<div class="pud-no-records" >No record Found</div>';
        } else if ($page == 1 && !empty($log_print)) {
            $log_print = '<div class="pud-log-title" ><h2>Generator Log</h2></div>' . $log_print;
        }

        $result = array('error' => 0, 'message' => $log_print, 'load_more' => $load_more, 'last_id' => $last_id);
        $this->json_return($result);
    }

    public function get_display_name($user_id)
    {
        if (!$user = get_userdata($user_id)) {
            return false;
        }

        return $user->data->display_name;
    }
    public function load_placeholder()
    {
        global $wpdb;
        $result['data'] = array();
        $myresult       = $wpdb->get_results("SELECT * FROM " . PUD_PLACEHOLDER_TABLE . " where 1 ", OBJECT_K);
        $final_arr      = array();
        $i              = 0;
        foreach ($myresult as $key => $value) {
            $final_arr[$i][] = $value->id;
            $final_arr[$i][] = stripslashes(esc_html($value->name));
            $final_arr[$i][] = esc_html($value->placeholder);
            $final_arr[$i][] = stripslashes(esc_html($value->tags));
            $final_arr[$i][] = $value->created;
            $final_arr[$i][] = $value->updated;
            $i++;
        }
        $result['data'] = $final_arr;
        $this->json_return($result);
    }

    public function delete_generator()
    {
        global $wpdb;
        $id      = $this->filterIntData('id');
        $success = $wpdb->delete(PUD_GENERATOR_TABLE, array('id' => $id), array('%d'));
        if ($success) {
            $result = array('error' => 0, 'message' => 'Record deleted successfully');
        } else {
            $result = array('error' => 1, 'message' => 'Unable to delete the record, please try again');
        }
        $this->json_return($result);
    }

    public function delete_placeholder()
    {
        global $wpdb;
        $id      = $this->filterIntData('id');
        $success = $wpdb->delete(PUD_PLACEHOLDER_TABLE, array('id' => $id), array('%d'));
        if ($success) {
            $result = array('error' => 0, 'message' => 'Placeholder deleted successfully');
        } else {
            $result = array('error' => 1, 'message' => 'Unable to delete the record, please try again');
        }
        $this->json_return($result);
    }

    public function add_place_holder()
    {
        global $wpdb;
        $name        = $this->filterTextData('name');
        $placeholder = $this->filterTextData('placeholder');
        $post_tags   = $_POST['tags'];
        $tags        = "";
        if (is_array($post_tags)) {
            $tags = implode("|", $post_tags);
        }
        $data = array(
            'name'        => array($name, 'Name'),
            'placeholder' => array($placeholder, 'Placeholder'),
            'tags'        => array($tags, 'Tags'),
        );
        $status = $this->validation_fields($data);
        if ($status['error']) {
            $this->json_return($status);
        }
        $is_exist    = 1;
        $i           = 1;
        $placeholder = strtolower(trim(substr($placeholder, 0, 35)));
        $placeholder = str_replace(' ', '-', $placeholder);

        $org_placeholder = $placeholder;
        do {
            $myresult = $wpdb->get_results("SELECT id FROM " . PUD_PLACEHOLDER_TABLE . " where placeholder='" . $org_placeholder . "'");
            if (empty($myresult)) {
                $is_exist = 0;
            } else {
                $org_placeholder = $placeholder . '-' . $i;
                $i++;
            }
        } while ($is_exist);

        $tags = $this->filter_tags($tags);

        $placeholder = $org_placeholder;
        $success     = $wpdb->insert(PUD_PLACEHOLDER_TABLE, array(
            "name"        => $name,
            'placeholder' => $placeholder,
            'tags'        => $tags,
            'created'     => date('Y-m-d H:i:s'),
            'updated'     => date('Y-m-d H:i:s'),
        ));

        if ($success) {
            $result = array('error' => 0, 'message' => 'Placeholder added successfully');
        } else {
            $result = array('error' => 1, 'message' => 'Unable to add the record, please try again');
        }
        $this->json_return($result);
    }

    public function edit_place_holder()
    {
        global $wpdb;
        $name        = $this->filterTextData('name');
        $placeholder = $this->filterTextData('placeholder');
        $id          = $this->filterIntData('id');
        $post_tags   = $_POST['tags'];
        $tags        = "";
        if (is_array($post_tags)) {
            $tags = implode("|", $post_tags);
        }

        $data = array(
            'name'        => array($name, 'Name'),
            'placeholder' => array($placeholder, 'Placeholder'),
            'tags'        => array($tags, 'Tags'),
            'id'          => array($id, 'Key'),
        );
        $status = $this->validation_fields($data);
        if ($status['error']) {
            $this->json_return($status);
        }

        $tags = $this->filter_tags($tags);

        $success = $wpdb->update(PUD_PLACEHOLDER_TABLE, array(
            "name"    => $name,
            'tags'    => $tags,
            'updated' => date('Y-m-d H:i:s'),
        ), array('id' => $id));

        if ($success) {
            $result = array('error' => 0, 'message' => 'Placeholder updated successfully');
        } else {
            $result = array('error' => 1, 'message' => 'Unable to update the record, please try again');
        }
        $this->json_return($result);
    }
    public function validation_fields($data)
    {
        $errors  = array('error' => 0, 'message' => 'Required field can not be left blank');
        $message = '';
        foreach ($data as $key => $value) {
            if ($value[0] == '') {
                $errors['error'] = 1;
                $message .= $value[1] . "\n";
                break;
            }
        }
        $errors['message'] .= "\n" . $message;
        return $errors;
    }

    public function json_return($result)
    {
        echo json_encode($result);
        exit;
    }

    public function filterTextData($key, $default = '')
    {
        $v = isset($_POST[$key]) ? $_POST[$key] : $default;
        return sanitize_text_field($v);
    }

    public function filterIntData($key, $default = '')
    {
        $v = isset($_POST[$key]) ? $_POST[$key] : $default;
        return intval($v);
    }

    public function filterHtmlData($key, $default = '')
    {
        $v = isset($_POST[$key]) ? $_POST[$key] : $default;
        return wp_kses_post($v);
    }

    public function filter_tags($tags)
    {
        $final_arr = array();
        $tags_arr  = explode("|", $tags);
        foreach ($tags_arr as $key => $value) {
            $value = trim($value);
            if ($value == "") {
                continue;
            }
            $final_arr[] = trim(wp_kses_data($value));
        }
        $final_arr = array_unique($final_arr);
        return implode("|", $final_arr);
    }

    public function save_generator()
    {
        global $wpdb;
        $postData = json_decode(file_get_contents('php://input'), true);
        $_POST = $postData;
        $name             = $this->filterTextData('pud_name');
        $author           = $this->filterIntData('pud_default_author');
        $max_page         = $this->filterIntData('pud_max_page');
        $content          = $this->filterHtmlData('pud_content');
        $excerpt          = $this->filterTextData('pud_page_excerpt');
        $post_status      = $this->filterTextData('pud_page_status');
        $type             = $this->filterTextData('pud_page_type');
        $visibility       = $this->filterTextData('pud_page_visibility');
        $title            = $this->filterTextData('pud_page_title');
        $placeholders     = $_POST['names'];
        $pud_type         = $this->filterTextData('pud_type');
        $pud_id           = $this->filterIntData('pud_id');
        $generator_status = $this->filterTextData('generator_status');

        $data = array(
            'pud_name'            => array($name, 'Name'),
            'pud_page_title'      => array($title, 'Title'),
            'pud_content'         => array($content, 'Content'),
            'pud_default_author'  => array($author, 'Author'),
            'pud_max_page'        => array($max_page, 'Max Pages'),

            'pud_page_status'     => array($post_status, 'Status'),
            'pud_page_type'       => array($type, 'Type'),
            'pud_page_visibility' => array($visibility, 'Visibility'),
        );
        $status = $this->validation_fields($data);
        if ($status['error']) {
            $this->json_return($status);
        }
        if (empty($generator_status)) {
            $generator_status = "pending";
        }

        $page_combination = 0;
        $user_id          = get_current_user_id();

        if ($pud_type == 'edit' && $pud_id) {
            $success = $wpdb->update(PUD_GENERATOR_TABLE, array(
                "user_id"      => $user_id,
                "name"         => $name,
                'post_type'   => $type,
                'page_title'   => $title,
                'page_excerpt' => $excerpt,
                'page_content' => $content,
                'max_page'     => $max_page,
                'author_id'    => $author,
                'post_status'  => $post_status,
                'visibility'   => $visibility,
                'updated'      => date('Y-m-d H:i:s'),
            ), array('id' => $pud_id));
            $generator_id = $pud_id;
        } else {
            $success = $wpdb->insert(PUD_GENERATOR_TABLE, array(
                "user_id"          => $user_id,
                "name"             => $name,
                'post_type'   => $type,
                'page_title'       => $title,
                'page_excerpt'     => $excerpt,
                'page_content'     => $content,
                'page_combination' => $page_combination,
                'max_page'         => $max_page,
                'author_id'        => $author,
                'post_status'      => $post_status,
                'visibility'       => $visibility,
                'status'           => $generator_status,
                'created'          => date('Y-m-d H:i:s'),
                'updated'          => date('Y-m-d H:i:s'),
            ));
            $generator_id = $wpdb->insert_id;
        }

        if ($success) {
            if ($pud_type == 'edit' && $pud_id) {
                $wpdb->delete(PUD_GENERATOR_RELATION, array("generator_id" => $generator_id), array('%d'));
            }
            $repeated_placeholders = array();
            foreach ($placeholders as $key => $placeholder) {
                $id     = $placeholder['id'];
                $name   = trim(strip_tags($placeholder['name']));
                $holder = trim(strip_tags($placeholder['placeholder']));
                if (empty($name) || empty($holder)) {
                    continue;
                }
                $childs = $placeholder['child'];
                $tags   = array();
                foreach ($childs as $key2 => $value2) {
                    if ($value2['tag'] != '') {
                        $tags[] = $value2['tag'];
                    }
                }
                $tags           = implode("|", $tags);
                $tags           = $this->filter_tags($tags);
                $tmp            = $wpdb->get_row("SELECT id FROM " . PUD_PLACEHOLDER_TABLE . " where id='" . $id . "'");
                $placeholder_id = 0;
                if (empty($tmp)) {
                    $is_exist        = 1;
                    $org_placeholder = $holder;
                    $i               = 1;
                    do {
                        $myresult = $wpdb->get_results("SELECT id FROM " . PUD_PLACEHOLDER_TABLE . " where placeholder='" . $org_placeholder . "'");
                        if (empty($myresult)) {
                            $is_exist = 0;
                        } else {
                            $org_placeholder                = $holder . '-' . $i;
                            $repeated_placeholders[$holder] = $org_placeholder;
                            $i++;
                        }
                    } while ($is_exist);

                    $wpdb->insert(PUD_PLACEHOLDER_TABLE, array(
                        "name"        => $name,
                        'placeholder' => $org_placeholder,
                        'tags'        => $tags,
                        'created'     => date('Y-m-d H:i:s'),
                        'updated'     => date('Y-m-d H:i:s'),
                    ));

                    $placeholder_id = $wpdb->insert_id;
                } else {
                    $wpdb->update(PUD_PLACEHOLDER_TABLE, array(
                        "name"    => $name,
                        'tags'    => $tags,
                        'updated' => date('Y-m-d H:i:s'),
                    ), array('id' => $id));

                    $placeholder_id = $id;
                }
                if ($placeholder_id && $generator_id) {
                    $wpdb->insert(PUD_GENERATOR_RELATION, array(
                        "generator_id"   => $generator_id,
                        'placeholder_id' => $placeholder_id,
                    ));
                }
            }
        }
        $combination_result = $this->get_page_combination($content);
        foreach ($repeated_placeholders as $old_holder => $new_holder) {
            $title   = str_ireplace('{$' . $old_holder . '}', '{$' . $new_holder . '}', $title);
            $content = str_ireplace('{$' . $old_holder . '}', '{$' . $new_holder . '}', $content);
            $excerpt = str_ireplace('{$' . $old_holder . '}', '{$' . $new_holder . '}', $excerpt);
        }

        $page_combination = count($combination_result);
        $wpdb->update(PUD_GENERATOR_TABLE, array(
            'page_title'       => $title,
            'page_content'     => $content,
            'page_excerpt'     => $excerpt,
            'page_combination' => $page_combination,
            'updated'          => date('Y-m-d H:i:s'),
        ), array('id' => $generator_id));
        if ($success) {
            $result = array('error' => 0, 'message' => 'Generator saved successfully');
        } else {
            $result = array('error' => 1, 'message' => 'Unable to save the record, please try again');
        }
        $this->json_return($result);
    }

    public function get_page_combination($content)
    {
        $page_combination = array();
        $placeholders     = $_POST['names'];
        foreach ($placeholders as $key => $placeholder) {
            $holder = trim(strip_tags($placeholder['placeholder']));
            if (empty($holder)) {
                continue;
            }
            $childs = $placeholder['child'];
            $tags   = array();
            foreach ($childs as $key2 => $value2) {
                if ($value2['tag'] != '') {
                    $tags[] = $value2['tag'];
                }

            }
            $tags = implode("|", $tags);
            $tags = $this->filter_tags($tags);
            if (empty($tags)) {
                continue;
            }
            if (strpos($content, '{$' . $holder . "}") !== false) {
                $page_combination[$holder] = explode("|", $tags);
            }
        }
        if (!empty($page_combination)) {
            $result = $this->cartesian_product($page_combination);
            return $result;
        }
    }

    public function get_page_combination_dynamic($placeholders, $content)
    {
        $page_combination = array();
        foreach ($placeholders as $key => $placeholder) {
            $holder = $placeholder['placeholder'];
            if (empty($holder)) {
                continue;
            }
            $childs = $placeholder['child'];
            if (strpos($content, '{$' . $holder . "}") !== false) {
                $page_combination[$holder] = $childs;
            }
        }
        if (!empty($page_combination)) {
            $result = $this->cartesian($page_combination);
            return $result;
        }
    }

    public function cartesian($input)
    {
        $result = array();
        while (list($key, $values) = each($input)) {
            if (empty($values)) {
                continue;
            }
            if (empty($result)) {
                foreach ($values as $value) {
                    $result[] = array($key => $value);
                }
            } else {
                $append = array();

                foreach ($result as &$product) {
                    $product[$key] = array_shift($values);
                    $copy          = $product;
                    foreach ($values as $item) {
                        $copy[$key] = $item;
                        $append[]   = $copy;
                    }
                    array_unshift($values, $product[$key]);
                }
                $result = array_merge($result, $append);
            }
        }
        return $result;
    }
    public function cartesian_product($set)
    {
        if (!$set) {
            return array(array());
        }
        $subset          = array_shift($set);
        $cartesianSubset = $this->cartesian_product($set);
        $result          = array();
        foreach ($subset as $value) {
            foreach ($cartesianSubset as $p) {
                array_unshift($p, $value);
                $result[] = $p;
            }
        }
        return $result;
    }

    public function calculate_page_combination()
    {
        global $wpdb;
        $postData = json_decode(file_get_contents('php://input'), true);
        $_POST = $postData;
       
        $content          = $this->filterHtmlData('pud_content');
        $excerpt          = $this->filterTextData('pud_page_excerpt');
        $title            = $this->filterTextData('pud_page_title');
        $placeholders     = $_POST['names'];
         
        $page_combination = 0;
        if (!empty($placeholders)) {
            $combination_result = $this->get_page_combination($content);
            $page_combination   = count($combination_result);
        }

        if ($page_combination) {
            $result = array('error' => 0, 'message' => 'Number of pages will generate based on the page content is ' . $page_combination);
        } else {
            $result = array('error' => 0, 'message' => '');
        }
        $this->json_return($result);
    }

    public function start_generator()
    {
        set_time_limit(0);
        global $wpdb;
        $generator_id = $this->filterIntData('id');

        $wpdb->delete(PUD_GENERATOR_LOG_TABLE, array('generator_id' => $generator_id), array('%d'));

        $wpdb->update(PUD_GENERATOR_TABLE, array(
            'status'       => "progress", 
        ), array('id' => $generator_id));

        $success = $this->start_generator_clone($generator_id);

        $wpdb->update(PUD_GENERATOR_TABLE, array(
            'status'       => "completed", 
        ), array('id' => $generator_id));

        if ($success) {
            $result = array('error' => 0, 'message' => 'Generation process completed successfully');
        } else {
            $result = array('error' => 1, 'message' => 'Unable to start the process, please try again');
        }
        $this->json_return($result);
    }

    public function start_generator_clone($id)
    {
        global $wpdb;
        $result['data'] = array();
        $myresult       = $wpdb->get_row("SELECT * FROM " . PUD_GENERATOR_TABLE . " where id='".$id."' ");
        if (empty($myresult)) {
           return true;
        }
        $generator_id     = $myresult->id;
        $name             = $myresult->name;
        $page_title       = $myresult->page_title;
        $page_content     = $myresult->page_content;
        $page_excerpt     = $myresult->page_excerpt;
        $page_combination = $myresult->page_combination;
        $max_page         = $myresult->max_page;
        $author_id        = $myresult->author_id;
        $post_status      = $myresult->post_status;
        $visibility       = $myresult->visibility;
        $post_type = $myresult->post_type;
        $placeholders_data = $wpdb->get_results(" SELECT p.* FROM " . PUD_PLACEHOLDER_TABLE . " p LEFT JOIN " . PUD_GENERATOR_RELATION . " gp ON p.id = gp.`placeholder_id` WHERE gp.`generator_id` = '" . $generator_id . "'");

        if (is_array($placeholders_data)) {
            $placeholders = array();
            foreach ($placeholders_data as $key => $value) {
                $placeholders[$key]['placeholder'] = $value->placeholder;
                $tags                              = $value->tags;
                $childs                            = [];
                $tags                              = explode("|", $tags);
                if (is_array($tags)) {
                    foreach ($tags as $key2 => $value2) {
                        $childs[] = $value2;
                    }
                }
                $placeholders[$key]['child'] = $childs;
            }
            $page_content_combination = $this->get_page_combination_dynamic($placeholders, $page_content);
            $page_title_combination   = $this->get_page_combination_dynamic($placeholders, $page_title);
            $page_excerpt_combination = $this->get_page_combination_dynamic($placeholders, $page_excerpt);

            if(is_array($page_content_combination))
            {
                $t = $e = 0;
                $page_number = 0;
                foreach ($page_content_combination as $k => $content_combinations) {
                     $temp_page_content = $page_content;
                     $temp_page_title = $page_title;
                     $temp_page_excerpt = $page_excerpt;
                     if(!isset($page_title_combination[$t]))
                     {
                        $t = 0;
                     }
                     $title_combinations = isset($page_title_combination[$t])?$page_title_combination[$t]:Array();
                     $t++;
                     if(!isset($page_excerpt_combination[$e]))
                     {
                        $e = 0;
                     }
                     $excerpt_combinations = isset($page_excerpt_combination[$e])?$page_excerpt_combination[$e]:Array();
                     $e++;
                     foreach ($content_combinations as $holder => $holder_v)
                     {
                         $temp_page_content = str_ireplace('{$' . $holder . '}', $holder_v, $temp_page_content);
                     }
                     foreach ($title_combinations as $holder => $holder_v)
                     {
                         $temp_page_title = str_ireplace('{$' . $holder . '}', $holder_v, $temp_page_title);
                     }
                     foreach ($excerpt_combinations as $holder => $holder_v)
                     {
                         $temp_page_excerpt = str_ireplace('{$' . $holder . '}', $holder_v, $temp_page_excerpt);
                     } 
                     $post_id = wp_insert_post(
                        array(
                            'comment_status'    =>  'closed',
                            'ping_status'       =>  'closed',
                            'post_author'       =>  $author_id, 
                            'post_title'        =>  $temp_page_title,
                            'post_status'       =>  $post_status,
                            'post_type'     =>  $post_type,
                            'post_content' => $temp_page_content,
                            'post_excerpt'        =>  $temp_page_excerpt,
                        )
                    ); 
                    if( $post_id && ! is_wp_error( $post_id ) ) {
                        $permalink = get_the_permalink( $post_id ); 
                        $content = '<span class="log-success">'.ucfirst($post_type).' generated successfully: <a href="'.$permalink.'" target="_blank">'.$permalink."</a>"."</span>";
                        $wpdb->insert(PUD_GENERATOR_LOG_TABLE, array(
                            "generator_id"        => $generator_id,
                            'content' => $content,
                            'page_id'        => $post_id,
                            'created'     => date('Y-m-d H:i:s'), 
                        ));
                    }
                    else
                    {
                        $error_string = $post_id->get_error_message();
                        $content = '<span class="log-error">Error while processing page: '.$error_string."</span>";
                        $wpdb->insert(PUD_GENERATOR_LOG_TABLE, array(
                            "generator_id"        => $generator_id,
                            'content' => $content,
                            'page_id'        => 0,
                            'created'     => date('Y-m-d H:i:s'), 
                        ));
                    }
                    $page_number++;
                    if($page_number >= $max_page)
                    {
                        break;
                    }
                }
            }
        }

        $content = '<span class="log-completed">Processing Completed</span>';
        $wpdb->insert(PUD_GENERATOR_LOG_TABLE, array(
            "generator_id"        => $generator_id,
            'content' => $content,
            'page_id'        => 0,
            'created'     => date('Y-m-d H:i:s'), 
        ));

        return true;
    }

}
