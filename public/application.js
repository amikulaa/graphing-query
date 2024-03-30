/* Script for PIT */

// TimeOut success message on input (home) screen
if ($('#user_message').length > 0) {
  setTimeout(() => {
	  const message = document.getElementById('user_message');
	  message.style.display = 'none';
	}, 800); //miliseconds
}

// Hide error div when not in use
if ($('#error_message_div').length > 0) {
	var error_message = document.getElementById('error_message').value;
	if(error_message == ""){
		document.getElementById('error_message_div').hidden = true;
	} else {
		document.getElementById('error_message_div').hidden = false;
	}
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
  document.body.scrollTop = 0; // For Safari
  document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}

// When the user clicks on the button, scroll to the top of the document
function topSOMEFunction() {
	window.scrollBy(0, -200);
  }

// When the user clicks on the button, scroll to the bottom of the document
function downSOMEFunction() {
	window.scrollBy(0, 200);
}

// When the user clicks on the button, scroll to the bottom of the document
function downFunction() {
	window.scrollTo(0,document.body.scrollHeight);
}

// any window on load, make sure session variabels are updated
window.addEventListener("load", () => {
	if ($('#navigation').length > 0 || $('#nav').length > 0) {
  		get_current_tab();
	}
}); 

/* Sets curr tab session var */
function set_current_tab(tab_name){
	var postUrl = url + 'Home/set_current_tab';
	var postData = {
				tab_name : tab_name
		};
	$.ajax({
        url: postUrl,
        type: 'POST',
        data: postData,
        dataType: 'html',
        error: function (xhr, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        },
        success: function(response) {	
			if(response != 0){
				// DEBUG:
				console.log('set_current_tab():[' + response + ']');
			}
        }	 
    });
}

/* Gets the current tab */
function get_current_tab(){
	var tab_arr = ['home_tab', 'approve_tab', 'display_tab', 'help_tab'];
	var postUrl = url + 'Home/get_current_tab';
	$.ajax({
        url: postUrl,
        type: 'POST',
        dataType: 'html',
        error: function (xhr, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        },
        success: function(response) {	
			if(response != 1){
				for(var i = 0; i < tab_arr.length; i++){
					if(response == tab_arr[i]){
						document.getElementById(tab_arr[i]).style.color = '#FFFFFF';
						document.getElementById(tab_arr[i]).style.border= '1px solid #FFFFFF';
					} 
				}
			}
        }	 
    });
}

/* Set date and time of report automatically */
function set_date_time(){
	var date = new Date();
	document.getElementById('incident_date').value = date.getFullYear() + "-" + ((date.getMonth() < 10 ? '0' : '') + (date.getMonth() + 1)) + "-" + ((date.getDate() < 10 ? '0' : '') + date.getDate());
	document.getElementById('incident_time').value = ((date.getHours() < 10 ? '0' : '') + date.getHours()) + ":" + ((date.getMinutes() < 10 ? '0' : '') + date.getMinutes());
}

/* Fills the type select for service or incident */
function get_type_options(){
	if(document.getElementById('report_choice') == null || document.getElementById('report_choice') == ''){
		return;
	} 
	var postUrl = url + 'Home/get_type_options';
	var postData = {
				report_choice : document.getElementById('report_choice').value
		};
	$.ajax({
        url: postUrl,
        type: 'POST',
        data: postData,
        dataType: 'html',
        error: function (xhr, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        },
        success: function(response) {	
			if(response != null){
				for(var i = 0; i < $('#total_report_type_items').val(); i++){
					$('#option_value').remove();
				}
				var count_options = response.split("id='option_value'");
				$('#report_type').append(response);
				$('#total_report_type_items').val(count_options.length - 1);
			} else {
				// DEBUG:
				//document.getElementById("error_message").style.color = "red";
				//document.getElementById("error_message").value = response;
				console.log(response);
			}
			get_resolution_options();
        }	 
    });
}

/* Fills the resolution select for service or incident */
function get_resolution_options(){
	var postUrl = url + 'Home/get_resolution_options';
	var postData = {
				report_choice : document.getElementById('report_choice').value
		};
	$.ajax({
        url: postUrl,
        type: 'POST',
        data: postData,
        dataType: 'html',
        error: function (xhr, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        },
        success: function(response) {	
			if(response != null){
				for(var i = 0; i < $('#total_report_resolution_items').val(); i++){
					$('#resolution_option').remove();
				}
				var count_options = response.split("id='resolution_option'");
				$('#report_resolution').append(response);
				$('#total_report_resolution_items').val(count_options.length - 1);
			} else {
				// DEBUG:
				//document.getElementById("error_message").style.color = "red";
				//document.getElementById("error_message").value = response;
				console.log(response);
			}
        }	 
    });
}

/* Fills the resolution select for service or incident */
function get_next_incident_num(){
	var postUrl = url + 'Home/get_next_incident_num';
	$.ajax({
        url: postUrl,
        type: 'POST',
        dataType: 'text',
        error: function (xhr, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        },
        success: function(response) {	
			if(response != null){
				document.getElementById('incident_num').value = response;
			}
        }	 
    });
}

