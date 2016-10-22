/**
 * Created with PhpStorm.
 * User: ZyManch
 * Date: 16.10.2016
 * Time: 15:38
 */
function MangaReader() {
    var self = this;
    this.$body = $('body');
    this.$container = $('.manga-chapter');
    this.$blocks = self.$container.find('.manga-image');
    this.maxScroll;
    this.dragStartedAtPixel;

    this.init = function() {
        self.maxScroll = self.$body.width()-self.$container.width();
        self.$container.css('left',self.maxScroll+'px');
        $(window).resize(self.resize);
        self.$container.bind('mousedown',self.startDrag);
        self.$container.bind('touchstart',self.startDrag);
        this.resize();
    };
    this.startDrag = function(e) {
        if (typeof e.originalEvent.touches != 'undefined') {
            e = e.originalEvent.touches[0];
        }
        self.dragStartedAtPixel = parseInt(self.$container.css('left'),10)- e.pageX;
        self.$body.bind('mousemove',self.dragging);
        self.$body.bind('touchmove',self.dragging);
        self.$body.bind('mouseup',self.finishDrag);
        self.$body.bind('touchend',self.finishDrag);
        e.stopPropagation();
        return false;
    };
    this.dragging = function (e) {
        if (typeof e.originalEvent.touches != 'undefined') {
            e = e.originalEvent.touches[0];
        }
        var newX = (self.dragStartedAtPixel+e.pageX);
        if (newX > 0) {
            newX = 0;
        } else if (newX < self.maxScroll) {
            newX = self.maxScroll
        }
        self.$container.css('left',newX+'px');
        e.stopPropagation();
        return false;
    };
    this.finishDrag = function(e) {
        if (typeof e.originalEvent.touches != 'undefined') {
            e = e.originalEvent.touches[0];
        }
        self.$body.unbind('mousemove',self.dragging);
        self.$body.unbind('touchmove',self.dragging);
        self.$body.unbind('mouseup',self.finishDrag);
        self.$body.unbind('touchend',self.finishDrag);
        e.stopPropagation();
        return false;
    };
    this.resize = function() {
        var containerHeight = self.$container.height(),
            scroll = parseInt(self.$container.css('left'),10)/self.maxScroll;
        self.$blocks.each(function() {
            var $this = $(this),
                $image = $this.find('img'),
                width = $this.data('width'),
                height = $this.data('height');
            if (containerHeight > height) {
                $image.css({
                    width: width+'px',
                    height: height+'px',
                    marginTop: Math.round((containerHeight-height)/2)+'px'
                });
            } else {
                $image.css({
                    width: Math.round(width*containerHeight/height)+'px',
                    height: containerHeight+'px',
                    marginTop: '0px'
                });
            }
        });
        self.maxScroll = self.$body.width()-self.$container.width();
        self.$container.css('left',Math.round(self.maxScroll*scroll)+'px');
    };
    this.init();
}