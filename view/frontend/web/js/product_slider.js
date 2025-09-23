define(['Mygento_Slider/js/vendor/splide.min'], function (Splide) {
  'use strict';

  return function (config, element) {
    new Splide(element, config).mount();
  };
});