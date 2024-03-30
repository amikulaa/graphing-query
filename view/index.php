<!-- view/display/index.php -->
<!DOCTYPE html>
<body onload='load_saved_graphs(); set_curr_inner("graphs_display");'>
<div class="container">
	<div id='page_info' class="col-sm-12">
		<button onclick="topFunction()" id="top_button" class='btn' title="TOP"><i class="fa-solid fa-arrows-up-to-line"></i></button>
		<button onclick="topSOMEFunction()" id="top_SOME_button" class='btn' title="UP"><i class="fa-solid fa-angle-up"></i></button>
		<button onclick="downSOMEFunction()" id="down_SOME_button" class='btn' title="DOWN"><i class="fa-solid fa-angle-down"></i></button>
		<button onclick="downFunction()" id="down_button" class='btn' title="BOTTOM"><i class="fa-solid fa-arrows-down-to-line"></i></button>
    	<div id='nav'>
    		<a id='table_display' href='<?php echo URL;?>Display/table'>Table</a>
    		<a id='graphs_display' href='<?php echo URL;?>Display'>Graphs</a>
    	</div>
    	<div id='help' class='help'>
			<i id='show_help_icon' class="fa-solid fa-circle-info" onclick='show_help();'></i>
			<i id='show_examples_icon' class="fa-solid fa-chart-simple" onclick='show_examples();'></i>
		</div>
		<div id='help_user_pick_graph' hidden>
			<div id='reccomendation_content' hidden>
				<h4><b>1.</b> What do you want your <b>graph to show?</b>
    			<select id='graph_data_type' name='graph_data_type' onchange='show_reccomendation(this.value)'>
    				<option value='' selected hidden disabled>Select</option>
    				<option value='1'>Comparisons: shows counts between multiple categories</option>
    				<option value='2'>Trends: shows changes or progress over a period of time</option>
    				<option value='3'>Proportions: shows the different parts that make up a whole</option>
    			</select></h4>
    			<div id='reccomendation_container' name='reccomendation_container' hidden>
        			<h4>We recommend using a<input id='reccomendation' name='reccomendation' disabled></h4>
    			</div>
    			<h4><b>2.</b> Full or half page <b>graph size?</b></h4>
    			<h4><b>3.</b> Do you want to get the results of <b>incidents or services?</b></h4>
    			<h4><b>4a. The input</b> you want to collect your statistics on.</h4>
    			<h4><b>4b. A specific answer</b> of 4a input you want to collect your statistics on, or all.</h4>
    			<h4><b>5a. A filter</b> for your selected input. <i>(optional)</i></h4>
    			<h4><b>5b. A specific answer</b> of 5a filter for selected input. <i>(optional)</i></h4>
    			<h4><b>6. A category</b> for your selected input for stacked charts and line charts. <i>(hidden by default)</i></h4>
    			<hr/>
    			<h4><i>Make a <u>&emsp;&emsp;&emsp;&emsp;</u> of size <u>&emsp;&emsp;&emsp;&emsp;</u> of <u>&emsp;&emsp;&emsp;&emsp;</u>&emsp;<u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</u> that are equal to <u>&emsp;&emsp;&emsp;&emsp;</u></i></h4>
    			<h4><i><i class="fa-solid fa-plus"></i> (optional filter) filter by <u>&emsp;&emsp;&emsp;&emsp;</u> being equal to <u>&emsp;&emsp;&emsp;&emsp;</u></i></h4>
    			<h4><i><i class="fa-solid fa-plus"></i> (stacked or line graph type ONLY) categorized by <u>&emsp;&emsp;&emsp;&emsp;</u></i></h4>
			</div>
			<div id='examples_content' hidden>
				<h4><b>Example 1:</b> Make a
    				<br/>1. <u>Column Chart</u> of size
    				<br/>2. <u>Full Page</u> of 
    				<br/>3. <u>Incident</u> 
    				<br/>4a. <u>Resolution Options</u> that are equal to
    				<br/>4b. <u>All</u></h4>
    			<h4><b>Example 2:</b> Make a
    				<br/>1. <u>Column Chart</u> of size
    				<br/>2. <u>Full Page</u> of 
    				<br/>3. <u>Incident</u> 
    				<br/>4a. <u>Resolution Options</u> that are equal to
    				<br/>4b. <u>All</u>, filter by 
    				<br/>5a. <u>Type Option</u> being equal to
    				<br/>5b. <u>Warrent Arrest</u></h4>
    			<h4><b>Example 3:</b> Make a
    				<br/>1. <u>Stacked Column Chart</u> of size
    				<br/>2. <u>Full Page</u> of 
    				<br/>3. <u>Incident</u> 
    				<br/>4a. <u>Resolution Options</u> that are equal to
    				<br/>4b. <u>All</u>, filter by 
    				<br/>5a. <u>Type Option</u> being equal to
    				<br/>5b. <u>Warrent Arrest</u>, categorized by
    				<br/>6. <u>Levels</u></h4>
    			<hr/>
    			<h4><i>Make a <u>&emsp;&emsp;&emsp;&emsp;</u> of size <u>&emsp;&emsp;&emsp;&emsp;</u> of <u>&emsp;&emsp;&emsp;&emsp;</u> <u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</u> that are equal to <u>&emsp;&emsp;&emsp;&emsp;</u></i></h4>
    			<h4><i><i class="fa-solid fa-plus"></i> (optional filter) filter by <u>&emsp;&emsp;&emsp;&emsp;</u> being equal to <u>&emsp;&emsp;&emsp;&emsp;</u></i></h4>
    			<h4><i><i class="fa-solid fa-plus"></i> (stacked or line graph type ONLY) categorized by <u>&emsp;&emsp;&emsp;&emsp;</u></i></h4>
			</div>
        </div>
		<form id='utility_form' method='POST'>
    		<div class='outer'>
        		<div id='user_create_graph'>
        			<select id='graph_type' name='graph_type' class='display_input' onchange='show_third_input();' required>
        				<option value='' selected hidden disabled>Select Graph Type</option>
        				<option value='BarChart-Base'>Bar Chart</option>
        				<option value='BarChart-StackedBase'>Stacked Bar Chart</option>
        				<option value='ColumnChart-Base'>Column Chart</option>
        				<option value='ColumnChart-StackedBase'>Stacked Column Chart</option>
        				<option value='LineChart-Base'>Line Chart</option>
        				<option value='PieChart-Base'>Pie Chart</option>
        				<option value='PieChart-3D'>3D Pie Chart</option>
        				<option value='PieChart-Donut'>Donut Pie Chart</option>
        			</select>
        			<br/>
        			<select id='graph_size' name='graph_size' class='display_input' required>
            			<option value=2 Selected>Full Page</option>
            			<option value=1>Half Page</option>
            		</select> 	
        			<br/>
        			<select id='graph_report_choice' name='graph_report_choice' class='display_input' required>
            			<option value='incident' selected>Incident</option>
            			<option value='service'>Service</option>
        			</select>
        			<br/>
        			<select id='input_one' name='input_one' class='display_input' onchange='get_child_one_fields(this.value)' required>
        				<?php echo $this->entry_model->get_parent_field_one();?>
        			</select>
        			<input type="hidden" value="0" id="total_input_one_child_items">
        			<select id='input_one_child' name='input_one_child' class='display_input' required></select>
        			<div id='input_two_container'>
            			<select id='input_two' name='input_two' class='display_input' onchange='get_child_two_fields(this.value)'>
                			<?php echo $this->entry_model->get_parent_field_two();?>
                		</select>
                		<input type="hidden" value="0" id="total_input_two_child_items">
                		<select id='input_two_child' name='input_two_child' class='display_input'></select>    			
        			</div>   		
        			<div id='input_three_container'>
        			    <!-- Stacked Bar Chart, Stacked Column Chart, Line Chart-->
            			<select id='input_three' name='input_three' class='display_input'>
            				<option value=''>Select</option>
            				<option value='levels'>Levels</option>
            				<option value='months'>Months</option>
            				<option value='years'>Years</option>
                		</select>		 		
        			</div>  
            		<div class='top'>
            			<div id="error_message_div"><input id="error_message" disabled></div>
        				<button type='submit' name='submit_graph' id='submit_graph' class='btn'>CREATE GRAPH</button>
        			</div>
        		</div>
    		</div>
		</form>
		<?php 
    		if(isset($_POST['submit_graph']) && isset($_POST['graph_type']) && isset($_POST['graph_report_choice'])){
    		    $graph_type = $_POST['graph_type'];
    		    $graph_report_choice = $_POST['graph_report_choice'];
    		    $graph_size = $_POST['graph_size'];
    		    if(isset($_POST['input_one']) && isset($_POST['input_one_child'])){
    		        $input_one = $_POST['input_one'];
    		        $input_one_child = $_POST['input_one_child'];
    		        if(isset($_POST['input_two']) && isset($_POST['input_two_child'])){
    		            $input_two = $_POST['input_two'];
    		            $input_two_child = $_POST['input_two_child'];
    		            if($input_one == $input_two){
    		                echo '<script>document.getElementById("error_message").value = "ERROR: Equal Input Selections";</script>';
    		            } else {
    		                if(isset($_POST['input_three'])){
    		                    $input_three = $_POST['input_three'];
    		                    echo $this->entry_model->generate_new_graph($graph_type, $input_one, $input_one_child, $input_two, $input_two_child, $input_three, $graph_size, $graph_report_choice, 0);
    		                } else {
    		                    echo $this->entry_model->generate_new_graph($graph_type, $input_one, $input_one_child, $input_two, $input_two_child, '', $graph_size, $graph_report_choice, 0);
    		                }
    		            }
    		        } else if(isset($_POST['input_two'])){
    		            echo '<script>document.getElementById("error_message").value = "ERROR: Missing Input 2 Answer Selection";</script>';
    		        } else if(isset($_POST['input_three'])){
    		            $input_three = $_POST['input_three'];
    		            echo $this->entry_model->generate_new_graph($graph_type, $input_one, $input_one_child, '', '', $input_three, $graph_size, $graph_report_choice, 0);
    		        } else {
    		            echo $this->entry_model->generate_new_graph($graph_type, $input_one, $input_one_child, '', '', '', $graph_size, $graph_report_choice, 0);
    		        }
    		    }
            }
        ?>
		<div id='current_graphs_container'>
    		<div id='current_graph'>
    			<div id='graph'></div>
            	<input type="hidden" value="0" id="total_current_graphs">
    				<button id='save_graph_button' name='save_graph_button' class='btn' type='button' onclick='save_graph()'>Save Graph</button>
    			</div>
		</div>
		<div class="col-sm-12" id='saved_graphs_container'>
    		<div id='saved_graphs'>
            	<input type="hidden" value="0" id="total_saved_graphs">
    		</div>
		</div>
		<form action='<?php echo URL;?>Display/print_graphs' method='POST' target='_blank'>
    		<div id='print_graphs'>
    			<button name='print_graphs_button' id='print_graphs_button' class='btn' onclick='set_curr_graphs();' title='PRINT GRAPHS'>PRINT</button>
    		</div>
		</form>
	</div>
</div>
</body>
</html>