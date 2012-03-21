(function ($) {
  Drupal.behaviors.entitycollectionAdmin = {
    attach: function (context, settings) {
      var acdb = [];
      var entity_collection_forms = Drupal.settings.entity_collection;
      $.each(entity_collection_forms, function(entity_collection, form_settings) {
        var path = form_settings.path;
        var form = $('form.' + entity_collection, context);
        var input = $("input[name='content_select']", form)
        var entity_type = $("select[name='entity_type']", form)
        input.once('autocomplete', function() {
    	  entity_type.change(attachAutocomplete);
          input.attr('autocomplete', 'OFF').attr('aria-autocomplete', 'list');
          $(input[0].form).submit(Drupal.autocompleteSubmit);
          input.parent()
            .attr('role', 'application')
            .append($('<span class="element-invisible" aria-live="assertive"></span>')
                    .attr('id', input.attr('id') + '-autocomplete-aria-live')
                   );
          attachAutocomplete();
        });
        function attachAutocomplete() {
    	  var uri = path  + '/' + entity_type.val()
          if (!acdb[uri]) {
            acdb[uri] = new Drupal.ACDB(uri);
          }
          input.unbind();
          new Drupal.jsAC(input, acdb[uri]);
        }
      });
    }
  }

})(jQuery);