const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const postcss = require('gulp-postcss');
const jshint = require('gulp-jshint');
const uglify = require('gulp-uglify');
const rename = require('gulp-rename');
const concat = require('gulp-concat');
const notify = require('gulp-notify');
const foreach = require('gulp-flatmap');
const changed = require('gulp-changed');
// const browserSync = require('browser-sync').create();
const wpPot = require('gulp-wp-pot');
const cssnano = require('cssnano');
// const cmq = require('css-mqpacker');
const autoprefixer = require('autoprefixer');
const zip = require('gulp-zip');
const comments = require('postcss-discard-comments');
// const critical = require('critical');

var plugins = [
    autoprefixer,
    cssnano,
    // cmq,
    comments({
        removeAllButFirst: true
    })
]

var paths = {
    styles: {
        src: 'assets/scss/style.scss',
        dest: 'assets/css'
    },
    // criticalcss: {
    //     src: 'assets/scss/inline.scss',
    //     dest: 'assets/css'
    // },
    admincss: {
        src: 'assets/scss/admin.scss',
        dest: 'assets/css'
    },
    uamsalert: {
        src: 'assets/scss/uamsalert.scss',
        dest: 'assets/css'
    },
    scripts: {
        src: [
            // 'node_modules/jquery/dist/jquery.js', // Changed from jquery.slim.js for AJAX
            // 'node_modules/popper.js/dist/umd/popper.js',
            // 'node_modules/bootstrap/dist/js/bootstrap.js',
            'node_modules/bootstrap/dist/js/bootstrap.bundle.js', // Includes popper
            // 'node_modules/bootstrap/js/dist/alert.js',
            // 'node_modules/bootstrap/js/dist/button.js',
            // 'node_modules/bootstrap/js/dist/carousel.js',
            // 'node_modules/bootstrap/js/dist/dropdown.js',
            // 'node_modules/bootstrap/js/dist/modal.js',
            // 'node_modules/bootstrap/js/dist/scrollspy.js',
            // 'node_modules/bootstrap/js/dist/tab.js',
            // 'node_modules/bootstrap/js/dist/util.js',
            'node_modules/smartmenus/dist/jquery.smartmenus.js',
            'node_modules/smartmenus-bootstrap-4/jquery.smartmenus.bootstrap-4.js',
            'assets/js/source/overflowing-navbar.min.js',
            'assets/js/source/headertrays.js',
            'assets/js/source/app.js',
            // 'assets/js/source/all.js', // FontAwesome 
            // 'assets/js/source/v4-shims.js', // FontAwesome v4 Shims
        ],
        dest: 'assets/js'
    },
    // jquery: {
    //     src: [
    //         'node_modules/jquery/dist/jquery.min.js',
    //     ],
    //     dest: 'assets/js'
    // },
    fontawesome: {
        src: [
            'assets/js/source/all.js', // FontAwesome 
            'assets/js/source/v4-shims.js', // FontAwesome v4 Shims
        ],
        dest: 'assets/js'
    },
    languages: {
        src: '**/*.php',
        dest: 'languages/uams-2020.pot'
    },
    zip: {
        src: 'html/html_template/*',
        dest: 'html'
    },
    site: {
        url: 'http://uamshealthmu.local'
    }
}

function translation() {
    return gulp.src(paths.languages.src)
        .pipe(wpPot())
        .pipe(gulp.dest(paths.languages.dest))
}

function scriptsLint() {
    return gulp.src('assets/js/source/**/*','gulpfile.js')
        .pipe(jshint('.jshintrc'))
        .pipe(jshint.reporter('default'))
}

function style() {
    return gulp.src(paths.styles.src)
        .pipe(changed(paths.styles.dest))
        .pipe(sass().on('error', sass.logError))
        .pipe(concat('app.scss'))
        .pipe(postcss(plugins))
        .pipe(rename('app.css'))
        .pipe(gulp.dest(paths.styles.dest))
        // .pipe(browserSync.stream())
        .pipe(notify({ message: 'Styles task complete' }));
}

