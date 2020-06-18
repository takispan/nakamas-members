
// jQuery
jQuery(document).ready(function($) {

  // datepicker
  $("#datepicker").datepicker({
            dateFormat : "dd/mm/yy"
        });

  // Tab reload test
  $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
    var tabLink = $(e.target).attr('href');
    if ( tabLink == '#dance-school' ) {
      $('#myTab2 a[href="#ds-overview"]').tab('show');
    }
    if ( tabLink.startsWith('#ds') ) {
      tabLink = '#dance-school';
      tabLink2 = $(e.target).attr('href');
      sessionStorage.setItem('activeTab', tabLink);
      sessionStorage.setItem('activeTab2', tabLink2);
    }
    else {
      sessionStorage.setItem('activeTab', tabLink);
    }
	});
	var activeTab = sessionStorage.getItem('activeTab');
  if ( activeTab == '#dance-school' ) {
    var activeTab2 = sessionStorage.getItem('activeTab2');
  }
	if(activeTab){
		$('#myTab a[href="' + activeTab + '"]').tab('show');
	}
  if(activeTab2) {
    $('#myTab a[href="' + activeTab + '"]').tab('show');
    $('#myTab2 a[href="' + activeTab2 + '"]').tab('show');
  }

  /*
   * DANCERS
   */
  // Reload dancers & groups lists
  $('a[href="#ds-dancers"]').on('show.bs.tab', function(e) {
    window.location.reload();
  });
  $('a[href="#ds-dance-groups"]').on('show.bs.tab', function(e) {
    window.location.reload();
  });

  // Add dancer to dance school list of dancers
  $('form#add-dancers').on('submit', function(e) {
    e.preventDefault();
    var dancer_to_add = $('#add_dancer_to_ds').val();

    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'ds_add_dancer',
        dancer: dancer_to_add,
      },
      success: function(response) {
          $('form#add-dancers .ajax-response').html( response.data );
      },
      error: function(response) {
        $('form#add-dancers .ajax-response').html("<p class='text-danger'>Something went wrong, please try again.</p>");
      }
    });
  });

  // Pass data to populate single dancer tab
  $('.single-dancer').on('click', function(e) {
    var single_dancer_id = $(this).attr('data-dancer-id');

    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'ds_single_dancer',
        single_dancer_id: single_dancer_id,
      },
      success: function(response) {
        $('.ds-single-dancer').html( response.data );
        $('#myTab2 a[href="#ds-dancers"]').removeClass('active');
        $('#myTab2 a[href="#ds-dancer-single"]').addClass('active');
        window.location.reload();
      },
      error: function(response) {
        $('.ds-single-dancer').html( '<p class="text-danger">Error getting dancer data. Please try again.</p>' );
        $('#myTab2 a[href="#ds-dancers"]').removeClass('active');
        $('#myTab2 a[href="#ds-dancer-single"]').addClass('active');
        window.location.reload();
      }
    });
  });

  // Change dancer status
  $('.change-dancer-status').on('click', function(e) {
    var single_dancer_id = $(this).attr('data-dancer-id');

    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'ds_change_status',
        dancer_id: single_dancer_id,
      },
      success: function(response) {
        console.log(response);
        window.location.reload();
      },
      error: function(response) {
        console.log(response);
        window.location.reload();
      }
    });
  });

  // Remove dancer from dance school list of dancers
  $('.remove-dancer').on('click', function(e) {
    var single_dancer_id = $(this).attr('data-dancer-id');
    console.log(single_dancer_id);

    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'ds_remove_dancer',
        single_dancer_id: single_dancer_id,
      },
      success: function(response) {
        console.log(response);
        window.location.reload();
      },
      error: function(response) {
        console.log(response);
      }
    });
  });

  /*
   * GROUPS
   */
  // Add group to dance school list of groups
  $('form#add-groups').on('submit', function(e) {
    e.preventDefault();
    //reset info messages
    $('.success_msg').css('display','none');
    $('.error_msg').css('display','none');
    //get user input from form
    var group_name = $('#add_group_name').val();
    var group_type = $('#add_group_type').val();

    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'ds_add_group',
        group_name: group_name,
        group_type: group_type,
      },
      success: function(response) {
        $('.success_msg').css('display','block');
        console.log(response);
      },
      error: function(response) {
        $('.error_msg').css('display','block');
        console.log(response);
      }
    });
  });

  // Pass data to populate single group tab
  $('.single-group').on('click', function(e) {
    var single_group_id = $(this).attr('data-group-id');
    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'ds_single_group',
        single_group_id: single_group_id,
      },
      success: function(response) {
        console.log("Response: " + response);
        window.location.reload();
      },
      error: function(response) {
        console.log(response);
      }
    });
    $('.ajax')[0].reset();
  });

  // Add dancer to dance group
  $('form#add-group-dancer').on('submit', function(e) {
    e.preventDefault();
    //reset info messages
    $('.success_msg').css('display','none');
    $('.error_msg').css('display','none');
    //var url = $(this).attr('action');
    var group_dancer_to_add = $('#add_dancer_to_group').val();
    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'ds_add_group_dancer',
        dancer: group_dancer_to_add,
      },
      success: function(response) {
        $('.success_msg').html( response.data );
        console.log(response);
      },
      error: function(response) {
        $('.error_msg').css('display','block');
        console.log(response);
      }
    });
  });

  // Remove dancer from dance group
  $('form#remove-group-dancer').on('submit', function(e) {
    e.preventDefault();
    //reset info messages
    $('.success_msg').css('display','none');
    $('.error_msg').css('display','none');
    //var url = $(this).attr('action');
    var group_dancer_to_remove = $('#remove_dancer_from_group option').filter(':selected').val();
    console.log(group_dancer_to_remove);
    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'ds_remove_group_dancer',
        dancer: group_dancer_to_remove,
      },
      success: function(response) {
        $('.success_msg').css('display','block');
        console.log(response);
      },
      error: function(response) {
        $('.error_msg').css('display','block');
        console.log(response);
      }
    });
  });

  // Change group status
  $('.change-group-status').on('click', function(e) {
    var group_id = $(this).attr('data-group-id');
    console.log(group_id);

    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'ds_group_change_status',
        group_id: group_id,
      },
      success: function(response) {
        console.log(response);
        window.location.reload();
      },
      error: function(response) {
        console.log(response);
      }
    });
  });



  // REGISTRATION
  $("#select_role").change(function() {
    //e.preventDefault();
    var selRole = $('#select_role').val();
    console.log(selRole);
    if ( selRole === 'dance-school') {
      $('#ds-reg-fields-dancer').hide();
      $('#ds-reg-fields-dance-school').show();
    }
    if ( selRole === 'dancer') {
      $('#ds-reg-fields-dance-school').hide();
      $('#ds-reg-fields-dancer').show();
    }
    if ( selRole === 'guardian') {
      $('#ds-reg-fields-dance-school').hide();
      $('#ds-reg-fields-dancer').hide();
    }
  });
  // // Custom registration
  // $('#submit_registration').on('submit', function() {
  //   e.preventDefault();
  //   var select_role = $('#select_role option').filter(':selected').val();
  //   console.log(select_role);
  //
  //   $.ajax({
  //     _ajax_nonce: nkms_ajax.nonce,
  //     url: nkms_ajax.ajax_url,
  //     type: "POST",
  //     data: {
  //       action: 'ds_update_registration_fields',
  //       role: select_role,
  //     },
  //     success: function(response) {
  //       $('#ds-reg-fields').css('display','block');
  //       console.log(response);
  //     },
  //     error: function(response) {
  //       $('#ds-reg-fields').css('display','none');
  //       console.log(response);
  //     }
  //   });
  // });

});