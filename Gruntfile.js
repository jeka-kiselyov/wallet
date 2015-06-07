module.exports = function(grunt) {

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    bower: {
      install: {
        options: {
          install: true,
          copy: false
        }
      }
    },
    watch: {
      scripts: {
        files: ['app/public/scripts/**/*.js','app/public/scripts/*.js'],
        tasks: ['bump', 'version', 'exec:minifyjs'],
        options: {
          spawn: false,
          livereload: true,
        }
      },
      styles: {
        files: ['app/public/css/*.css','app/public/css/**/*.css','app/public/css/**/*.less','app/public/vendors/**/*.less'],
        tasks: ['bump', 'version', 'exec:minifycss'],
        options: {
          spawn: false,
          livereload: true,
        }
      }, 
    },
    version: {
      options: {
      },
      myplugin: {
        options: {
          prefix: 'return [\'"]'
        },
        src: ['settings/version.php']
      }
    },
    bump: {
      options: {
        files: ['package.json'],
        commit: false,
        push: false
      }
    },
    exec: {
      test: 'php cli/tools/test.php',
      updateschema: 'php cli/tools/update_schema.php --force',
      minifycss: 'php cli/tools/compress_css_files.php --debug',
      minifyjs: 'php cli/tools/compress_js_files.php --debug',
      createfirstuser: 'php cli/tools/create_first_user.php'
    },
    availabletasks: {
      tasks: {}
    }
  });

  grunt.loadNpmTasks('grunt-bower-task');
  grunt.loadNpmTasks('grunt-composer');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-version');
  grunt.loadNpmTasks('grunt-bump');
  grunt.loadNpmTasks('grunt-exec');
  grunt.loadNpmTasks('grunt-available-tasks');

  // Default task(s).
  grunt.registerTask('default', [ 'availabletasks' ]);
  grunt.registerTask('install', [ 'exec:test', 
                                  'bump', 
                                  'version', 
                                  'composer:install', 
                                  'bower', 
                                  'exec:updateschema',
                                  'exec:createfirstuser',
                                  'exec:minifycss', 
                                  'exec:minifyjs'
                                ]);
  grunt.registerTask('pull', [ 'exec:test', 
                                  'bump', 
                                  'version', 
                                  'composer:install', 
                                  'bower', 
                                  'exec:updateschema',
                                  'exec:minifycss', 
                                  'exec:minifyjs'
                                ]);

};