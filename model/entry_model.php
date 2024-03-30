<?php
/**
 * PIT Entry Model
 Some properties are changed for privacy reasons.
 */
namespace ME\Model;

class entry_model extends Model
{
    
    /*
     * $val = val to check if int
     */
    public function int_input($val) {
        $val = filter_var($val, FILTER_VALIDATE_INT);
        if ($val === false) {
            return "ERROR";
        }
        return $val;
    }
    
    /*
     * $val = val to check if str
     */
    public function str_input($val) {
        if (!is_string($val)) {
            return "ERROR";
        } else {
            $val = stripslashes($val);
            $val = strip_tags($val);
            $val = htmlentities($val);
            $val = str_replace('%20',' ',$val);
            $val = str_replace('&amp;','&', $val);
            $val = str_replace('&#039;',"'", $val);
            $val = str_replace('&quot;','"', $val);
            $val = trim($val);
        }
        return $val;
    }
    
    /**
     * This function cleans the given array of data.
     *
     * @param $column_arr  the assosiative array of data
     */
    public function clean_post_vars($column_arr){
        // clean post variables
        $cleaned_arr = $column_arr;
        if($column_arr != null){
            foreach ($column_arr as $curr_header => $curr_value) {
                if (is_string($curr_value)) {
                    $cleaned_arr[$curr_header] = $this->str_input($curr_value);
                } else if (filter_var($curr_value, FILTER_VALIDATE_INT)) {
                    $cleaned_arr[$curr_header] = $this->int_input($curr_value);
                }
            }
        }
        return $cleaned_arr;
    }
    
    /**
     * This function fills the options for select in data entry
     *
     * @param string $table_name the table to pull from
     * @param string $type the column name from the table to pull
     * @return $html_statement  the htm options to be outputted
     */
    public function fill_options($table_name){
        $sql = "SELECT `name` FROM $table_name";
        $query = $this->db->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        $html_statement = "<option id='select' value='' selected disabled hidden>Select</option>";
        
        foreach ($result as $row){
            $html_statement .=  "<option value=\"" . $row->name . "\">". $row->name . "</option>";
        }
        
        return $html_statement;
    }
    
    /**
     * This function fills the input field(s) for reports reports/index.php
     *
     * @return arr of html <options>
     */
    public function get_parent_field_one(){
        $table_name = 'incidents';
        $html_options = "<option id='select' selected disabled hidden>Select</option>";
        $i = 1;
        $table_names = [
            "case_type", 
            "type_options",
            "initiator_incident",
            "location_incident",            
            "focus_incident",
            "injured_damaged",
            "location_detail",
            "resolution_options",
            "weapons_contraband",
        ];
        $column_names = [
            "incident_case_type",
            "report_type",
            "incident_initiator",
            "incident_location",
        ];
        foreach($table_names as $value){
            $html_options .= "<option id='" . $i . "' value='" . $value ."'>" . ucwords(str_replace('_', ' ', $value)) . "</option>";
            $i += 1;
        }
        return $html_options;
    }
    
    /**
     * This function fills the input field(s) for reports reports/index.php
     *
     * @return arr of html <options>
     */
    public function get_parent_field_two(){
        $table_name = 'incidents';
        $html_options = "<option id='select' selected disabled hidden>Select</option>";
        $i = 1;
        $table_names = [
            "case_type",
            "type_options",
            "initiator_incident",
            "location_incident",
            "focus_incident",
            "injured_damaged",
            "location_detail",
            "resolution_options",
            "weapons_contraband",
        ];
        $column_names = [
            "incident_focus",
            "incident_injured",
            "incident_detail",
            "report_resolution",
            "incident_weapons",
        ];
        foreach($table_names as $value){
            $html_options .= "<option id='" . $i . "' value='" . $value ."'>" . ucwords(str_replace('_', ' ', $value)) . "</option>";
            $i += 1;
        }
        return $html_options;
    }
    
