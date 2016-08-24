(function(){
    define('app', ['jquery', 'router', 'cache-manager',
     'menu', 'project-slider', 'velocity'], 
    function($, Router, CacheManager, Menu, ProjectSlider){
        function App(){
            var self = this;

            self.init = init;
            self.destroy = destroy;

            function init(){
                self.cm = new CacheManager();

                self.menu = new Menu(self.cm);
                self.menu.init();

                self.projectSlider = new ProjectSlider(self.cm);

                self.router = new Router(self.cm, self.menu);
                $('#menu a').click(function(e){
                    e.preventDefault();
                    $("html")
                        .velocity('stop')
                        .velocity('scroll', {
                            duration: 1000, 
                            offset: $('#content section').offset().top 
                        });
                    location.hash = $(this).attr('href')
                })
                $('#menu a').each(function(i, el){
                    var item = $(el);
                    var itemStateName = item.attr('href').substr(1);
                    self.router.addState(itemStateName, {
                        url: itemStateName,
                        templateUrl: item.attr('data-page-url'),
                        controller: function(stateName, args, states){
                            self.menu.renderSection(stateName, states, function(){
                                if(item.attr('data-menu-item-type') === 'category'){
                                    $('#content .action').each(function(i, el){
                                        $(el).attr('href', '#'+itemStateName+'/'+$(el).attr('data-action-slug'));
                                    });
                                }
                            });
                        }
                    });

                    if(item.attr('data-menu-item-type') === 'category'){
                        self.router.addState(itemStateName+'-ActionDetail', {
                            url: itemStateName+'/:actionId',
                            controller: function(stateName, args, states){
                                if($('#content').html() === ''){
                                    self.menu.renderSection(itemStateName, states, function(){
                                        $('#content .action').each(function(i, el){
                                            $(el).attr('href', '#'+itemStateName+'/'+$(el).attr('data-action-slug'));
                                        });
                                        self.projectSlider.init({
                                            slideClass: '.bdq-slide',
                                            templateUrl: $('#'+args.actionId).attr('data-action-link'),
                                            title: $('#'+args.actionId).attr('data-action-title')
                                        });
                                    });
                                } else {
                                    self.projectSlider.init({
                                        slideClass: '.bdq-slide',
                                        templateUrl: $('#'+args.actionId).attr('data-action-link'),
                                        title: $('#'+args.actionId).attr('data-action-title')
                                    });
                                }
                            }
                        });
                    }

                    if(i === 0){
                        self.router.setDefaultState(item.attr('href').substr(1));
                    }
                });
                
                

                self.router.init();
            }

            function destroy(){
                self.menu.destroy();
                self.router.destroy();
            }
        }


        return App;
    })
})();