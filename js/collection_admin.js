(function ($) {

/**
 * Attaches administrative stuff to each entity collection.
 */
Drupal.behaviors.entityCollection = {
  attach: function (context, settings) {
     $('.entity-collection .item').draggable();
  }
};
})(jQuery);