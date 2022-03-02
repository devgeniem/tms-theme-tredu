let mix = require( 'laravel-mix' );

// mix.webpackConfig({
//     module: allModules
// });
// mix.js( 'assets/scripts/theme-tredu.js', 'theme_tredu.js' )
//     .autoload( {
//         jquery: [ '$', 'window.jQuery' ],
//     } )
    // .extract() // this breaks all JS without errors
mix.sass( 'assets/styles/theme-tredu.scss', 'theme_tredu.css' )
    .options( {
        processCssUrls: false,
    } )
    // .svgSprite(
    //     'src/icons', // The directory containing your SVG files
    //     'output/sprite.svg', // The output path for the sprite
    //     [loaderOptions], // Optional, see https://github.com/kisenka/svg-sprite-loader#configuration
    //     [pluginOptions] // Optional, see https://github.com/kisenka/svg-sprite-loader#configuration
    // );
    .version()
    .setPublicPath( 'assets/dist' );
