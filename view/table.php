<!-- view/display/table.php -->
<!DOCTYPE html>
<body onload='set_curr_inner("table_display");'>
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
    	<div id='user_select'>
    		<select id='report_choice_type' name='report_choice_type' onchange='change_display_report_choice(this.value)'>
    			<option value='incident' selected>Incident</option>
    			<option value='service'>Service</option>
    			<option value='total'>Total</option>
    		</select>
		</div>
        <div id='table' name='table'>
        	<?php echo $this->entry_model->get_table_stats('incident');?>
        </div>
    	<form action='<?php echo URL;?>Display/print_table' method='POST' target='_blank'>
            <input id='html_table' name='html_table' type='hidden' value=''></input>
    		<div id='print_table'>
    		<br/>
    			<button name='print_table_button' id='print_table_button' class='btn' onclick='print_table();' title='PRINT TABLES'>PRINT</button>
    		</div>
		</form>
	</div>
</div>
</body>
</html>