var gulp = require('gulp');
var concat = require('gulp-concat');
var concatCss = require('gulp-concat-css');
var notify = require("gulp-notify");

gulp.task('js', function() {
    return gulp.src([
            'bower_components/moment/moment.js',
            'bower_components/moment-timezone/builds/moment-timezone-with-data.min.js',
            'bower_components/jquery/dist/jquery.min.js',
            'bower_components/bootbox.js/bootbox.js',
            'bower_components/bootstrap/dist/js/bootstrap.min.js',
            'bower_components/bootstrap-multiselect/dist/js/bootstrap-multiselect.js',
            'bower_components/bootstrap-multiselect/dist/js/bootstrap-multiselect-collapsible-groups.js',
            'bower_components/bootstrap-submenu/dist/js/bootstrap-submenu.min.js',
            'bower_components/datatable/datatables.min.js',
            'bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
            'bower_components/file-saver/FileSaver.min.js',
            'bower_components/jquery-mousewheel/jquery.mousewheel.min.js',
            'bower_components/jquery-ui/jquery-ui.min.js',
            'bower_components/jqueryfiletree/dist/jQueryFileTree.min.js',
            'bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js',
            'bower_components/remarkable-bootstrap-notify/dist/bootstrap-notify.min.js',
        ])
        .pipe(concat('all.js'))
        .pipe(gulp.dest('./public/dest/'))
        .pipe(notify("JS Done!"));
});

gulp.task('css', function() {
    return gulp.src([
            'bower_components/animate.css/animate.min.css',
            'bower_components/bootstrap/dist/css/bootstrap.min.css',
            'bower_components/bootstrap-multiselect/dist/css/bootstrap-multiselect.css',
            'bower_components/bootstrap-submenu/dist/css/bootstrap-submenu.min.css',
            'bower_components/datatable/datatables.min.css',
            'bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css',
            'bower_components/font-awesome/css/font-awesome.min.css',
            'bower_components/jquery-ui/themes/smoothness/jquery-ui.min.css',
            'bower_components/jqueryfiletree/dist/jQueryFileTree.min.css',
            'bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css',
        ], {
            base: 'bower_components'
        })
        .pipe(concatCss("all.css"))
        .pipe(gulp.dest('./public/dest/'))
        .pipe(notify("CSS Done!"));
});

gulp.task('bootstrap', function() {
    return gulp.src('bower_components/bootstrap/dist/fonts/*.*', {
        base: 'bower_components'
    }).pipe(gulp.dest('./public/dest/'));
});
gulp.task('fontawesome', function() {
    return gulp.src('bower_components/font-awesome/fonts/*.*', {
        base: 'bower_components'
    }).pipe(gulp.dest('./public/dest/'));
});
gulp.task('jqueryui', function() {
    return gulp.src('bower_components/jquery-ui/themes/smoothness/images/*.*', {
        base: 'bower_components'
    }).pipe(gulp.dest('./public/dest/'));
});
gulp.task('jqueryfiletree', function() {
    return gulp.src('bower_components/jqueryfiletree/dist/images/*.*', {
        base: 'bower_components'
    }).pipe(gulp.dest('./public/dest/'));
});
gulp.task('malihu-custom-scrollbar-plugin', function() {
    return gulp.src('bower_components/malihu-custom-scrollbar-plugin/*.png', {
        base: 'bower_components'
    }).pipe(gulp.dest('./public/dest/'));
});

gulp.task('file', ['bootstrap', 'fontawesome', 'jqueryui', 'jqueryfiletree', 'malihu-custom-scrollbar-plugin'], function() {
    gulp.src('').pipe(notify("FILE Done!"));
});
