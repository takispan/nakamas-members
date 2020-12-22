
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
      },
      error: function(response) {
        $('#add_dancer_to_dance_school .admin-ajax-response').html( response.data );
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

	// Add dancer to dance group
	// Populate 2nd select with Dancers from Dance School only
	$('#add_dancer_to_dance_group_name').on('change', function(e) {
		var both_id = $('#add_dancer_to_dance_group_name').val();
		var both_id_array = both_id.split('-', );
		var dance_school_id = both_id_array[0];

		$.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'admin_get_dancers_of_dance_school',
        admin_get_dancers_of_dance_school_dance_school_id: dance_school_id,
      },
      success: function(response) {
	      $('#add_dancer_to_dance_group_dancer').removeAttr( 'disabled' );
				$('#add_dancer_to_dance_group_dancer').html( response.data );
      },
      error: function(response) {
        $('#add_dancer_to_dance_group .admin-ajax-response').html( '<p class="text-danger">An error occured. Please try again.</p>' );
      }
    });
	});
	// submit form
	$('#add_dancer_to_dance_group > form').on('submit', function(e) {
		e.preventDefault();
		var both_id = $('#add_dancer_to_dance_group_name').val();
		var both_id_array = both_id.split('-', );
		var dance_school_id = both_id_array[0];
		var group_id = both_id_array[1];
		var dancer_id = $('#add_dancer_to_dance_group_dancer').val();

    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'admin_add_dancer_to_dance_group',
        admin_add_dancer_to_dance_group_dance_school_id: dance_school_id,
				admin_add_dancer_to_dance_group_group_id: group_id,
				admin_add_dancer_to_dance_group_dancer_id: dancer_id
      },
      success: function(response) {
        $('#add_dancer_to_dance_group .admin-ajax-response').html( response.data );
      },
      error: function(response) {
        $('#add_dancer_to_dance_group .admin-ajax-response').html( response.data );
      }
    });
  });

	// Remove dancer from dance group
	// Populate 2nd select with Dancers from Dance School only
	$('#remove_dancer_from_dance_group_name').on('change', function(e) {
		var both_id = $('#remove_dancer_from_dance_group_name').val();
		var both_id_array = both_id.split('-', );
		var dance_school_id = both_id_array[0];
		var dance_group_id = both_id_array[1];

		$.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'admin_get_dancers_of_dance_group',
        admin_get_dancers_of_dance_school_dance_school_id: dance_school_id,
        admin_get_dancers_of_dance_school_dance_group_id: dance_group_id,
      },
      success: function(response) {
				$('#remove_dancer_from_dance_group_dancer').removeAttr( 'disabled' );
				$('#remove_dancer_from_dance_group_dancer').html( response.data );
      },
      error: function(response) {
        $('#remove_dancer_from_dance_group .admin-ajax-response').html( '<p class="text-danger">An error occured. Please try again.</p>' );
      }
    });
	});
	// submit form
	$('#remove_dancer_from_dance_group > form').on('submit', function(e) {
		e.preventDefault();
		var both_id = $('#remove_dancer_from_dance_group_name').val();
		var both_id_array = both_id.split('-', );
		var dance_school_id = both_id_array[0];
		var group_id = both_id_array[1];
		var dancer_id = $('#remove_dancer_from_dance_group_dancer').val();

    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'admin_remove_dancer_from_dance_group',
        admin_remove_dancer_from_dance_group_dance_school_id: dance_school_id,
				admin_remove_dancer_from_dance_group_group_id: group_id,
				admin_remove_dancer_from_dance_group_dancer_id: dancer_id
      },
      success: function(response) {
        $('#remove_dancer_from_dance_group .admin-ajax-response').html( response.data );
      },
      error: function(response) {
        $('#remove_dancer_from_dance_group .admin-ajax-response').html( response.data );
      }
    });
  });

	// Add dancer to guardian
	$('#add_dancer_to_guardian > form').on('submit', function(e) {
		e.preventDefault();
    var guardian_id = $('#add_dancer_to_guardian_guardian').val();
		var dancer_id = $('#add_dancer_to_guardian_dancer').val();

		$.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'admin_add_dancer_to_guardian',
        admin_add_dancer_to_guardian_guardian_id: guardian_id,
        admin_add_dancer_to_guardian_dancer_id: dancer_id,
      },
      success: function(response) {
				$('#add_dancer_to_guardian .admin-ajax-response').html( response.data );
      },
      error: function(response) {
        $('#add_dancer_to_guardian .admin-ajax-response').html( '<p class="text-danger">An error occured. Please try again.</p>' );
      }
    });
	});


	// Remove dancer from guardian
	// Populate 2nd select with Dancers from Dance School only
	$('#remove_dancer_from_guardian_guardian').on('change', function(e) {
    var guardian_id = $('#remove_dancer_from_guardian_guardian').val();

		$.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'admin_get_dancers_of_guardian',
        admin_get_dancers_of_guardian_guardian_id: guardian_id,
      },
      success: function(response) {
        $('#remove_dancer_from_guardian_dancer').removeAttr( 'disabled' );
				$('#remove_dancer_from_guardian_dancer').html( response.data );
      },
      error: function(response) {
        $('#remove_dancer_from_guardian .admin-ajax-response').html( '<p class="text-danger">An error occured. Please try again.</p>' );
      }
    });
	});
	// submit form
	$('#remove_dancer_from_guardian > form').on('submit', function(e) {
		e.preventDefault();
		var guardian_id = $('#remove_dancer_from_guardian_guardian').val();
		var dancer_id = $('#remove_dancer_from_guardian_dancer').val();

    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'admin_remove_dancer_from_guardian',
        admin_remove_dancer_from_guardian_guardian_id: guardian_id,
				admin_remove_dancer_from_guardian_dancer_id: dancer_id
      },
      success: function(response) {
        $('#remove_dancer_from_guardian .admin-ajax-response').html( response.data );
      },
      error: function(response) {
        $('#remove_dancer_from_guardian .admin-ajax-response').html( response.data );
      }
    });
  });

});
