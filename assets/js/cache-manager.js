(function(){
    'use strict';

    define('cache-manager', ['jquery'], function($){

        function CacheManager(){

            var self = this;
            var storedTemplates = {};

            self.get = get;
            self.refreshTemplate = refreshTemplate;

            function refreshTemplate(url){
                var promise = $.get(url);
                promise.done(function(template){
                    storedTemplates[url] = template;
                });
            }

            function get(url){
                if(storedTemplates[url] === undefined){
                    var promise = $.get(url);
                    promise.done(function(template){
                        storedTemplates[url] = template;
                    });
                    return promise;
                } else {
                    return {
                        done: function(cb){
                            return cb(storedTemplates[url]);
                        }
                    }
                }
            }

        }

        return CacheManager;

    });
})();