jQuery(document).ready(function($) {
  $('select') .dropdown();

    jQuery(".postmanform").submit(function(e) {

      var form = jQuery(this);
      if (!form.hasClass('postmanform')) {
        return true;
      }
	 
     //load overlay and remove previous errors
     $.LoadingOverlay("show");
   

     //prepare form data and submit it
     var formData = $('.postmanform').serialize();
     e.preventDefault();
     jQuery.ajax({
       url: $('.postmanform').first().attr('action'),
       data: {
         action: 'SaveAnswer',
         _wpnonce: $('.postmanform').first().find('#_wpnonce').first().val(),
         _wp_http_referer: $('.postmanform').first().find('input[name="_wp_http_referer"]').first().val(),
         data: formData
       },
       cache: false, // To unable request pages to be cached
       processData: true, // To send DOMDocument or non processed data file it is set to false,
       dataType: 'json',
       type: 'POST',
       complete: function(data) {
         $.LoadingOverlay("hide");
         response = data.responseJSON;
         HandleResponse(form, response);
        }
      });
      return true;
     
    });

});



/**
 * Handles ajax registration response
 * @param       {jQuery OBJECT} form     jQuery selector for form being handled
 * @param       {OBJECT} response Response in object format being handled
 * @constructor
 */
function HandleResponse(form, response) {
  //handle the response
  
  jQuery('.error').removeClass('error');
  jQuery('.error_element').remove();
  if (response.status == "error") {
			jQuery.each (response.data, function(key, value) {
        if (jQuery('#'+ key +'-error').length > 0) {
          jQuery('#'+ key +'-error').remove();
        }
          jQuery('[name="'+ key +'"]').first().parents('.field').first().addClass('error');
          var teXt = '';
          jQuery.each (value, function(keys, values) {
            teXt = teXt + ' ' + values;
          });
          jQuery('[name="'+ key +'"]').first().parents('.field').first().append('<div id="'+key+'-error" class="error ui mini pointing basic label transition error_element" style="display: inline-block;">'+teXt+'</div>');
			});
    } else if (response.status =='success') {
      if (response.redirect != false) {
        window.location.href = response.redirect;
      } else {
        location.reload();
      }
    }
  

}

function deparam(query) {
    var pairs, i, keyValuePair, key, value, map = {};
    // remove leading question mark if its there
    if (query.slice(0, 1) === '?') {
        query = query.slice(1);
    }
    if (query !== '') {
        pairs = query.split('&');
        for (i = 0; i < pairs.length; i += 1) {
            keyValuePair = pairs[i].split('=');
            key = decodeURIComponent(keyValuePair[0]);
            value = (keyValuePair.length > 1) ? decodeURIComponent(keyValuePair[1]) : undefined;
            map[key] = value;
        }
    }
    return map;
}