// onclick changes approval value in the database
function update_approval_value(id){
	var is_checked = document.getElementById('approve'+id).checked == 1 ? 1 : 0;
	document.getElementById('approve'+id).checked = is_checked;
	var column_arr = {[id] : is_checked};
	
	var postUrl = url + 'Approve/update_approval_value';
	var postData = {
				column_arr : column_arr
		};
	$.ajax({
        url: postUrl,
        type: 'POST',
        data: postData,
        dataType: 'html',
        error: function (xhr, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        },
        success: function(response) {	
			if(response != 0){
				console.log(response);
			}
        }	 
    });
}

// onclick changes report type
function update_report_type(id){
	if(confirm('Change report from incident to service?')){
		var is_checked = document.getElementById('type'+id).checked == 1 ? 1 : 0;
		document.getElementById('type'+id).checked = is_checked;
		var column_arr = {[id] : is_checked};
		
		var postUrl = url + 'Approve/update_report_type';
		var postData = {
					column_arr : column_arr
			};
		$.ajax({
	        url: postUrl,
	        type: 'POST',
	        data: postData,
	        dataType: 'html',
	        error: function (xhr, thrownError) {
	            alert(xhr.status);
	            alert(thrownError);
	        },
	        success: function(response) {	
				if(response != 0){
					console.log(response);
				}
				window.location.href = window.location.href;
	        }	 
	    });
	    
	} else {
		document.getElementById('type'+id).checked = false;
	}
}

// onchange fill more 
function get_child_one_fields(input_field){
	var postUrl = url + 'Display/get_child_field_one';
	var postData = {
				input_field : input_field
		};
	$.ajax({
        url: postUrl,
        type: 'POST',
        data: postData,
        dataType: 'text',
        error: function (xhr, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        },
        success: function(response) {	
			if(response != 1 && response != null){
				for(var i = 0; i <= $('#total_input_one_child_items').val(); i++){
					$('#one' + i).remove();
				}
				var count_options = response.split("id=");
				$('#input_one_child').append(response);
				$('#total_input_one_child_items').val(count_options.length - 1);
			} else {
				// DEBUG:
				console.log(response);
			}
		} 
    });
}

// onchange fill more 
function get_child_two_fields(input_field){
	var postUrl = url + 'Display/get_child_field_two';
	var postData = {
				input_field : input_field
		};
	$.ajax({
        url: postUrl,
        type: 'POST',
        data: postData,
        dataType: 'text',
        error: function (xhr, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        },
        success: function(response) {	
			if(response != 1 && response != null){
				for(var i = 0; i <= $('#total_input_two_child_items').val(); i++){
					$('#two' + i).remove();
				}
				var count_options = response.split("id=");
				$('#input_two_child').append(response);
				$('#total_input_two_child_items').val(count_options.length - 1);
				document.getElementById('input_two_child').required = true;
				document.getElementById('input_two').required = true;
			} else {
				// DEBUG:
				console.log(response);
			}
		} 
    });
}

var curr_graph_divs = [];
function set_curr_graph_div(graph_id){
	curr_graph_divs.push(graph_id);
}

var curr_graphs = [];
function set_curr_graph(graph){
	curr_graphs.push(graph);
	set_curr_graphs();
}

