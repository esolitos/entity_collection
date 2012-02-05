(function ($) {
Drupal.behaviors.entitycollectionAdmin = {
  attach: function (context, settings) {
    var acdb = [];
    $('#edit-entity-type', context).change(attachAutocomplete);
    $('#edit-content-select', context).once('autocomplete', function() {
      var $input = $('#edit-content-select')
        .attr('autocomplete', 'OFF')
        .attr('aria-autocomplete', 'list');
      $($input[0].form).submit(Drupal.autocompleteSubmit);
      $input.parent()
        .attr('role', 'application')
        .append($('<span class="element-invisible" aria-live="assertive"></span>')
          .attr('id', $input.attr('id') + '-autocomplete-aria-live')
      );
      attachAutocomplete();
    });

    function attachAutocomplete() {
      var uri = '/entity_collection/autocomplete/' + $('#edit-entity-type').val();
      var $input = $('#edit-content-select');
      if (!acdb[uri]) {
        acdb[uri] = new Drupal.ACDB(uri);
      }
      new Drupal.jsAC($input, acdb[uri]);
    }
  }
}

})(jQuery);