    /**
     * This function gets the child inputs based off of the input fields selected for display.
     * 
     * @return 1 on ERROR, or HTML option selects on non error
     */
    public function get_child_fields($input){
        $table_name = $_POST['input_field'] ? $_POST['input_field'] : null;
        if($table_name == null){
            return 1; //error
        } else {
            if($input == 'one'){
                $html_statement = '<option id="' . $input . '0" value="all" selected>All</option>'; 
            } else {
                $html_statement = '';
            }
            $column = 'name';
            $i = 1;
            // grab all occurances within the database within that field and output
            if($table_name == 'resolution_options'){
                $column = 'resolution_name';
            } else if($table_name == 'type_options'){
                $column = 'type_name';
            } 
            $sql = "SELECT * FROM $table_name";
            $query = $this->db->prepare($sql);
            $query->execute();
            if($query->rowCount() != 0){
                $result = $query->fetchAll();
                foreach ($result as $row){
                    $html_statement .=  "<option id='$input" . $i . "' value=\"" . $row->$column . "\">". $row->$column . "</option>";
                    $i += 1;
                }
            } else {
                return 1;//error
            }
            return $html_statement;
        }
    }
    
    /**
     * This function fills the options for select types
     */
    public function get_type_options(){
        $report_choice = $_POST['report_choice'];
        $sql = "SELECT `type_name` FROM `type_options` WHERE `report_type` = ?";
        $query = $this->db->prepare($sql);
        $query->execute([$report_choice]);
        $result = $query->fetchAll();
        $html_statement = "<option id='select' value='' selected disabled hidden>Select Type</option>";
        
        foreach ($result as $row){
            $html_statement .=  "<option id='option_value' value=\"" . $row->type_name . "\">"
                . $row->type_name . "</option>";
        }
        return $html_statement;
    }
    
    /**
     * This function fills the options for select resolutions
     */
    public function get_resolution_options(){
        $report_choice = $_POST['report_choice'];
        $sql = "SELECT `resolution_name` FROM `resolution_options` WHERE `report_type` = ?";
        $query = $this->db->prepare($sql);
        $query->execute([$report_choice]);
        $result = $query->fetchAll();
        $html_statement = "<option id='select' value='' selected disabled hidden>Select Resolution</option>";
        
        foreach ($result as $row){
            $html_statement .=  "<option id='resolution_option' value=\"" . $row->resolution_name . "\">"
                . $row->resolution_name . "</option>";
        }
        return $html_statement;
    }
    
    /**
     * This function inserts into the database
     * Indexes or inserts by: (incident_num)
     * 
     * @param $column_arr   the assosiative array of data values as "column_header" => $new_value
     */
    public function insert_db($column_arr){
        // clean user vars
        $column_arr = $this->clean_post_vars($column_arr);
        
        // if return is empty, good, insert new incident
        $sql = "SELECT * FROM `incidents` WHERE `incident_num` = ?";
        $query = $this->db->prepare($sql);
        $query->execute([$column_arr['incident_num']]);
        if($query->rowCount() == 0){
            $sql = "INSERT INTO `incidents` (`incident_num`) VALUES (?)";
            $query = $this->db->prepare($sql);
            $query->execute([$column_arr['incident_num']]);
            foreach($column_arr as $column_header => $value){
                if($value != null && $column_header != 'incident_num'){
                    $sql = "UPDATE `incidents` SET $column_header = ? WHERE `incident_num` = ?";
                    $query = $this->db->prepare($sql);
                    $query->execute([$value, $column_arr['incident_num']]);
                    if($column_header == 'report_choice' && $value == 'service'){
                        $sql = "UPDATE `incidents` SET `approved` = 1 WHERE `incident_num` = ?";
                        $query = $this->db->prepare($sql);
                        $query->execute([$column_arr['incident_num']]);
                    }
                }
            }
        } 
        return 0; //success
    }
    
    /*
     * Gets the next incident num for the user
     */
    public function get_next_incident_num(){
        $sql = "SELECT MAX(`incident_num`) as 'MAX' FROM `incidents`";
        $query = $this->db->prepare($sql);
        $query->execute();
        // if return is empty, good, insert new incident
        if($query->rowCount() != 0){
            $result = $query->fetchAll();
            echo $result[0]->MAX + 1;
        } else {
            echo 1; //new incident table, first incident
        }
    }
    
