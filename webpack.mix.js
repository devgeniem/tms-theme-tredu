const mix = require( 'laravel-mix' );
require( 'laravel-mix-svg-sprite' );
require( 'laravel-mix-eslint' );

// TODO: extract() shoud be fixed. Polyfills, aliases. Different setup for dev and prod builds

// eslint-disable-next-line no-undef
Config.svgSprite = {
    /*
     * @see https://github.com/kisenka/svg-sprite-loader#configuration
     */
    loaderOptions: {
        symbolId: 'icon-[name]',
    },
    /*
     * @see https://github.com/kisenka/svg-sprite-loader#configuration
     */
    // pluginOptions: {
    //     plainSprite: true,
    // },
};

mix.webpackConfig( {
    externals: { jquery: 'jQuery' },
} )
    .js( 'assets/scripts/theme-tredu.js', 'theme_tredu.js' )
    .autoload( {
        jquery: [ '$', 'window.jQuery' ],
    } )
    .eslint( {
        fix: true,
        extensions: [ 'js' ],
    } )
    //.extract() // this breaks all JS without errors
    .sass( 'assets/styles/theme-tredu.scss', 'theme_tredu.css' )
    .options( {
        processCssUrls: false,
        postCss: [
            require( 'css-mqpacker' )( {
                sort: true,
            } ),
            require( 'cssnano' )( {
                preset: [
                    'default',
                    {
                        discardComments: {
                            removeAll: true,
                        },
                    },
                ],
            } ),
        ],
    } )
    .svgSprite(
        'assets/icons', // The directory containing your SVG files
        'icons.svg' // The output path for the sprite
    )
    .version()
    .setPublicPath( 'assets/dist' );

