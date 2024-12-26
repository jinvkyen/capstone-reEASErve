const mix = require("laravel-mix");

mix.js("resources/js/app.js", "public/js")
    .postCss("resources/css/build.css", "public/css/app.css", [
        require("tailwindcss"),
    ])
    .version(); // Optional: for cache busting