    /**
     * This function fills the approve table with unapproved reports.
     */
    public function get_unapproved_reports(){
        $html_statement = "";
        $sql = "SELECT * FROM `incidents` WHERE `approved` = 0 AND `report_choice` = 'incident'";
        $query = $this->db->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        if($query->rowCount() != 0){
            $html_statement = "<table class=tb2><thead><tr>";
            $html_statement .= "<th title='APPROVE AS INCIDENT' class='approve_heading'><i class='fa-solid fa-square-check fa-2xl'></i></th>";
            $html_statement .= "<th title='CHANGE TO SERVICE' class='approve_heading'><i class='fa-solid fa-square-xmark fa-2xl'></i></th>";
            foreach($result as $row){
                foreach($row as $column_header => $row_value){
                    if($column_header != 'approved' && $column_header != 'incident_num'){
                        $html_statement .= "<th class='approve_heading approve_display_table' style='font-size: 14px;'>" . ucwords(str_replace('_', ' ', $column_header)). "</th>";
                    }
                }
                break;
            }
            $html_statement .= "</tr></thead><tbody>";
            foreach($result as $row){
                $html_statement .= "<tr>";
                $html_statement .= "<td><input type='checkbox' name='" . $row->incident_num . "' id='approve" . $row->incident_num . "' onclick='update_approval_value($row->incident_num)'/></td>";
                $html_statement .= "<td><input type='checkbox' name='" . $row->incident_num . "' id='type" . $row->incident_num . "' onclick='update_report_type($row->incident_num)'/></td>";
                foreach($row as $column_header => $row_value){
                    if($column_header != 'approved' && $column_header != 'incident_num'){
                        if($row_value == null){
                            $row_value = '';
                        }
                        if($column_header == 'report_level'){
                            $html_statement .= "<td class='level" . $row_value . "'>" . $row_value . "</td>";
                        } else {
                            $html_statement .= "<td>" . $row_value . "</td>";
                        }
                    }
                }
                $html_statement .= "</tr>";
            }
            $html_statement .= "</tbody></table>";
        } else {
            $html_statement = "<div id='approve_complete'><a>Complete</a></div>";
        }
        return $html_statement;
    }

    /**
     * This function inserts and updates("saves") approval checkbox
     */
    public function update_approval_value(){
        $column_arr = $_POST['column_arr']; // set by me, no need to clean
        foreach ($column_arr as $column_header => $value) {
            if ($value != null) {
                $sql = "UPDATE `incidents` SET `approved` = ? WHERE `incident_num` = ?";
                $query = $this->db->prepare($sql);
                $query->execute([$value,$column_header]);
            }
        }
        return 0;
    }
    
    /**
     * This function updates report type
     */
    public function update_report_type(){
        $column_arr = $_POST['column_arr']; // set by me, no need to clean
        foreach ($column_arr as $column_header => $value) {
            if ($value != null) {
                if($value = 1){
                    $sql = "UPDATE `incidents` SET `report_choice` = ? WHERE `incident_num` = ?";
                    $query = $this->db->prepare($sql);
                    $query->execute(['service',$column_header]);
                } else {
                    $sql = "UPDATE `incidents` SET `report_choice` = ? WHERE `incident_num` = ?";
                    $query = $this->db->prepare($sql);
                    $query->execute(['incident',$column_header]);
                }
            } else {
                return 1;
            }
        }
        return 0;
    }

    public function fill_officers(){
        //privacy removed code
        return $officer_list;
    }
    
    public function is_admin_user(){
        //privacy removed code
    }

    public function is_basic_user(){ 
        //privacy removed code
    }
    
    /*
     * Swaps name for this sepcific database purposes
     */
    public function swap_name($column_name){
        $table_swap = [
            ["case_type","incident_case_type"],
            ["type_options", "report_type"],
            ["initiator_incident", "incident_initiator"],
            ["location_incident", "incident_location"],
            ["focus_incident", "incident_focus"],
            ["injured_damaged","incident_injured"],
            ["location_detail","incident_detail"],
            ["resolution_options","report_resolution"],
            ["weapons_contraband","incident_weapons"]
        ];
        
        for($i = 0; $i < count($table_swap); $i++){
            if($column_name == $table_swap[$i][0]){
                return $table_swap[$i][1];
            }
        }
        return $column_name;
    }
    
