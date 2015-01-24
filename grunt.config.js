module.exports = {

    webDir: 'web',
    srcDir: 'src',
    buildDir: 'build',
    webFiles: {
        php: ['**/*.php'],
        html: ['*.html'],
        js: ['assets/js/*.js'],
        apache: ['.htaccess'],
        images: ['assets/img/**/*']
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
            js: ['<%= helper.srcDir %>/**/*.js']
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
                'angular/angular.min.js',
                'angular-route/angular-route.min.js',
                'angular-resource/angular-resource.min.js',
                'angular-local-storage/angular-local-storage.min.js',
                'flying-focus/standalone/flying-focus.js',
                'momentjs/moment.js',
                'html5shiv/dist/html5shiv.js',
                'respond/dest/respond.min.js'
            ],
            css: [
                'bootstrap/dist/css/bootstrap.min.css'
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