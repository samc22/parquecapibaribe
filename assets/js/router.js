(function(){
    define('router', ['jquery', 'project-slider'], function($, ProjectSlide){

        function Router(cm, menu){

            var self = this;
            
            var slider = null;
            var states = {};
            var defaultState = null;
            var activeStateName = undefined;

            self.addState = addState;
            self.deleteState = deleteState;
            self.setDefaultState = setDefaultState;
            self.init = init;
            self.destroy = destroy;

            function setDefaultState(state){
                defaultState = state;                
            }

            function addState(stateName, state){

                console.assert(typeof(stateName) === 'string', 'State name must be a string');
                console.assert(typeof(state) === 'object', 'State must be an object');

                if(typeof(stateName) === 'string' && typeof(state) === 'object'){
                    console.assert(typeof(state.url) === 'string', 
                        'State url must be a string');
                                    
                    if(typeof(state.url) === 'string'){
                        states[stateName] = state;
                    }
                }
            }


            function route(e){
                var stateFound = false;
                var url = location.hash ? location.hash.substr(1) : states[defaultState].url;
                var urlParts = url.split('/');

                var oldStateName = activeStateName;

                for(var key in states){
                    var keyParts = states[key].url.split('/');
                    var keyMatch = true;
                    var args = {};
                    if(urlParts.length !== keyParts.length){
                        keyMatch = false;
                    }
                    for(var i = 0; keyMatch && i < Math.min(keyParts.length, urlParts.length); i++){
                        if(keyParts[i][0] === ':'){
                            args[keyParts[i].substr(1)] = urlParts[i];
                        } else if(urlParts[i] !== keyParts[i]){
                            keyMatch = false;
                        }
                    }
                    if(keyMatch){
                        stateFound = true;
                        states[key].controller(key, args, states, oldStateName);
                        activeStateName = key;
                    }
                }
            }


            function init(){
                $(window).on('hashchange', route);
                route();
            }

            function deleteState(stateName){
                delete states[stateName];
            }

            function destroy(){
                $(window).off('hashchange');
            }
        }

        return Router;

    });
})();