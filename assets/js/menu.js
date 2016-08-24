(function(){
  define('menu', ['jquery', 'velocity'], function($){
    function Menu(cm){
      var self = this;
      var lastOffsetBeforeAnimation;

      var DIRECTIONS = {
        DOWN: +1,
        UP: -1
      };

      var tempWrapper = $('<div>').css('display', 'none').attr('id', 'temp-wrapper');
      $('body').append(tempWrapper);

      var spinnerModal = $('<div id="loading-modal">'+
        '<div class="spinner-wrapper">'+
          '<div class="sk-folding-cube">'+
            '<div class="sk-cube1 sk-cube"></div>'+
            '<div class="sk-cube2 sk-cube"></div>'+
            '<div class="sk-cube4 sk-cube"></div>'+
            '<div class="sk-cube3 sk-cube"></div>'+
          '</div>'+
        '</div>'+
      '</section>');

      var activeSection;
      var oldSection;
      var oldSectionIdx;
      var activeSectionIdx;
      var sectionsRendered;

      var defaultConfig = {
        wrapperSel: '#content',
        mobileMenuBtnSel: '#mobile-menu-btn',
        sectionList: defaultGetSectionList(),
        notFoundTemplateUrl: 'http://localhost/parquecapibaribe/wp-content/themes/parquecapibaribe/sections' +'/'+ '404.php',
        speed: 500
      };

      self.init = init;
      self.destroy = destroy;
      self.getActiveSection = getActiveSection;
      self.renderSection = renderSection;

      function getActiveSection(){
        return activeSection;
      }

      function renderSection(hashStr, states, callback){
        oldSection = activeSection;
        activeSection = !!hashStr ? hashStr : (!!self.config.initialSection ? self.config.initialSection : self.config.sectionList[0]);
        oldSectionIdx = getSectionIndex(oldSection);
        activeSectionIdx = getSectionIndex(activeSection);
        sectionsRendered = 0;

        $('#temp-wrapper').html($('#content').html());

        lastOffsetBeforeAnimation = Math.max(0, $(window).scrollTop() - ($('#content #'+oldSection).offset() ? $('#content #'+oldSection).offset().top : 0));

        $('body').removeClass('mobile-menu-opened');
        if(!oldSection){
          cm.get(states[activeSection].templateUrl)
            .done(function(template){
              $(self.config.wrapperSel).html($(template).addClass('active'));
              $('#loading-modal').remove();
              callback();
            });
        } else {
          $('body').append(spinnerModal.clone());
          if(activeSectionIdx < 0){
            render404Template(callback);
          } else {
            if(oldSectionIdx < activeSectionIdx){
              goToActiveSection(states, DIRECTIONS.DOWN, callback);
            } else if(oldSectionIdx > activeSectionIdx) {
              goToActiveSection(states, DIRECTIONS.UP, callback);
            } else {
              $('#loading-modal').remove();
            }
          }
        }
      }

      function init(config){
        self.config = $.extend(true, defaultConfig, config);
        $('body').append(spinnerModal.clone().css({
              width: '100vw',
              height: '100vh',
              top: 0,
              left: 0,
              margin: 0,
              background: 'white'
          }));
        
        $(self.config.mobileMenuBtnSel).click(function(e){
          e.preventDefault();
          e.stopPropagation();
          $('body').toggleClass('mobile-menu-opened');
        });

        $('header').click(function(e){
          $('body').removeClass('mobile-menu-opened');
        });

        $(document).on('click', 'section', function(e){
          $('body').removeClass('mobile-menu-opened');
        });

        $('footer').click(function(e){
          $('body').removeClass('mobile-menu-opened');
        });

        $(window).on('scroll', relocateMenu);
        relocateMenu();
      }
      

      function getSectionIndex(sectionName){
        return self.config.sectionList.indexOf(sectionName);
      }

      function render404Template (){
        $(self.config.wrapperSel).html('<section id="404">'+
          '<h1 class="text-center"> ERRO: Página não encontrada!</h1>'+
        '</section>');
        $('#loading-modal').remove();
        callback();
      }

      function goToActiveSection(states, animationDirection, callback){
        for(var i = oldSectionIdx + animationDirection; 
          animationDirection === 1 ? i <= activeSectionIdx : i >= activeSectionIdx;
          i += animationDirection){

          renderSectionTemplate(states, i, animationDirection, callback);
        }
      }

      function putSectionInTheRightPlace(sectionEl, idx){
        if(idx != 0){
          var foundSectionBefore = false;
          for(var k = idx - 1; k >= 0 && !foundSectionBefore; k--){
            if($('#temp-wrapper').find('#'+self.config.sectionList[k]).length > 0){
              foundSectionBefore = true;
              $('#temp-wrapper').find('#'+self.config.sectionList[k]).after(sectionEl.clone());
            }
          }
          if(!foundSectionBefore){
            $('#temp-wrapper').prepend(sectionEl.clone());
          }
        } else {
          $('#temp-wrapper').prepend(sectionEl.clone());
        }
      }
      
      function removeInactiveSections(animationDirection){
        for(var j = activeSectionIdx - animationDirection; 
          animationDirection === -1 ? j <= oldSectionIdx : j >= oldSectionIdx; 
          j -= animationDirection){

          $(self.config.wrapperSel).find('#' + self.config.sectionList[j]).remove();
          $("html, body").scrollTop( $('#content #'+activeSection).offset().top );

        }
      }

      function renderSectionTemplate(states, idx, animationDirection, callback){
        cm.get(states[self.config.sectionList[idx]].templateUrl)
          .done(function(template){
            var sectionEl = $(template);

            putSectionInTheRightPlace(sectionEl, idx);
            if(animationDirection === -1){
              $("html, body").scrollTop( $('#content #'+oldSection).offset().top + lastOffsetBeforeAnimation );
            }
            sectionsRendered++;

            if(sectionsRendered === Math.abs(oldSectionIdx - activeSectionIdx)){
              $('#loading-modal').remove();
              $(self.config.wrapperSel).html($('#temp-wrapper').html());

              if(!!oldSection)
                $("html, body").scrollTop( $('#content #'+oldSection).offset().top + lastOffsetBeforeAnimation );

              $('#temp-wrapper').html('');

              $('#'+activeSection).addClass('active');
              $("html")
                .velocity('stop')
                .velocity('scroll', {
                  duration: self.config.speed, 
                  offset: $('#content #'+activeSection).offset().top, 
                  complete: function(){
                    removeInactiveSections(animationDirection);
                    callback();
                  }
                });
            }
          });
      }

      function relocateMenu() {
        var window_top = $(window).scrollTop();
        var div_top = $('header').outerHeight(true);
        if (window_top > div_top) {
          $('#menu').addClass('fixed');
        } else {
          $('#menu').removeClass('fixed');
        }
      }

      function destroy(){
        $(window).off('scroll');
        $(self.config.mobileMenuBtnSel).off('click');
        $('header, section, footer').off('click');
      }

      function defaultGetSectionList(){
        var sections = [];
        for(var idx in $('#menu li a').toArray()){
          sections.push($($('#menu li a')[idx]).attr('href').substr(1));
        }
        return sections;
      }
    }

    return Menu;
  });
})();