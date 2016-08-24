require.config({
    "baseUrl": window.baseUrl,
    "paths": {
        "jquery": "//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min",
        "bootstrap": "bootstrap/js/bootstrap.min.js",
        "menu": "assets/js/menu",
        "cache-manager": "assets/js/cache-manager",
        "router": "assets/js/router",
        "project-slider": "assets/js/project-slider",
        "app": "assets/js/app",
        "velocity": "assets/js/vendor/velocity.min"
    },
    "shim": {
        "bootstrap": {
            "deps": ["jquery"]
        },
        "velocity": {
            "deps": ["jquery"]
        }
    },
    "waitSeconds": 15
});