    /*
     * Saves graph information to generate graph onload next time
     */
    public function save_graph(){
        if(isset($_SESSION['curr_chart_type']) && isset($_SESSION['curr_input_one']) && isset($_SESSION['curr_input_one_child'])
            && isset($_SESSION['curr_input_two']) && isset($_SESSION['curr_input_two_child']) && isset($_SESSION['curr_input_three'])
            && isset($_SESSION['curr_graph_size']) && isset($_SESSION['curr_report_choice'])){
                $sql = "INSERT INTO `saved_graphs` (`chart_type`, `input_one`, `input_one_child`, `input_two`, `input_two_child`, `input_three`, `graph_size`, `report_choice`) VALUES (?,?,?,?,?,?,?,?)";
                $query = $this->db->prepare($sql);
                $query->execute([$_SESSION['curr_chart_type'], $_SESSION['curr_input_one'], $_SESSION['curr_input_one_child'], 
                    $_SESSION['curr_input_two'], $_SESSION['curr_input_two_child'], $_SESSION['curr_input_three'],
                    $_SESSION['curr_graph_size'], $_SESSION['curr_report_choice']]);
                return 0;
        } else {
            return 1;
        }
    }
    
    /*
     * Saves graph data into session incase user saves graph into database
     */
    public function save_curr_graph_session($graph_type, $column_one, $answer_column_one, $column_two, $answer_column_two, $column_three, $graph_size, $report_choice){
        $_SESSION['curr_chart_type'] = $graph_type;
        $_SESSION['curr_input_one'] = $column_one;
        $_SESSION['curr_input_one_child'] = $answer_column_one;
        $_SESSION['curr_input_two'] = $column_two;
        $_SESSION['curr_input_two_child'] = $answer_column_two;
        $_SESSION['curr_input_three'] = $column_three;
        $_SESSION['curr_graph_size'] = $graph_size;
        $_SESSION['curr_report_choice'] = $report_choice;
    }
    
    /*
     * Swaps name for this sepcific database purposes
     */
    public function delete_graph(){
        $graph_id = isset($_POST['id']) ? $_POST['id'] : 0;
        if($graph_id != null && $graph_id != 0){
            $sql = "SELECT * FROM `saved_graphs` WHERE `id` = ?";
            $query = $this->db->prepare($sql);
            $query->execute([$graph_id]);
            if($query->rowCount() != 0){
                $sql = "DELETE FROM `saved_graphs` WHERE `id` = ?";
                $query = $this->db->prepare($sql);
                $query->execute([$graph_id]);
                return 0;//success
            } else {
                return 1;//error
            }
        } else {
            return 1;//error
        }
    }
    
    /*
     * Swaps name for this sepcific database purposes
     */
    public function load_saved_graphs(){
        $return_arr = [];
        $graph_html = '';
        $sql = "SELECT * FROM `saved_graphs` ORDER BY `id` ASC";
        $query = $this->db->prepare($sql);
        $query->execute();
        if($query->rowCount() != 0){
            $result = $query->fetchAll();
            foreach($result as $row){
                $id = $row->id;
                $graph_html = $this->generate_new_graph($row->chart_type, $row->input_one, $row->input_one_child, $row->input_two, $row->input_two_child, $row->input_three, $row->graph_size, $row->report_choice, $row->id);
                array_push($return_arr, ["id" => $id, "graph_html" => $graph_html]);
            }
            return json_encode($return_arr);
        } else {
            return json_encode(1);
        }
    }
    
    /**
     * Logs the user when the login.
     */
    public function log_user($username){
        //privacy removed code
    }
    
    /*
     * Creates graph of users choice with specified data
     */
    public function create_graph($ChartData, $ChartType, $ChartDivId, $ChartTitle, $ChartWidth, $ChartHeight)
    {
        $ChartTypeArray = explode("-", $ChartType);
        $BaseChartType = $ChartTypeArray[0];
        $OtherChartOptions = $ChartTypeArray[1];
        $colors = isset($ChartTypeArray[2]) ? $ChartTypeArray[2] : '';
        $add_level_colors = $colors == 'levels' ? true : false;
        $ChartTypeFunction = 'draw' . $BaseChartType;
        
        if(substr_count($ChartTitle, "Incident") > 1){
            $ChartTitleArr = explode("Incident", $ChartTitle, 2);
            $ChartTitle = $ChartTitleArr[1];
        }
        
        //set options for chart
        $LEVELS = "['#F0BA70', '#F68656', '#EA5A3E', '#DA2B27', '#C12600']";
        $ChartOptions = "title: '$ChartTitle', width: " . $ChartWidth . ", height: " . $ChartHeight;
        $ChartOptions .= ", titleTextStyle: {
                color: '#454545',
                fontSize: 18,
                bold: true,
                italic: false
            }";
        if($BaseChartType == 'LineChart'){
            $ChartOptions .= ", chartArea: {width: '70%', height: '30%'}";
        } else if($BaseChartType == 'PieChart'){
            $ChartOptions .= ", chartArea: {width: '80%'}";
        } else if($OtherChartOptions == 'Small'){
            $ChartOptions .= ", chartArea: {width: '50%'}";
        } else {
            $ChartOptions .= ", chartArea: {width: '70%'}";
        }
        
