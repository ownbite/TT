module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    sass: {
      options: {
        includePaths: ['../Helsingborg/bower_components/foundation/scss']
      },
      dist: {
        options: {
          outputStyle: 'expanded'
        },
        files: {
          'css/app.css': 'scss/app.scss'
        }
      }
    },

    copy: {
      scripts: {
        expand: true,
        cwd: '../Helsingborg/bower_components/',
        src: '**/*.js',
        dest: 'js'
      },

      maps: {
        expand: true,
        cwd: '../Helsingborg/bower_components/',
        src: '**/*.map',
        dest: 'js'
      }
    },

    uglify: {
      dist: {
        files: {
          '../Helsingborg/js/modernizr/modernizr.min.js': ['../Helsingborg/js/modernizr/modernizr.js']
        }
      }
    },

    concat: {
      options: {
        separator: ';',
        outputStyle: 'expanded'
      },
      dist: {
        src: [
          '../Helsingborg/js/foundation/js/foundation.min.js',
          '../Helsingborg/js/foundation/js/foundation/foundation.orbit.js',
          '../Helsingborg/js/custom/*.js'
        ],

        dest: 'js/app.js'
      }

    },

    watch: {
      grunt: { files: ['Gruntfile.js'] },

      sass: {
        files: 'scss/**/*.scss',
        tasks: ['sass']
      }
    }
  });

  grunt.loadNpmTasks('grunt-sass');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-uglify');

  grunt.registerTask('build', ['sass']);
  grunt.registerTask('default', ['copy', 'uglify', 'concat', 'watch']);

}
