module.exports = {

    webDir: 'web',
    srcDir: 'src',
    backendDir: 'backend',
    buildDir: 'build',
    packageDir: 'deploy',
    webFiles: {
        php: ['**/*.php'],
        html: ['*.html', 'snapshots/**'],
        js: ['assets/js/*.js'],
        apache: ['.htaccess'],
        images: ['assets/img/**/*'],
        seo: ['robots.txt']
    },

    api: {
        srcDir: '<%= srcDir %>/api',
        appFiles: {
            js: ['<%= api.srcDir %>/**/*.js']
        }
    },

    helper: {
        srcDir: '<%= srcDir %>/helper',
        appFiles: {
            js: ['<%= helper.srcDir %>/**/*.js'],
            tpl: ['<%= helper.srcDir %>/**/*.tpl.html']
        },
        tpl: {
            jsFile: '<%= buildDir %>/tpl/helper/templates.js',
            moduleName: 'helper-templates'
        }
    },

    front: {
        srcDir: '<%= srcDir %>/front',

        appFiles: {
            js: ['<%= front.srcDir %>/**/*.js'],
            tpl: ['<%= front.srcDir %>/**/*.tpl.html'],
            less: ['<%= front.srcDir %>/**/*.less']
        },
        tpl: {
            jsFile: '<%= buildDir %>/tpl/front/templates.js',
            moduleName: 'front-templates'
        }
    },

    admin: {
        srcDir: '<%= srcDir %>/admin',

        appFiles: {
            js: ['<%= admin.srcDir %>/**/*.js'],
            tpl: ['<%= admin.srcDir %>/**/*.tpl.html'],
            less: ['<%= admin.srcDir %>/**/*.less']
        },
        tpl: {
            jsFile: '<%= buildDir %>/tpl/admin/templates.js',
            moduleName: 'admin-templates'
        }
    },

    vendor: {
        files: {
            js: [
                'jquery/dist/jquery.min.js',
                'bootstrap/dist/js/bootstrap.min.js',
                'responsive-bootstrap-toolkit/js/bootstrap-toolkit.min.js',
                'angular/angular.min.js',
                'angular-route/angular-route.min.js',
                'angular-resource/angular-resource.min.js',
                'angular-local-storage/angular-local-storage.min.js',
                'flying-focus/standalone/flying-focus.js',
                'momentjs/min/moment.min.js',
                'momentjs/min/moment-with-langs.min.js',
                'momentjs/lang/ru.js',
                'html5shiv/dist/html5shiv.js',
                'respond/dest/respond.min.js',
                'imagelightbox/dist/imagelightbox.min.js'
            ],
            css: [
                'bootstrap/dist/css/bootstrap.min.css',
                'imagelightbox/dist/imagelightbox.min.css',
                'imagelightbox/img/next_arrow.png',
                'imagelightbox/img/prev_arrow.png'
            ],
            map: [
                'jquery/dist/jquery.min.map',
                'angular/angular.min.js.map',
                'angular-route/angular-route.min.js.map',
                'angular-resource/angular-resource.min.js.map'
            ],
            fonts: [
                'bootstrap/dist/fonts/glyphicons-halflings-regular.*'
            ]
        }
    },

    angular: {
        locale: 'ru-ru',
        core: 'angular/angular.min.js',
        localeFile: '<%= webDir %>/assets/js/angular-locale_<%= angular.locale %>.js'
    }
};