// Generated by CoffeeScript 1.6.2
(function($, window) {
  var Flyingkrai;

  Flyingkrai = window.Flyingkrai || {};
  Flyingkrai.FeaturedSlideshow = (function() {
    var bindEvents, changeActive, setFirst, __active, __activeClass, __container, __containerElement, __imageItens, __images, __thumbs;

    __container = '.superbanner';
    __containerElement = null;
    __images = 'ul.images';
    __imageItens = "" + __images + " li";
    __thumbs = 'ul.min';
    __activeClass = 'active';
    __active = "." + __activeClass;
    changeActive = function(element) {
      var actual, current, elementIndex;

      elementIndex = element.index();
      current = __containerElement.find(__images).find(__active);
      if (current.index() === elementIndex) {
        return false;
      }
      actual = $(__containerElement.find(__imageItens)[elementIndex]);
      return current.stop(true, true).fadeOut(500, function() {
        $(this).removeClass(__activeClass);
        return actual.addClass(__activeClass).fadeIn(1000);
      });
    };
    setFirst = function() {
      var first;

      first = __containerElement.find(__imageItens).filter(':first').addClass(__activeClass);
      return __containerElement.find(__imageItens).not(first).hide();
    };
    bindEvents = function() {
      return __containerElement.find(__thumbs).bind('click', function(event) {
        event.preventDefault();
        return changeActive($(this));
      });
    };
    return {
      init: function() {
        __containerElement = $(__container);
        if (__containerElement.length === 0) {
          return false;
        }
        setFirst();
        return bindEvents();
      }
    };
  })();
  return $(function() {
    return Flyingkrai.FeaturedSlideshow.init();
  });
})(jQuery, window);
