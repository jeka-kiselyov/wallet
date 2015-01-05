module.exports = function(grunt) {

  // Project configuration.
  // A very basic default task.
  grunt.registerTask('default', 'Log some stuff.', function() {
    grunt.log.write('Logging some stuff...').ok();
  });

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    concat: {
      jsdist: {
        src: [  'app/public/vendors/jquery/jquery.js',
                'app/public/vendors/bootstrap/dist/js/bootstrap.js',
                'app/public/vendors/underscore/underscore.js',
                'app/public/vendors/backbone/backbone.js',
                'app/public/vendors/backbone.paginator/lib/backbone.paginator.js',
                'app/public/vendors/bootstrap-clickonmouseover/bootstrap.clickonmouseover.js',
                'app/public/vendors/magnific-popup/dist/jquery.magnific-popup.js',
                'app/public/vendors/chartist/dist/chartist.js',
                'app/public/vendors/jsmart/jsmart.js',
                'app/public/scripts/functions.js',
                'app/public/scripts/app.js',
                'app/public/scripts/app/view_stack.js',
                'app/public/scripts/app/settings.js',
                'app/public/scripts/app/local_storage.js',
                'app/public/scripts/app/template_manager.js',
                'app/public/scripts/app/i18n.js',
                'app/public/scripts/app/settings.js',
                'app/public/scripts/app/abstract/*.js',
                'app/public/scripts/app/models/*.js',
                'app/public/scripts/app/collections/*.js',
                'app/public/scripts/app/views/dialogs/*.js',
                'app/public/scripts/app/views/widgets/*.js',
                'app/public/scripts/app/views/parts/*.js',
                'app/public/scripts/app/views/pages/*.js',
                'app/public/scripts/app/views/charts/*.js',
                'app/public/scripts/app/views/*.js',
                'app/public/scripts/app/router.js',
                'app/public/scripts/setup.js',
        ],
        dest: 'app/public/scripts/dist/app.js',
        nonull: true,
        separator: ';',
        stripBanners: true,
      }
    },
    uglify: {
      options: {
        banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
      },
      build: {
        src: 'app/public/scripts/dist/app.js',
        dest: 'app/public/scripts/dist/app.min.js'
      }
    },
    cssmin: {
      options: {
        keepSpecialComments: 0,
        noAdvanced: true
      },
      target: {
        files: {
          'app/public/css/dist/app.min.css': [
            'app/public/vendors/magnific-popup/dist/magnific-popup.css', 
            'app/public/vendors/chartist/dist/chartist.min.css', 
            'app/public/vendors/bootstrap/dist/css/bootstrap.css', 
            'app/public/css/parts/*.css'
          ]
        }
      }
    },
    copy: {
      main: {
        files: [
          // includes files within path
          {expand: true, flatten: true, src: ['app/public/vendors/bootstrap/fonts/*'], dest: 'app/public/css/fonts/', filter: 'isFile'}
        ]
      }
    },
    bower: {
      install: {
        options: {
          install: true,
          copy: false
        }
       //just run 'grunt bower:install' and you'll see files from your Bower packages in lib directory
      }
    }
  });

  // Load the plugin that provides the "uglify" task.
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-bower-task');

  // Default task(s).
  grunt.registerTask('default', ['bower','concat','uglify','cssmin','copy']);

};