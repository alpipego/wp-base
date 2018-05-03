module.exports = function (grunt) {
    var toml = require('toml');
    require('load-grunt-tasks')(grunt);
    require('time-grunt')(grunt);
    require('load-grunt-config')(grunt, {
        data: {
            config: toml.parse(grunt.file.read('config/env.local.toml')),
            path: {
                src: {
                    scss: 'assets/scss',
                    js: 'assets/js'
                },
                dest: {
                    css: 'web/assets/css',
                    js: 'web/assets/js'
                },
                tmp: 'assets/tmp'
            },
            files: {
                js: {
                    // '<%= path.dest.js %>/app.min.js': [
                    //
                    // ]
                },
                noJquery: {
                },
                copy: {}
            }
        }
    });
};
