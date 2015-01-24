'use strict';

module.exports = function(grunt) {

    require('load-grunt-tasks')(grunt);

    var gruntConfig = require('./grunt.config.js');

    // Project configuration.
    var taskConfig = {
        pkg: grunt.file.readJSON('package.json'),

        clean: {
            build: {
                src: ['<%= buildDir %>']
            }
        },

        /**
         * Compile front and admin templates separately.
         */
        html2js: {
            admin: {
                options: {
                    base: '.'
                },
                src: ['<%= admin.appFiles.tpl %>'],
                dest: '<%= admin.tpl.jsFile %>',
                module: '<%= admin.tpl.moduleName %>'
            },
            front: {
                options: {
                    base: '.'
                },
                src: ['<%= front.appFiles.tpl %>'],
                dest: '<%= front.tpl.jsFile %>',
                module: '<%= front.tpl.moduleName %>'
            }
        },

        jshint: {
            options: {
                jshintrc: '.jshintrc',
                force: true, // Don't fail the task, just warn
                ignores: []
            },
            src: {
                files: {
                    src: [
                        '<%= api.appFiles.js %>',
                        '<%= front.appFiles.js %>',
                        '<%= admin.appFiles.js %>',
                        '<%= helper.appFiles.js %>'
                    ]
                }
            },
            gruntfile: {
                files: {
                    src: ['grunt.config.js', 'Gruntfile.js']
                }
            }
        },

        copy: {
            web: {
                files: [{
                    src: [
                        '<%= webFiles.html %>',
                        '<%= webFiles.js %>',
                        '<%= webFiles.php %>',
                        '<%= webFiles.apache %>'
                    ],
                    dest: '<%= buildDir %>',
                    cwd: '<%= webDir %>',
                    expand: true
                }]
            },
            img: {
                files: [{
                    src: ['<%= webFiles.images %>'],
                    dest: '<%= buildDir %>',
                    cwd: '<%= webDir %>',
                    expand: true
                }]
            },
            js: {
                files: [
                    {
                        src: [
                            '<%= api.appFiles.js %>',
                            '<%= admin.appFiles.js %>',
                            '<%= front.appFiles.js %>',
                            '<%= helper.appFiles.js %>'
                        ],
                        dest: '<%= buildDir %>',
                        cwd: '.',
                        expand: true

                    }
                ]
            },
            vendor: {
                files: [{
                    src: [
                        '<%= vendor.files.js %>',
                        '<%= vendor.files.map %>',
                        '<%= vendor.files.fonts %>',
                        '<%= vendor.files.css %>'
                    ],
                    dest: '<%= buildDir %>/vendor',
                    cwd: 'bower_components',
                    expand: true
                }]
            }
        },

        /**
         * Todo: fix, currently this isn't working.
         */
        concat: {
            angular: {
                files: {
                    src:  ["<%= angular.core %>", "<%= angular.localeFile %>"],
                    dest: "<%= buildDir %>/angular.min.js",
                    cwd: '<%= webDir %>/assets',
                    expand: true
                }
            }
        },

        ngAnnotate: {
            build: {
                files: [
                    {
                        src: [
                            '<%= api.appFiles.js %>',
                            '<%= front.appFiles.js %>',
                            '<%= admin.appFiles.js %>',
                            '<%= helper.appFiles.js %>'
                        ],
                        cwd: '<%= buildDir %>',
                        dest: '<%= buildDir %>',
                        expand: true
                    }
                ]
            }
        },

        less: {
            build: {
                options: {
                    paths: ["<%= srcDir %>"]
                },
                files: {
                    "<%= buildDir %>/assets/css/admin.css": "<%= webDir %>/assets/less/theatres.admin.less",
                    "<%= buildDir %>/assets/css/front.css": "<%= webDir %>/assets/less/theatres.front.less",
                    "<%= buildDir %>/assets/css/print.css": "<%= webDir %>/assets/less/print.less"
                }
            }
        },

        watch: {
            options: {
                livereload: true
            },

            gruntfile: {
                files: ['<%= jshint.gruntfile.files.src %>'],
                tasks: ['jshint:gruntfile'],
                options: {
                    livereload: false
                }
            },

            jssrc: {
                files: [
                    '<%= api.appFiles.js %>',
                    '<%= front.appFiles.js %>',
                    '<%= admin.appFiles.js %>',
                    '<%= helper.appFiles.js %>'
                ],
                tasks: ['jshint:src', 'copy:js', 'ngAnnotate']
            },

            tpls: {
                files: [
                    '<%= front.appFiles.tpl %>',
                    '<%= admin.appFiles.tpl %>'
                ],
                tasks: ['html2js']
            },
            webfiles: {
                files: [
                    '<%= webDir %>/<%= webFiles.html %>',
                    '<%= webDir %>/<%= webFiles.php %>',
                    '<%= webDir %>/<%= webFiles.apache %>'
                ],
                tasks: ['copy:web']
            },
            img: {
                files: ['<%= webFiles.images %>'],
                tasks: ['copy:img']
            }
        }

        // Todo: configure concat and less tasks.

//        uglify: {
//            options: {
//                mangle: false,
//                banner: '/*! <%= pkg.name %> <%= pkg.version %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
//            },
//            admin: {
//                expand: true,
//                cwd: 'web/src/admin',
//                src: '**/**/*.js',
//                dest: 'web/build/admin'
//            }
//        }
    };

    grunt.initConfig(grunt.util._.merge(taskConfig, gruntConfig));


    grunt.registerTask('build', [
        'clean',
        'html2js',
        'jshint',
        'copy:js',
        'copy:vendor',
        'ngAnnotate',
        // assets
        'less',
        'copy:img',
        'copy:web'
    ]);

    // Default task(s).
    grunt.registerTask('default', ['build']);

};