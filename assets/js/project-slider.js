(function(){
    'use strict';

    define('project-slider', ['jquery'], function($){
        function ProjectSlider(cm){

            var self = this;

            var uid, 
                activeSlide = 0,
                slides = [],
                defaultConfig = {
                    previousBtnClass: '.previous-btn',
                    nextBtnClass: '.next-btn',
                    closeBtnClass: '.close-btn',
                    slideClass: '.slide'
                };

            self.init = init;
            self.destroy = destroy;
            self.goToSlide = goToSlide;
            self.next = next;
            self.previous = previous;

            function init(config){
                generateUid();
                self.config = $.extend(true, defaultConfig, config);

                $('html,body').addClass('no-scroll');

                initSlideTemplate();
                initBtnEvents();
                loadSlideContent();

                updateControlButtonsBehavior(0);
            }

            function initSlideTemplate(){
                var slider = $('<div>')
                    .addClass('slider')
                    .attr('id', uid.substr(1));


                var spinner = $('<div class="spinner-wrapper">'+
                  '<div class="sk-folding-cube">'+
                    '<div class="sk-cube1 sk-cube"></div>'+
                    '<div class="sk-cube2 sk-cube"></div>'+
                    '<div class="sk-cube4 sk-cube"></div>'+
                    '<div class="sk-cube3 sk-cube"></div>'+
                  '</div>'+
                '</div>');

                var title = $('<h1>')
                    .html(self.config.title)
                    .appendTo(slider);

                var slideWrapper = $('<div>')
                    .addClass('slide-wrapper')
                    .appendTo(slider);

                var closeBtn = $('<button>')
                    .addClass(self.config.closeBtnClass.substr(1))
                    .appendTo(slider)
                    .html('<i class="glyphicon glyphicon-remove"> </i>');

                var previousBtn = $('<button>')
                    .addClass(self.config.previousBtnClass.substr(1) + ' visible-md visible-lg')
                    .appendTo(slider)
                    .html('<i class="glyphicon glyphicon-chevron-left"> </i>');

                var nextBtn = $('<button>')
                    .addClass(self.config.nextBtnClass.substr(1) + ' visible-md visible-lg')
                    .appendTo(slider)
                    .html('<i class="glyphicon glyphicon-chevron-right"> </i>');

                $('body').append(slider);
            }

            function initBtnEvents(){
                $(self.config.previousBtnClass).click(function(e){
                    if(activeSlide > 0){
                        previous();
                    }
                });
                $(self.config.nextBtnClass).click(function(e){
                    if(activeSlide < getSlidesCount() - 1){
                        next();
                    }
                });
                $(self.config.closeBtnClass).click(function(e){
                    destroy();
                });
            }


            function getSlidesCount(){
                return $(uid).find(self.config.slideClass).length;
            }

            function generateUid(){
                uid = '#project-slide-' + 1;
                for(var i=2; $(uid).length !== 0; i++){
                    uid = '#project-slide-' + i;
                }
            }

            function next(){
                if(activeSlide < getSlidesCount() - 1){
                    goToSlide(activeSlide+1);
                }
            }


            function previous(){
                if(activeSlide > 0){
                    goToSlide(activeSlide-1);
                }
            }


            function goToSlide(slide){
                if(slide < getSlidesCount() && slide >= 0){
                    activeSlide = slide;
                    $(uid).find(self.config.slideClass).addClass('hidden-md hidden-lg');
                    $($(uid).find(self.config.slideClass)[slide]).removeClass('hidden-md hidden-lg');
                }

                updateControlButtonsBehavior(slide);
                
            }

            function updateControlButtonsBehavior(slide){
                if(slide === 0){
                    $(self.config.previousBtnClass).addClass('disabled');
                } else {
                    $(self.config.previousBtnClass).removeClass('disabled');
                }

                if(slide === getSlidesCount() - 1){
                    $(self.config.nextBtnClass).addClass('disabled');
                } else {
                    $(self.config.nextBtnClass).removeClass('disabled');
                }
            }

            function loadSlideContent(){
                cm.get(self.config.templateUrl).done(function(templateStr){
                    $(uid).find('.slide-wrapper').html(templateStr);
                    $(uid).find(self.config.slideClass).each(function(i, el){
                        if(i !== 0){
                            $(el).addClass('hidden-md hidden-lg');
                        }
                    });
                    $('.spinner-wrapper').remove();
                });
            }

            function destroy(){
                $(self.config.previousBtnClass).off('click');
                $(self.config.nextBtnClass).off('click');
                $(self.config.closeBtnClass).off('click');
                $(uid).remove();

                activeSlide = 0;

                $('html,body').removeClass('no-scroll');

                var urlParts = location.hash.split('/');
                urlParts.pop();
                location.hash = urlParts.length > 1 ? urlParts.join('/') : urlParts[0];
            }

        }

        return ProjectSlider;
    });

})();