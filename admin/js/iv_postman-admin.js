var ObjectHolder;
jQuery(document).ready(function($) {
	$( ".fields" ).sortable();

	$('input[name="create_new_form"]').click(function() {
		//new form editor
		$('#post-body-content').append($('#question_template_holder').html());
	});
	
	$('#post-body-content').on('click', '.step_adder', function() {
		$(this).parents('.single').first().find('.steps').first().append($('#single_step_template').html());
	});


	$('#post-body-content').on('click', '.field_remover', function() {
		$(this).parents('.field ').first().remove();
	});

	$('#post-body-content').on('click', '.step_remover', function() {
		$(this).parents('.step ').first().remove();
	});


	

	$('input[name="save_forms"]').click(function() {
		//new form editor
		var saveResult = {};
		$('#post-body-content .question_div').each(function(cq_index, cq_element) {
			saveResult[cq_index] = {};
			saveResult[cq_index]['options'] = {};
			saveResult[cq_index]['steps'] = {};
			
			saveResult[cq_index]['options']['arrays'] = []; 
			saveResult[cq_index]['options']['bools'] = []; 
			$(cq_element).find('.options_container').first().find('input').each(function(op_index, op_element) {		
				var OpName = $(op_element).attr('name');
				OpName = OpName.replace("[]", '');
				if ( $(op_element).attr('type') == "checkbox") {
					saveResult[cq_index]['options']['bools'].push(OpName);
	
					if ($(op_element).is(":checked"))
					{
						saveResult[cq_index]['options'][OpName] = 1;
					} else {
						saveResult[cq_index]['options'][OpName] = 0;
					}
				} else {
					//not a checkbox, just save
					saveResult[cq_index]['options'][OpName] = $(op_element).val();

				}
			});

			$(cq_element).find('.step').each(function(st_index, st_element) {
				saveResult[cq_index]['steps'][st_index] = {};

				
				$(st_element).find('.field').each(function(fl_index, fl_element) {
					saveResult[cq_index]['steps'][st_index][fl_index] = {};
					saveResult[cq_index]['steps'][st_index][fl_index]['arrays'] = []; 
					saveResult[cq_index]['steps'][st_index][fl_index]['bools'] = []; 

					$(fl_element).find('input').each(function(op_index, op_element) {
						var OpName = $(op_element).attr('name');
						OpName = OpName.replace("[]", '');
						
						if ( $(op_element).attr('type') == "checkbox") {
							saveResult[cq_index]['steps'][st_index][fl_index]['bools'].push(OpName);
							if ($(op_element).is(":checked"))
							{
								saveResult[cq_index]['steps'][st_index][fl_index][OpName] = 1;
							} else {
								saveResult[cq_index]['steps'][st_index][fl_index][OpName] = 0;
							}

						} else {
							saveResult[cq_index]['steps'][st_index][fl_index][OpName] = $(op_element).val();
						}
					});	

					$(fl_element).find('select').each(function(op_index, op_element) {
						var OpName = $(op_element).attr('name');
						OpName = OpName.replace("[]", '');
						saveResult[cq_index]['steps'][st_index][fl_index][OpName] = $(op_element).val();
						saveResult[cq_index]['steps'][st_index][fl_index]['arrays'].push(OpName);
					});	

				});
			});
		});

		jQuery.ajax({
			url: ajaxurl,
			data: {
				action: "savequestions",
				question: saveResult
			},
			type: 'POST',
			complete: function(data) {
				location.reload();
			}
		  });
		//console.log(saveResult);
	});
	$(".stepcode").on("click", function () {
		$(this).select();
	 });
	
	$('#post-body-content').on('click', '.field_adder', function() {
		ObjectHolder = $(this).parents('.step').first().find('.fields').first();

		$.confirm({
			title: 'Field type?',
			content: 'Please choose a field type you would like to add:',
			buttons: {
				text_field: {
					text: 'Text field', // With spaces and symbols
					action: function () {
						ObjectHolder.append($('#text_field_template').html());
						setTimeout(function() {
							$("html, body").animate({ scrollTop: $(document).height() }, "fast");
						}, 600);					}
				},
				blank_field: {
					text: 'Blank divider', // With spaces and symbols
					action: function () {
						ObjectHolder.append($('#splitter_template').html());
						setTimeout(function() {
							$("html, body").animate({ scrollTop: $(document).height() }, "fast");
						}, 600);					}
				},
				dropdown_field: {
					text: 'Drowdown', // With spaces and symbols
					action: function () {
						ObjectHolder.append($('#dropdown_template').html());
						setTimeout(function() {
							$("html, body").animate({ scrollTop: $(document).height() }, "fast");
						}, 600);
					}
				}
			}
		});
		$( ".fields" ).sortable();

	});


});