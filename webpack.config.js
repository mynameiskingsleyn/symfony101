var Encore = require('@symfony/webpack-encore');

Encore
    // the project direcotry where compiled assets will be stored
    .setOutputPath('public/build')
    //set the public path used by the web server to access the previous direcotry
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())

    //define the assets of the project...
    .addEntry('js/app',['./node_modules/jquery/dist/jquery.slim.js',
      './node_modules/popper.js/dist/popper.min.js',
      './node_modules/bootstrap/dist/js/bootstrap.min.js',
      './node_modules/holderjs/holder.min.js'

    ])
    .addStyleEntry('css/app',[
        './node_modules/bootstrap/dist/css/bootstrap.min.css',
        './assets/css/app.css'

      ]
    )


module.exports = Encore.getWebpackConfig();
