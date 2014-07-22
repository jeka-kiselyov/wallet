// Karma configuration
// Generated on Wed Jul 16 2014 00:53:07 GMT+0300 (EEST)

module.exports = function(config) {
  config.set({

    // base path that will be used to resolve all patterns (eg. files, exclude)
    basePath: '',


    // frameworks to use
    // available frameworks: https://npmjs.org/browse/keyword/karma-adapter
    frameworks: ['jasmine-ajax', 'jasmine', 'jquery-1.11.0'],
    // https://github.com/scf2k/karma-jquery
    // https://www.npmjs.org/package/karma-jasmine
    // https://www.npmjs.org/package/karma-jasmine-ajax


    // list of files / patterns to load in the browser
    files: [
      'app/public/vendors/backbonejs/underscore-min.js',
      'app/public/vendors/backbonejs/backbone-min.js',
      'app/public/scripts/app.js',
      'app/public/scripts/app/models/*.js',
      'app/public/scripts/app/collections/*.js',
      'app/public/scripts/app/views/*.js',
      'app/public/scripts/app/*.js',
      'app/public/scripts/setup.js',
      'app/tests/js/*.js'
    ],


    // list of files to exclude
    exclude: [
    ],


    // preprocess matching files before serving them to the browser
    // available preprocessors: https://npmjs.org/browse/keyword/karma-preprocessor
    preprocessors: {
    },


    // test results reporter to use
    // possible values: 'dots', 'progress'
    // available reporters: https://npmjs.org/browse/keyword/karma-reporter
    reporters: ['progress'],


    // web server port
    port: 9876,


    // enable / disable colors in the output (reporters and logs)
    colors: true,


    // level of logging
    // possible values: config.LOG_DISABLE || config.LOG_ERROR || config.LOG_WARN || config.LOG_INFO || config.LOG_DEBUG
    logLevel: config.LOG_INFO,


    // enable / disable watching file and executing tests whenever any file changes
    autoWatch: true,


    // start these browsers
    // available browser launchers: https://npmjs.org/browse/keyword/karma-launcher
    browsers: ['PhantomJS'],


    // Continuous Integration mode
    // if true, Karma captures browsers, runs the tests and exits
    singleRun: true
  });
};
