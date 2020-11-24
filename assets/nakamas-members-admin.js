
// jQuery
jQuery(document).ready(function($) {

  // datepicker
  // $("#datepicker").datepicker({
  //   dateFormat: "dd/mm/yy",
  //   changeMonth: true,
  //   changeYear: true,
  //   yearRange: "-100:+0",
  // });

  /*
   * ADMIN
  **/
	// simple multiple select
	// $('#nkms_select2_users').select2();

	// multiple select with AJAX search - USERS
	$('#nkms_select2_users').select2({
  		ajax: {
  			url: ajaxurl, // AJAX URL is predefined in WordPress admin
  			dataType: 'json',
  			delay: 250, // delay in ms while typing when to perform a AJAX search
  			data: function (params) {
    				return {
      				users_query: params.term, // search query
      				action: 'nkms_get_custom_users' // AJAX action for nakamas-functions-admin.php
    				};
  			},
  			processResults: function( data ) {
				var options = [];
				if ( data ) {
					// data is the array of arrays, and each of them contains ID and the name of user
					$.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
						options.push( { id: text[0], text: text[1]  } );
					});
				}
				return {
					results: options
				};
			},
			cache: true
		},
		minimumInputLength: 1 // the minimum of symbols to input before perform a search
	});

  $('#nkms_select2_users').on('change', function() {
    var user_id = $('#nkms_select2_users').val();

    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'nkms_user_results',
        nkms_select_users_user_id: user_id,
      },
      success: function(response) {
         $('#nkms_user_results').html( response.data );
      },
      error: function(response) {
        $('#nkms_user_results').html( response.data );
      }
    });
  });

  // multiple select with AJAX search - GROUPS
	$('#nkms_select2_groups').select2({
  		ajax: {
  			url: ajaxurl, // AJAX URL is predefined in WordPress admin
  			dataType: 'json',
  			delay: 250, // delay in ms while typing when to perform a AJAX search
  			data: function (params) {
    				return {
      				groups_query: params.term, // search query
      				action: 'nkms_get_dance_groups' // AJAX action for nakamas-functions-admin.php
    				};
  			},
  			processResults: function( data ) {
				var options = [];
				if ( data ) {
					// data is the array of arrays, and each of them contains ID, name of group, type etc
					$.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
						options.push( { id: text[0], text: text[1] } );
					});
				}
				return {
					results: options
				};
			},
			cache: true
		},
		minimumInputLength: 1 // the minimum of symbols to input before perform a search
	});

  $('#nkms_select2_groups').on('change', function() {
    var both_id = $('#nkms_select2_groups').val();
		var both_id_array = both_id.split('-', );
		var user_id = both_id_array[0];
		var group_id = both_id_array[1];

    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'nkms_groups_results',
        nkms_select_groups_user_id: user_id,
        nkms_select_groups_group_id: group_id,
      },
      success: function(response) {
         $('#nkms_groups_results').html( response.data );
      },
      error: function(response) {
        $('#nkms_groups_results').html( response.data );
      }
    });

  });


	// Toggle behavior
	$('#add_dancer_to_dance_school_toggle').on('click', function() {
    $('#add_dancer_to_dance_school').toggle(250);
  });
	$('#remove_dancer_from_dance_school_toggle').on('click', function() {
    $('#remove_dancer_from_dance_school').toggle(250);
  });
	$('#add_dancer_to_dance_group_toggle').on('click', function() {
    $('#add_dancer_to_dance_group').toggle(250);
  });
	$('#remove_dancer_from_dance_group_toggle').on('click', function() {
    $('#remove_dancer_from_dance_group').toggle(250);
  });
	$('#add_dancer_to_guardian_toggle').on('click', function() {
    $('#add_dancer_to_guardian').toggle(250);
  });
	$('#remove_dancer_from_guardian_toggle').on('click', function() {
    $('#remove_dancer_from_guardian').toggle(250);
  });


	/*
	 * ACTIONS
	 */
	// Add dancer to dance school
	$('#add_dancer_to_dance_school > form').on('submit', function(e) {
		e.preventDefault();
		var dance_school_id = $('#add_dancer_to_dance_school_ds').val();
		var dancer_id = $('#add_dancer_to_dance_school_dancer').val();
		console.log(dance_school_id + ' / ' +dancer_id);

    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'admin_add_dancer_to_dance_school',
        admin_add_dancer_to_dance_school_dance_school_id: dance_school_id,
				admin_add_dancer_to_dance_school_dancer_id: dancer_id
      },
      success: function(response) {
         $('#add_dancer_to_dance_school .admin-ajax-response').html( response.data );
				 $('#add_dancer_to_dance_school .admin-ajax-response').show();
				 setTimeout(function(){
					 $('#add_dancer_to_dance_school .admin-ajax-response').slideUp();
				 }, 5000);
      },
      error: function(response) {
        $('#add_dancer_to_dance_school .admin-ajax-response').html( response.data );
				$('#add_dancer_to_dance_school .admin-ajax-response').show();
				// setTimeout(function(){
				// 	$('#add_dancer_to_dance_school .admin-ajax-response').slideUp();
				// }, 3000);
      }
    });

  });

	// Remove dancer from dance school
	// Populate 2nd select with Dancers from Dance School only
	$('#remove_dancer_from_dance_school_ds').on('change', function(e) {
		var dance_school_id = $('#remove_dancer_from_dance_school_ds').val();

		$.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'admin_get_dancers_of_dance_school',
        admin_get_dancers_of_dance_school_dance_school_id: dance_school_id,
      },
      success: function(response) {
         $('#remove_dancer_from_dance_school_dancer').removeAttr( 'disabled' );
				 $('#remove_dancer_from_dance_school_dancer').html( response.data );
      },
      error: function(response) {
        $('#add_dancer_to_dance_school .admin-ajax-response').html( '<p class="text-danger">An error occured. Please try again.</p>' );
      }
    });
	});
	// submit form
	$('#remove_dancer_from_dance_school > form').on('submit', function(e) {
		e.preventDefault();
		var dance_school_id = $('#remove_dancer_from_dance_school_ds').val();
		var dancer_id = $('#remove_dancer_from_dance_school_dancer').val();

    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'admin_remove_dancer_from_dance_school',
        admin_remove_dancer_from_dance_school_ds_dance_school_id: dance_school_id,
				admin_remove_dancer_from_dance_school_ds_dancer_id: dancer_id
      },
      success: function(response) {
         $('#remove_dancer_from_dance_school .admin-ajax-response').html( response.data );
      },
      error: function(response) {
        $('#remove_dancer_from_dance_school .admin-ajax-response').html( response.data );
      }
    });

  });

	// Remove dancer from guardian
	$('#remove_dancer_from_guardian').on('click', function(e) {
		e.preventDefault();
    var user_id = $('#remove_dancer_from_guardian').attr("id");
    console.log( user_id );
		console.log('button to remove dancer pressed!');
  });

});
