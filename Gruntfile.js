module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    sass: {
      dist: {
        options: {
          style: 'expanded', // Output style. Can be nested, compact, compressed, expanded.
          lineNumbers:true
        },
        files: [{
                expand: true,
                cwd: 'sass',
                src: ['*.scss'],
                dest: 'css',
                ext: '.css'
              }]
      },
      build: {
        options: {
          style: 'compact'// ,
          // sourcemap:'none'
        },
        files: [{
                expand: true,
                cwd: 'sass',
                src: ['*.scss'],
                dest: 'css',
                ext: '.css'
              }]
      }
    },

    watch: {
      css: {
        files: ['sass/**/*.scss'],
        tasks: ['sass:dist']
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-uglify');

  // Default task(s).
  grunt.registerTask('default', ['sass:dist']);
  grunt.registerTask('w', 'watch');

  // Final build
  grunt.registerTask('build', ['sass:build']);

};