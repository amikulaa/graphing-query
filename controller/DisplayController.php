<?php

/**
 * Class DisplayController PIT (Police Incident Tracker)
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */

namespace ME\Controller;

class DisplayController
{
    function __construct()
    {
        $this->entry_model = new \ME\Model\entry_model();
    }
    
    /**
     * This method handles what happens when you move to http://yourproject/projectname/
     */
    public function index()
    {
        $this->check_user_login();
        if ($this->isAdminUser()) {
            $_SESSION['curr_tab'] = 'display_tab';
            require APP . 'view/_templates/header.php';
            require APP . 'view/display/index.php';
            require APP . 'view/_templates/footer.php';
        } else {
            require APP . 'view/_templates/header.php';
            require APP . 'view/error/access_restricted.php';
            require APP . 'view/_templates/footer.php';
        }
    }

     /*
     * Loads table view for user
     */
    public function table()
    {
        $this->check_user_login();
        if ($this->isAdminUser()) {
            $_SESSION['curr_tab'] = 'display_tab';
            require APP . 'view/_templates/header.php';
            require APP . 'view/display/table.php';
            require APP . 'view/_templates/footer.php';
        } else {
            require APP . 'view/_templates/header.php';
            require APP . 'view/error/access_restricted.php';
            require APP . 'view/_templates/footer.php';
        }
    }
    
    /**
     * Makes sure user is an uthorized user approve AND display tabs
     */
    public function isAdminUser(){
        return $this->entry_model->is_admin_user();
    }
    
    /**
     * Check if user is logged in
     */
    public function check_user_login(){
        if(!isset($_SESSION['username'])){
            header('location: ' . URL . 'login');
            exit();
        }
    }
    
    /**
     * This functin prints (ALL) of the tables on the display table page (currently)
     */
    public function print_table(){
        $this->check_user_login();
        if(isset($_POST['html_table'])){
            $html_table = $_POST['html_table'];
            require APP . 'view/display/print_table.php';
        } else {
            require APP . 'view/_templates/header.php';
            require APP . 'view/error/index.php';
            require APP . 'view/_templates/footer.php';
        }
    }
    
    /**
     * This functin prints (ALL) of the graphs on the display graphs page
     */
    public function print_graphs(){
        $this->check_user_login();
        $curr_graphs = $_SESSION['curr_graphs'];
        require APP . 'view/display/print_graphs.php';
    }
    
    /**
     * This functin prints (ALL) of the tables on the display table page (currently)
     */
    public function set_curr_graphs(){
        if(isset($_POST['curr_graphs'])){
            $_SESSION['curr_graphs'] = $_POST['curr_graphs'];
        }
    }
    
    /*
     * Generates graph for user
     */
    public function generate_graph(){
        if(isset($_POST['submit_graph']) && isset($_POST['graph_type']) 
            && isset($_POST['input_one']) && isset($_POST['graph_size'])){
                $graph_type = $_POST['graph_type'];
                $column_one = $_POST['input_one'];
                $answer_column_one = isset($_POST['input_one_child']) ? $_POST['input_one_child'] : '';
                $column_two = isset($_POST['input_two']) ? $_POST['input_two'] : '';
                $answer_column_two = isset($_POST['input_two_child']) ? $_POST['input_two_child'] : '';
                $graph_size = $_POST['graph_size'];
                echo $this->entry_model->generate_new_graph($graph_type, $column_one, $answer_column_one, $column_two, $answer_column_two, $graph_size);
        }
    }
    
    /*
     * Generates images of the current saved graphs for printing FPDF
     * 
     * https://stackoverflow.com/questions/31380440/using-google-charts-api-generated-graphic-in-fpdf
     */
    public function generate_graph_image(){
        $graph_id = isset($_POST['graph_id']) ? $_POST['graph_id'] : 0;
        $data = isset($_POST['graph']) ? $_POST['graph'] : 0;
        if($data == 0){
            echo 0;
        } else if($graph_id == 0){
            echo 1;
        } else {
            list($type, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);
            file_put_contents('img/curr_graphs/'. $graph_id .'.png', $data);
        }
    }
    
    /*
     * Grabs the select items to fill select with
     */
    public function get_child_field_one(){
        echo $this->entry_model->get_child_fields('one');
    }
    
    /*
     * Grabs the select items to fill select with
     */
    public function get_child_field_two(){
        echo $this->entry_model->get_child_fields('two');
    }
    
    /*
     * Saves graph to database
     */
    public function save_graph(){
        echo $this->entry_model->save_graph();
    }
    
    /*
     * Deletes graph from database
     */
    public function delete_graph(){
        echo $this->entry_model->delete_graph();
    }
    
    /*
     * Load graphs saved within the database
     */
    public function load_saved_graphs(){
        echo $this->entry_model->load_saved_graphs();
    }
    
    /*
     * Get the statistics for the table
     */
    public function get_table_stats($type){
        echo $this->entry_model->get_table_stats($type);
    }
}