//sets graph images
function set_curr_graphs(){
	console.log(curr_graphs.length);
	console.log(curr_graphs);
	var postUrl = url + 'Display/set_curr_graphs';
	var postData = {curr_graphs : curr_graphs};
	$.ajax({
		url: postUrl, 
		type: 'POST', 
		data: postData, 
		dataType: 'text', 
		error: function(xhr, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}

// onclick save graph
function save_graph(){
	var postUrl = url + 'Display/save_graph';
	var graph_html = document.getElementById('graph').innerHTML == null ? '' : document.getElementById('graph').innerHTML;
	var postData = {
				graph_html : graph_html
		};
	if(graph_html == ''){
		return;
	}
	$.ajax({
        url: postUrl,
        type: 'POST',
        data: postData,
        dataType: 'text',
        error: function (xhr, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        },
        success: function(response) {	
			if(response != 0){
				document.getElementById('error_message').value = 'ERROR: Error Saving Graph';
			} else {
				window.location.href = window.location.href;
			}
		} 
    });
}

//delete saved graph
function delete_saved_graph(id){
	if(confirm('Delete Graph?')){
		var postUrl = url + 'Display/delete_graph';
		var postData = {id : id};
		$.ajax({
	        url: postUrl,
	        type: 'POST',
	        data: postData,
	        dataType: 'text',
	        error: function (xhr, thrownError) {
	            alert(xhr.status);
	            alert(thrownError);
	        },
	        success: function(response) {	
				if(response != 0){
					document.getElementById('error_message').value = 'ERROR';
				} else {
					window.location.href = window.location.href;
				}
			} 
	    });
	} 
}

//load saved graphs
function load_saved_graphs(){
	var postUrl = url + 'Display/load_saved_graphs';
	$.ajax({
        url: postUrl,
        type: 'POST',
        dataType: 'json',
        error: function (xhr, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        },
        success: function(response) {	
			 if(response != 1){
				for(var i = 0; i < response.length; i++){
					var new_button = '<button title="DELETE" id="' + response[i].id + '" type="button" class="btn_back" onclick="delete_saved_graph(this.id)"><i class="fa-solid fa-trash"></i></button>';
					$('#saved_graphs').append("<div id='chart" + response[i].id + "' >");
					$('#saved_graphs').append(response[i].graph_html);
					$('#saved_graphs').append(new_button);
					$('#saved_graphs').append("</div>");
				}
			} 
		} 
    });
}

//error
function error_inputs(){
	document.getElementById('error_message').value = 'ERROR';
}

//change table to the table stats
function change_display_report_choice(type){	
	var postUrl = url + 'Display/get_table_stats/' + type;
	$.ajax({
        url: postUrl,
        type: 'POST',
        dataType: 'html',
        error: function (xhr, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        },
        success: function(response) {	
			 if(response != 1){
				 $('#table').empty();
				 $('#table').append(response);
			}
		} 
    });
}

//hides the description of the header if the user clicks on it
function bring_bottom_up(id){
	var id_only = id.match(/\d/g);
	id_only = id_only.join("");
	if(document.getElementById('body' + id_only).hidden == true){
		document.getElementById('body' + id_only).hidden = false;
		document.getElementById('carrot' + id_only).hidden = true;
	} else {
		document.getElementById('body' + id_only).hidden = true;
		document.getElementById('carrot' + id_only).hidden = false;
	}
}

//change table to the table stats
function set_curr_inner(id){	
	document.getElementById(id).style.color = '#FFFFFF';
}

///show third input for stacked
function show_third_input(){	
	var curr_graph_type = document.getElementById('graph_type').value;
	if(curr_graph_type == 'BarChart-StackedBase' || curr_graph_type == 'ColumnChart-StackedBase' || curr_graph_type == 'LineChart-Base'){
		document.getElementById('input_three_container').style.display = 'block';		
		document.getElementById('input_three').required = true;
	} else {
		document.getElementById('input_three_container').style.display = 'none';
		document.getElementById('input_three').required = false;
	}
}

// print function for report tables
function print_table(){
	$('#html_table').val(document.getElementById('table').innerHTML);
}

//shows the help input section
function show_help(){
	if(document.getElementById('reccomendation_content').hidden == true){
		document.getElementById('help_user_pick_graph').hidden = false;
		document.getElementById('reccomendation_content').hidden = false;
		document.getElementById('examples_content').hidden = true;
	} else {
		document.getElementById('help_user_pick_graph').hidden = true;
		document.getElementById('reccomendation_content').hidden = true;
		document.getElementById('examples_content').hidden = true;
	}
}

//shows the examples
function show_examples(){
	if(document.getElementById('examples_content').hidden == true){
		document.getElementById('help_user_pick_graph').hidden = false;
		document.getElementById('examples_content').hidden = false;
		document.getElementById('reccomendation_content').hidden = true;
	} else {
		document.getElementById('help_user_pick_graph').hidden = true;
		document.getElementById('examples_content').hidden = true;
		document.getElementById('reccomendation_content').hidden = true;
	}
}

// gives the reccomendation of graph type to the user
function show_reccomendation(input){
	switch(input){
		case null:
			return;
		case '1':
			document.getElementById('reccomendation').value = 'Column Chart';
			document.getElementById('reccomendation_container').hidden = false;
			break;
		case '2':
			document.getElementById('reccomendation').value = 'Line Chart';
			document.getElementById('reccomendation_container').hidden = false;
			break;
		case '3':
			document.getElementById('reccomendation').value = 'Pie Chart';
			document.getElementById('reccomendation_container').hidden = false;
			break;
	}
}

// show password or hide password
$("#show_hide_password a").on('click', function(event) {
	event.preventDefault();
	if($('#show_hide_password input').attr("type") == "text"){
		$('#show_hide_password input').attr('type', 'password');
		$('#show_hide_password i').addClass( "fa-eye-slash" );
		$('#show_hide_password i').removeClass( "fa-eye" );
	} else if($('#show_hide_password input').attr("type") == "password"){
		$('#show_hide_password input').attr('type', 'text');
	    $('#show_hide_password i').removeClass( "fa-eye-slash" );
	    $('#show_hide_password i').addClass( "fa-eye" );
	}
});

/* Checks if user is admin user */
$('#approve_tab').on('load', is_admin);
function is_admin(){
	var postUrl = url + 'Home/show_admin_tabs';
	$.ajax({
        url: postUrl,
        type: 'POST',
        dataType: 'text',
        error: function (xhr, thrownError) {
			alert(xhr.status);
			alert(thrownError);
        },
        success: function(response) {	     
			if(response != 1) {
				document.getElementById("approve_tab").hidden = true;
				document.getElementById("display_tab").hidden = true;
			} 
        }	 
    });
}