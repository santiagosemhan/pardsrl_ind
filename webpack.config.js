// webpack.config.js
var Encore = require('@symfony/webpack-encore');
const CopyWebpackPlugin = require('copy-webpack-plugin');

Encore
    // the project directory where all compiled assets will be stored
    .setOutputPath('web/build/')

    // the public path used by the web server to access the previous directory
    .setPublicPath(Encore.isProduction() ? '/build' :'/pardsrl_v2/web/build')

    // will create web/build/app.js and web/build/app.css
    .addEntry('app', './assets/js/app.js')
    .addEntry('global', './assets/js/global.js')
    .addEntry('highcharts', './assets/js/highcharts.js')
    .addEntry('login', './assets/js/login.js')
    .addEntry('reporte', './assets/js/reporte.js')

    // allow legacy applications to use $/jQuery as a global variable
    .autoProvidejQuery()

    // enable source maps during development
    .enableSourceMaps(!Encore.isProduction())

    // empty the outputPath dir before each build
    .cleanupOutputBeforeBuild()

    // show OS notifications when builds finish/fail
    .enableBuildNotifications()

    // add CopyWebpackPlugin
    .addPlugin(new CopyWebpackPlugin([
        // copies to {output}/static
        { from: './node_modules/admin-lte/dist/img', to: 'static/img' }
    ]))

    // create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning()

    // allow sass/scss files to be processed
    // .enableSassLoader()
;

// export the final configuration
module.exports = Encore.getWebpackConfig();