// function criticalstyle() {
//     return gulp.src(paths.criticalcss.src)
//         .pipe(changed(paths.criticalcss.dest))
//         .pipe(sass().on('error', sass.logError))
//         .pipe(concat('inline.scss'))
//         .pipe(postcss(plugins))
//         .pipe(rename('inline.css'))
//         .pipe(gulp.dest(paths.styles.dest))
//         // .pipe(browserSync.stream())
//         .pipe(notify({ message: 'Critical Styles task complete' }));
// }

function adminstyle() {
    return gulp.src(paths.admincss.src)
        .pipe(changed(paths.admincss.dest))
        .pipe(sass().on('error', sass.logError))
        .pipe(concat('admin.scss'))
        .pipe(postcss(plugins))
        .pipe(rename('admin.css'))
        .pipe(gulp.dest(paths.styles.dest))
        // .pipe(browserSync.stream())
        .pipe(notify({ message: 'Admin Styles task complete' }));
}

function uamsalert() {
    return gulp.src(paths.uamsalert.src)
        .pipe(changed(paths.uamsalert.dest))
        .pipe(sass().on('error', sass.logError))
        .pipe(concat('uamsalert.scss'))
        .pipe(postcss(plugins))
        .pipe(rename('uamsalert.css'))
        .pipe(gulp.dest(paths.styles.dest))
        // .pipe(browserSync.stream())
        .pipe(notify({ message: 'UAMS Alert Styles task complete' }));
}

async function fa() {
    return gulp.src(paths.fontawesome.src)
        .pipe(changed(paths.fontawesome.dest))
        .pipe(concat('fa.js'))
        .pipe(uglify())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(paths.fontawesome.dest))
        .pipe(notify({ message: 'FontAwesome task complete' }));
}

// async function jquery() {
//     return gulp.src(paths.jquery.src)
//     .pipe(gulp.dest(paths.jquery.dest))
//     .pipe(notify({ message: 'JQuery task complete' }));
// }

function js() {
    return gulp.src(paths.scripts.src)
        .pipe(changed(paths.scripts.dest))
        .pipe(concat('uams.js'))
        // .pipe(foreach(function(stream, file){
        //     return stream
                .pipe(uglify())
                .pipe(rename({suffix: '.min'}))
        // }))
        .pipe(gulp.dest(paths.scripts.dest))
        // .pipe(browserSync.stream({match: '**/*.js'}))
        .pipe(notify({ message: 'Scripts task complete' }));
}

function template() {
    return gulp.src(paths.zip.src)
        .pipe(zip('html_template.zip'))
        .pipe(gulp.dest(paths.zip.dest));
}

// function browserSyncServe(done) {
//     browserSync.init({
//         injectChanges: true,
//         proxy: paths.site.url
//     })
//     done();
// }

// function browserSyncReload(done) {
//     browserSync.reload();
//     done();
// }

function watch() {
    gulp.watch(['assets/scss/*.scss', 'assets/scss/**/*.scss'], style).on('change', gulp.parallel(style, uamsalert, adminstyle, js))
    gulp.watch(paths.scripts.src, gulp.series(scriptsLint, js))
    gulp.watch([
            '*.php',
            'lib/*',
            '**/**/*.php'
        ],
        notify({ message: 'Watching' })
        // gulp.series(browserSyncReload)
    )
}

gulp.task('translation', translation);

gulp.task('fa', fa);

gulp.task('zip', template);

gulp.task('default', gulp.parallel(style, uamsalert, adminstyle, js, watch));

/* 
var dimensionSettings = [{
    width: 1280,
    height: 720
  },
  {
    width: 300,
    height: 500
  }];

gulp.task('criticalcss', function(cb) {
    critical.generate({
        base: 'assets/css/',
        src: "http://uamshealthmu.local/",
        dest: "critical.css",
        dimensions: dimensionSettings,
        minify: true,
        ignore: ['@font-face']
    }, cb);
    console.log('Generated critical CSS');
});
*/