        if($add_level_colors){
            $ChartOptions .= ", colors: " . $LEVELS;
        }
        
        // Adding additional view options
        switch ($OtherChartOptions) {
            case "Base":
            case "StackedTotal":
            case "Small":
                break;
            case "3D":
                $ChartOptions .= ", is3D: true";
                break;
            case "StackedBase":
                $ChartOptions .= ", isStacked: true";
                break;
            case "StackedPercent":
                $ChartOptions .= ", isStacked: 'percent'";
                break;
            case "LevelDesc":
                $ChartOptions .= ", hAxis: {title: '1: ROUTINE 2: ALTERCATION 3: THREAT 4: THREAT TO LIFE 5: THREAT TO LIVES'}";
                break;
            case "Curved":
                $ChartOptions .= ", curveType: 'function'";
                break;
            case "Donut":
                $ChartOptions .= ", pieHole: 0.4, chartArea: {width: '70%'}";
                break;
            default:
                echo "OtherChartOptions not found: " . $OtherChartOptions;
                break;
        }
        $postUrl = URL . 'Display/generate_graph_image';
        $PACKAGE = 'corechart';
        $GraphStr = "<script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>
                    <script type='text/javascript'>
                        google.charts.load('current', {'packages':['$PACKAGE']});
                        google.charts.setOnLoadCallback(" . $ChartTypeFunction . ");
                        function " . $ChartTypeFunction . "() {
                            var chart_data = google.visualization.arrayToDataTable(" . json_encode($ChartData) . ");
                            var chart = new google.visualization." . $BaseChartType . "(document.getElementById('" . $ChartDivId . "'));
                            chart.draw(chart_data, {" . $ChartOptions . "});
                            var curr_graph = (chart.getImageURI());
                            set_curr_graph_div('$ChartDivId');
                            set_curr_graph(curr_graph + 'WIDTH" . $ChartWidth. "');
                        }
                    </script>";
        return ($GraphStr);
    }
    
    /*
     * Creates requested graph for user
     */
    public function generate_new_graph($graph_type, $column_one, $answer_column_one, $column_two, $answer_column_two, $column_three, $graph_size, $report_choice_type, $is_new_graph){
        $year_start_date = date('Y') . "-01-01";
        $width = 400;
        $height = 400;
        $title = '';
        $graph_data = [];
        $levels_arr = [1, 2, 3, 4, 5];
        $months_arr = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        $year_arr = [date('Y', strtotime('-2 year')), date('Y', strtotime('-1 year')), date('Y', strtotime('now'))];
        if($graph_size == 2){
            $width = 800;
        }
        $column_one = $this->swap_name($column_one);
        if($column_two != null){
            $column_two = $this->swap_name($column_two);
        }
        
        if($column_three != null && $column_three != ''){
            $third_arr = $column_three == 'levels' ? $levels_arr : ($column_three == 'months' ? $months_arr : $year_arr);
            $column_three_actual = $column_three == 'levels' ? 'report_level' : 'incident_date';
            if($column_three == 'levels'){
                $graph_data = [[ucwords(str_replace('_', ' ', $column_one)), "Level 1", "Level 2", "Level 3", "Level 4", "Level 5"]];
            } else if($column_three == 'months'){
                $graph_data = [[ucwords(str_replace('_', ' ', $column_one)), "January", "Feburary", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]];
            } else {
                $year_minus2 = date('Y', strtotime('-2 year'));
                $year_minus1 = date('Y', strtotime('-1 year'));
                $year_minus0 = date('Y', strtotime('now'));
                $graph_data = [[ucwords(str_replace('_', ' ', $column_one)), $year_minus2, $year_minus1, $year_minus0]];
            }
            $sql = "SELECT DISTINCT `$column_one` FROM `incidents` WHERE `report_choice` = '$report_choice_type'";
            $sql .= " AND `incident_date` >= ?";
            if($column_two != null && $column_two != ''){
                $sql .= " AND `$column_two` = '$answer_column_two'";
            }
            $sql .= " AND `approved` = 1";
            $sql .= " AND `$column_one` is not null AND `$column_one` != ''";
            $query = $this->db->prepare($sql);
            $query->execute([$year_start_date]);
            $result = $query->fetchAll();
            foreach($result as $row){
                $inner_graph_data = [$row->$column_one];
                foreach($third_arr as $curr_outer_filter){
                    $sql = "SELECT COUNT(`incident_num`) AS 'count' FROM `incidents` WHERE `report_choice` = '$report_choice_type'";
                    $sql .= " AND `$column_one` = ?";
                    if($column_two != null && $column_two != ''){
                        $sql .= " AND `$column_two` = '$answer_column_two'";
                    }
                    if($column_three_actual == 'incident_date' && $third_arr === $months_arr){
                       $sql .= " AND MONTH(`$column_three_actual`) = ?";
                    } else if($column_three_actual == 'incident_date' && $third_arr === $year_arr){
                        $sql .= " AND YEAR(`$column_three_actual`) = ?";
                    } else {
                        $sql .= " AND `$column_three_actual` = ?";
                    }
                    $sql .= " AND `incident_date` >= ?";
                    $sql .= " AND `approved` = 1";
                    $query = $this->db->prepare($sql);
                    $query->execute([$row->$column_one, $curr_outer_filter, $year_start_date]);
                    $result = $query->fetchAll();
                    $count = $result[0]->count + 0;
                    $inner_graph_data[] = $count;
                }
                array_push($graph_data, $inner_graph_data);
            }
            $title = ucwords($report_choice_type) . " " . ucwords(str_replace('s', '', $column_three)) . " Breakdown of " . ucwords(str_replace('_', ' ', $column_one));
            $title .= ucwords($report_choice_type) . " " . $column_two == '' ? '' : "s";
            if($column_two != null && $column_two != ''){
                $title .= " for " . ucwords(str_replace('_', ' ', $answer_column_two));
            }
        } else {
            if($column_two != null && $column_two != ''){
                if($answer_column_one == 'all'){
                    $sql = "SELECT DISTINCT `$column_one` FROM `incidents` WHERE `report_choice` = '$report_choice_type'";
                    $sql .= " AND `incident_date` >= ?";
                    $sql .= " AND `$column_two` = ?";
                    $sql .= " AND `$column_one` is not null AND `$column_one` != ''";
                    $query = $this->db->prepare($sql);
                    $query->execute([$year_start_date, $answer_column_two]);
                    $result = $query->fetchAll();
                    $graph_data = [[ucwords(str_replace('_', ' ', $column_one)), "Count"]];
                    foreach($result as $row){
                        $sql = "SELECT COUNT(`incident_num`) AS 'count' FROM `incidents` WHERE `report_choice` = '$report_choice_type'";
                        $sql .= " AND `$column_one` = ?";
                        $sql .= " AND `$column_two` = ?";
                        $sql .= " AND `incident_date` >= ?";
                        $sql .= " AND `approved` = 1";
                        $query = $this->db->prepare($sql);
                        $query->execute([$row->$column_one, $answer_column_two, $year_start_date]);
                        $result = $query->fetchAll();
                        $count = $result[0]->count + 0;
                        array_push($graph_data, [$row->$column_one, $count]);
                    }
                    $title = ucwords($report_choice_type) . " " . ucwords(str_replace('_', ' ', $column_one)) . "s for " . ucwords(str_replace('_', ' ', $answer_column_two)) . "s";
                } else {
                    $graph_data = [[ucwords(str_replace('_', ' ', $column_one)), "Count"]];
                    $sql = "SELECT COUNT(`incident_num`) AS 'count' FROM `incidents` WHERE `report_choice` = '$report_choice_type'";
                    $sql .= " AND `$column_one` = ?";
                    $sql .= " AND `$column_two` = ?";
                    $sql .= " AND `incident_date` >= ?";
                    $sql .= " AND `approved` = 1";
                    $query = $this->db->prepare($sql);
                    $query->execute([$answer_column_one, $answer_column_two, $year_start_date]);
                    $result = $query->fetchAll();
                    $count = $result[0]->count + 0;
                    array_push($graph_data, [$answer_column_one, $count]);
                    $title = ucwords($report_choice_type) . " " . ucwords(str_replace('_', ' ', $answer_column_one)) . "s for " . ucwords(str_replace('_', ' ', $answer_column_two));
                }
            } else {
                if($answer_column_one != 'all'){
                    $sql = "SELECT COUNT(`incident_num`) AS 'count' FROM `incidents` WHERE `report_choice` = '$report_choice_type'";
                    $sql .= " AND `$column_one` = ?";
                    $sql .= " AND `incident_date` >= ?";
                    $sql .= " AND `approved` = 1";
                    $query = $this->db->prepare($sql);
                    $query->execute([$answer_column_one, $year_start_date]);
                    $graph_data = [[ucwords(str_replace('_', ' ', $column_one)), "Count"]];
                    if($query->rowCount() != 0){
                        $result = $query->fetchAll();
                        $count = $result[0]->count + 0;
                        array_push($graph_data, [ucwords(str_replace('_', ' ', $answer_column_one)), $count]);
                        $title = ucwords($report_choice_type) . " " . ucwords(str_replace('_', ' ', $column_one)) . ": " . ucwords(str_replace('_', ' ', $answer_column_one));
                    }
                } else {
                    // ALL
                    $sql = "SELECT DISTINCT `$column_one` FROM `incidents` WHERE `report_choice` = '$report_choice_type'";
                    $sql .= " AND `incident_date` >= ?";
                    $sql .= " AND `approved` = 1";
                    $sql .= " AND `$column_one` is not null AND `$column_one` != ''";
                    $query = $this->db->prepare($sql);
                    $query->execute([$year_start_date]);
                    if($query->rowCount() != 0){
                        $result = $query->fetchAll();
                        $graph_data = [[ucwords(str_replace('_', ' ', $column_one)), "Count"]];
                        $index = 0;
                        foreach($result as $row){
                            $sql = "SELECT COUNT(`incident_num`) AS 'count' FROM `incidents` WHERE `report_choice` = '$report_choice_type' AND `$column_one` = ?";
                            $sql .= " AND `incident_date` >= ?";
                            $sql .= " AND `approved` = 1";
                            $query = $this->db->prepare($sql);
                            $query->execute([$row->$column_one, $year_start_date]);
                            $result = $query->fetchAll();
                            $count = $result[0]->count + 0;
                            array_push($graph_data, [$row->$column_one, $count]);
                            $index += 1;
                        }
                        $title = ucwords($report_choice_type) . " " . ucwords(str_replace('_', ' ', $column_one)) . "s";
                    }
                }
            }
        }
        
        if($column_three == 'levels'){
            $graph_type .= "-levels";
        }
        
        // if results, generate graph
        if($graph_data != null && $graph_type != null && $title != null && $width != null && $height != null){
            $div_id = '';
            if($is_new_graph == 0){
                $this->save_curr_graph_session($graph_type, $column_one, $answer_column_one, $column_two, $answer_column_two, $column_three, $graph_size, $report_choice_type);
                $div_id = 'graph';
            } else {
                $div_id = 'chart' . $is_new_graph;
            }
            return $this->create_graph($graph_data, $graph_type, $div_id, $title, $width, $height, true);
        } else {
            return 'No Results';
        }
    }
    
    /**
     * This function fills the table of display/table with the table of data
     */
    public function get_table_stats($type){
        
        //base vars
        $table_title = ucwords($type) . " Statistics";
        $year_start_date = date('Y') . '-01-01';
        $report_choice = $type == 'total' ? null : $type;
        $additional_sql = $type == 'total' ? null : " AND `report_choice` = '$report_choice'";
        $table = '';
        $table_num = 0;
        $heading_index = 0;
        
        //headings
        $header1 = ucwords($type) . " Level Breakdown";
        $header2 = ucwords($type) . " Case Breakdown";
        $header3 = ucwords($type) . " Type Breakdown";
        $header4 = ucwords($type) . " Location Breakdown";
        $header5 = ucwords($type) . " Focus Breakdown";
        $header6 = ucwords($type) . " Weapons Breakdown";
        $headings = [$header1, $header2, $header3, $header4, $header5, $header6];
        
        // LEVEL BREAKDOWN
        $table_columns = [
            ['report_choice', 'report_level'],
            ['report_choice', 'incident_case_type'],
            ['report_type', 'report_resolution'],
            ['incident_location', 'incident_detail'],
            ['incident_focus', 'incident_initiator'],
            ['incident_weapons', 'incident_injured'],
        ];
        
        foreach($table_columns as $curr_table){
            $input_one = $curr_table[0];
            $input_two = $curr_table[1];
            $output_arr = [];
            $sql = "SELECT DISTINCT `$input_one` FROM `incidents` WHERE `incident_date` >= $year_start_date";
            if ($additional_sql != null) {
                $sql .= $additional_sql;
            }
            $sql .= " AND `approved` = 1";
            $sql .= " AND `$input_one` is not null AND `$input_one` != ''";
            $query = $this->db->prepare($sql);
            $query->execute();
            $result = $query->fetchAll();
            foreach ($result as $row) {
                $sql = "SELECT COUNT(`incident_num`) AS 'count' FROM `incidents` WHERE `$input_one` = ?";
                if ($additional_sql != null) {
                    $sql .= $additional_sql;
                }
                $sql .= " AND `incident_date` >= $year_start_date";
                $sql .= " AND `approved` = 1";
                if($input_one == 'report_choice'){
                    $sql .= " AND `$input_two` is not null AND `$input_two` != ''";
                }
                $query = $this->db->prepare($sql);
                $query->execute([$row->$input_one]);
                $result = $query->fetchAll();
                $count = $result[0]->count + 0;
                
                // getting each resolution of each report type
                $sql2 = "SELECT DISTINCT `$input_two` FROM `incidents` WHERE `incident_date` >= $year_start_date";
                if ($additional_sql != null) {
                    $sql2 .= $additional_sql;
                }
                $sql2 .= " AND `$input_one` = ?";
                $sql2 .= " AND `approved` = 1";
                $sql2 .= " AND `$input_two` is not null AND `$input_two` != ''";
                $query2 = $this->db->prepare($sql2);
                $query2->execute([$row->$input_one]);
                $result2 = $query2->fetchAll();
                $inner_arr = [];
                foreach ($result2 as $row2) {
                    $sql3 = "SELECT COUNT(`incident_num`) AS 'count' FROM `incidents` WHERE `$input_one` = ?";
                    if ($additional_sql != null) {
                        $sql3 .= $additional_sql;
                    }
                    $sql3 .= " AND `$input_two` = ?";
                    $sql3 .= " AND `incident_date` >= $year_start_date";
                    $sql3 .= " AND `approved` = 1";
                    $query3 = $this->db->prepare($sql3);
                    $query3->execute([$row->$input_one,$row2->$input_two]);
                    $result3 = $query3->fetchAll();
                    $count2 = $result3[0]->count + 0;
                    array_push($inner_arr, [$row2->$input_two, $count2]);
                }
                array_push($output_arr, [[$row->$input_one, $count], [$inner_arr]]);
            }
            //output table
            if($output_arr != null){
                $table .= "<table class=tb id='display_table'>";
                $table .= "<thead>";
                $table .= "<tr><th class='display_heading' style='font-size: 16px;'>$headings[$heading_index]</th><th class='td-count display_heading'></th></tr>";
                $heading_index += 1;
                for($index = 0; $index < count($output_arr); $index++){
                    $table .= "<thead><tr id='display_table" . $table_num . "' onclick='bring_bottom_up(this.id)'>";
                    $table .= "<th class='display_heading2'>" . ucwords($output_arr[$index][0][0]) . "<span id='carrot" . $table_num . "' class='carrot' hidden><i class='fa-solid fa-sort-down'></i></span></th>";
                    $table .= "<th class='display_heading2'>" . $output_arr[$index][0][1] . "</th>";
                    $table .= "</tr></thead>";
                    $table .= "<tbody id='body" . $table_num . "'>";
                    foreach($output_arr[$index][1] as $row){
                        foreach($row as $curr_row){
                            $table .= "<tr>";
                            $table .= "<td>";
                            $table .= $curr_row[0];
                            $table .= "</td>";
                            $table .= "<td class='td-count'>";
                            $table .= $curr_row[1];
                            $table .= "</td>";
                            $table .= "</tr>";
                        }
                    }
                    $table_num++;
                }
                $table .= "</tbody>";
                $table .= "</table></br>";
            }
        }
        return $table;
    }
}
