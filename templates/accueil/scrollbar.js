$(function() {

  $.fn.extend({
    slider: function(options) {

     var _$this = this,
       _$track = $('.track', this),
       _$thumb = $('.thumb', this),
       _$body = $('body'),
       _x = 0,
       trackWidth = $('.track', this).width(),
       thumbWidth = $('.thumb', this).width(),
       maxX = trackWidth - thumbWidth,
       _isDrag = false,
       _startDrag = -1,
          _hasTouchEvents = ('ontouchstart' in document.documentElement);

     _$track.mousedown(onPress);
     $('html').mouseup(onRelease);
     $('html').mousemove(onMove);

     if(_hasTouchEvents) {
       _$track[0].addEventListener('touchstart', onPress);
       _$track[0].addEventListener('touchend', onRelease);
       _$track[0].addEventListener('touchmove', onMove);
     }

     function onPress(e) {
       _isDrag = true;
       _startDrag = getMouseX(e);
       updateThumb(_startDrag);
       if(typeof options.onPress === 'function') {
         options.onPress(getPerc());
       }
     }

     function onRelease(e) {
       _isDrag = false;
       if(typeof options.onRelease === 'function') {
         options.onRelease(getPerc());
       }
     }

     function onMove(e) {
       if(_isDrag === true) {
         updateThumb(getMouseX(e));
         if(typeof options.onDrag === 'function') {
           options.onDrag(getPerc());
         }
       }
     }

     function getMouseX(e) {
       if(_hasTouchEvents) {
         return e.targetTouches[0].clientX - thumbWidth/2 - _$this.offset().left;
       }

       return e.clientX - thumbWidth/2 - _$this.offset().left;
     }

     function getPerc() {
       return _x/maxX;
     }

     function updateThumb(x) {
       _x = x;
       if(_x < 0) {
         _x = 0;
       }
       if(_x > maxX) {
         _x = maxX;
       }
       _$thumb.css('-webkit-transform', 'translateX(' + _x + 'px)');
     }

     return {
       goto: function(p) {
         updateThumb(maxX * p);
       }
     }
   }
 });

 var slider = $('.scrollbar').slider({
   onDrag: function(p) {
     console.log(p);
   },
   onPress: function(p) {
     console.log(p);
   },
   onRelease: function(p) {
     console.log(p);
   }
 });
 
 slider.goto(0.5); 
});

