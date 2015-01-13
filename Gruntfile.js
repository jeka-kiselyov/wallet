module.exports = function(grunt) {

  // Project configuration.
  // A very basic default task.
  grunt.registerTask('default', 'Log some stuff.', function() {
    grunt.log.write('Logging some stuff...').ok();
  });

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
        tasks: ['bump', 'version', 'exec'],
        options: {
          spawn: false,
        }
      },
      styles: {
        files: ['app/public/css/*.css','app/public/css/**/*.css'],
        tasks: ['bump', 'version', 'exec'],
        options: {
          spawn: false,
        }
      }, 
    },
    version: {
      options: {
      },
      myplugin: {
        options: {
          prefix: '\\$settings\\[[\'"]version[\'"]]\\s+=\\s+[\'"]'
        },
        src: ['settings/settings.php']
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
      minifycss: 'php cli/tools/compress_css_files.php',
      minifyjs: 'php cli/tools/compress_js_files.php'
    }
  });

  grunt.loadNpmTasks('grunt-bower-task');
  grunt.loadNpmTasks('grunt-composer');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-version');
  grunt.loadNpmTasks('grunt-bump');
  grunt.loadNpmTasks('grunt-exec');

  // Default task(s).
  grunt.registerTask('default', ['bump', 'version','composer:install', 'bower','exec']);

};