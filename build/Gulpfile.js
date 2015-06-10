var gulp = require('gulp'),
    //GENERAL
    minimist = require('minimist'),
    fs = require('fs'),
    glob = require('glob'),
    path = require('path'),
    merge = require('merge-stream'),
    rename = require('gulp-rename'),
    size = require('gulp-size'),
    changed = require('gulp-changed'),
    del = require('del'),
    //JS
    jsinclude = require('gulp-include'),
    uglify = require('gulp-uglify'),
    //IMG
    imagemin = require('gulp-imagemin'),
    //LESS
    less = require('gulp-less'),
    autoprefixer = require('gulp-autoprefixer'),
    minifyCSS = require('gulp-minify-css');

var autoprefixer_browserlist = [
    '> 15%',
    'last 5 chrome versions',
    'last 5 firefox versions',
    'safari >= 7',
    'ie >= 9',
    'ios_saf >= 7',
    'android >= 4'
];
var less_leaves = {},
    js_leaves = {};
var initialized = false,
    themes = [];

var options = minimist(process.argv.slice(2));

var css_task = function () {
    // we need to run one task for each theme
    // @see https://github.com/gulpjs/gulp/blob/master/docs/recipes/running-task-steps-per-folder.md
    var tasks = themes.map(function (theme) {
        less_leaves = build_import_leaves(less_leaves, '../themes/' + theme + '/webroot/less/**/*.less', /^\s*@import\s+['"]?((?!url\()[^'"]+)['"]?;/gmi);

        return gulp.src('../themes/' + theme + '/webroot/less/*.less')
            .pipe(changed('../webroot/theme/' + theme + '/css', {
                extension: '.css',
                hasChanged: function (stream, callback, sourceFile, destPath) {
                    var leafMTime = less_leaves[sourceFile.path] || sourceFile.stat.mtime;

                    fs.stat(destPath.replace('.css', '.min.css'), function (err, targetStat) {
                        if (err || leafMTime > targetStat.mtime) {
                            stream.push(sourceFile);
                        }
                        callback();
                    });
                }
            }))
            .pipe(less())
            .pipe(autoprefixer({
                browsers: autoprefixer_browserlist,
                cascade: true,
                remove: true
            }))
            .pipe(rename({
                suffix: '.full'
            }))
            .pipe(gulp.dest('../webroot/theme/' + theme + '/css'))
            .pipe(size({
                showFiles: true,
                title: 'CSS'
            }))
            .pipe(minifyCSS({
                skip_import: true
            }))
            .pipe(rename(function (path) {
                path.basename = path.basename.replace('.full', '.min');
            }))
            .pipe(gulp.dest('../webroot/theme/' + theme + '/css'))
            .pipe(size({
                showFiles: true,
                title: 'CSS'
            }))
            .pipe(size({
                showFiles: true,
                gzip: true,
                title: 'CSS'
            }));
    });

    return merge(tasks);
};
var js_task = function () {
    // we need to run one task for each theme
    // @see https://github.com/gulpjs/gulp/blob/master/docs/recipes/running-task-steps-per-folder.md
    var tasks = themes.map(function (theme) {
        js_leaves = build_import_leaves(js_leaves, '../themes/' + theme + '/webroot/js/**/*.js', /^[\/\s#]*?=\s*?(?:(?:require|include)(?:_tree|_directory)?)\s+(.*$)/gmi);

        return gulp.src('../themes/' + theme + '/webroot/js/*.js')
            .pipe(changed('../webroot/theme/' + theme + '/js', {
                extension: '.js',
                hasChanged: function (stream, callback, sourceFile, destPath) {
                    var leafMTime = js_leaves[sourceFile.path] || sourceFile.stat.mtime;

                    fs.stat(destPath.replace('.js', '.min.js'), function (err, targetStat) {
                        if (err || leafMTime > targetStat.mtime) {
                            stream.push(sourceFile);
                        }
                        callback();
                    });
                }
            }))
            .pipe(jsinclude())
            .pipe(rename({
                suffix: '.full'
            }))
            .pipe(gulp.dest('../webroot/theme/' + theme + '/js'))
            .pipe(size({
                showFiles: true,
                title: 'JS'
            }))
            .pipe(uglify())
            .pipe(rename(function (path) {
                path.basename = path.basename.replace('.full', '.min');
            }))
            .pipe(gulp.dest('../webroot/theme/' + theme + '/js'))
            .pipe(size({
                showFiles: true,
                title: 'JS'
            }))
            .pipe(size({
                showFiles: true,
                gzip: true,
                title: 'JS'
            }));
    });

    return merge(tasks);
};
var images_task = function () {
    // we need to run one task for each theme
    // @see https://github.com/gulpjs/gulp/blob/master/docs/recipes/running-task-steps-per-folder.md
    var tasks = themes.map(function (theme) {
        return gulp.src([
                '../themes/' + theme + '/webroot/img/**/*'
            ])
            .pipe(changed('../webroot/theme/' + theme + '/img'))
            .pipe(imagemin({
                interlaced: true,
                pngquant: true,
                progressive: true
            }))
            .pipe(gulp.dest('../webroot/theme/' + theme + '/img'))
            .pipe(size({
                showFiles: true,
                title: 'IMG'
            }))
            .pipe(size({
                showFiles: true,
                gzip: true,
                title: 'IMG'
            }));
    });

    return merge(tasks);
};
var fonts_task = function () {
    // we need to run one task for each theme
    // @see https://github.com/gulpjs/gulp/blob/master/docs/recipes/running-task-steps-per-folder.md
    var tasks = themes.map(function (theme) {
        return gulp.src([
                '../themes/' + theme + '/webroot/font/**/*.{eot,svg,ttf,woff,woff2}'
            ])
            .pipe(changed('../webroot/theme/' + theme + '/font'))
            .pipe(gulp.dest('../webroot/theme/' + theme + '/font'))
            .pipe(size({
                showFiles: true,
                title: 'FONT'
            }))
            .pipe(size({
                showFiles: true,
                gzip: true,
                title: 'FONT'
            }));
    });

    return merge(tasks);
};
var build_import_leaves = function (leaves, globPath, regexp) {
    var files = glob.sync(globPath),
        tree = {};

    files.map(function (file) {
        var filepath = path.resolve(__dirname, file),
            fileContent = fs.readFileSync(file, 'utf8'),
            matches = regexp.exec(fileContent),
            node,
            nodeStat;

        while (matches !== null) {
            node = path.resolve(path.dirname(file), matches[1]);
            tree = resolve_leaves(tree, [filepath], node);
            nodeStat = fs.statSync(node);

            for (var i in tree[node]) {
                if (leaves[tree[node][i]]) {
                    if (leaves[tree[node][i]] < nodeStat.mtime) {
                        leaves[tree[node][i]] = nodeStat.mtime;
                    }
                } else {
                    leaves[tree[node][i]] = nodeStat.mtime;
                }
            }

            matches = regexp.exec(fileContent);
        }
    });

    return leaves;
};
var resolve_leaves = function (tree, leaves, node) {
    for (var i in leaves) {
        var leaf = leaves[i];

        if (tree[leaf]) {
            tree = resolve_leaves(tree, tree[leaf], node);
        }
        if (tree[node]) {
            tree[node].push(leaf);
        } else {
            tree[node] = [leaf];
        }
    }
    return tree;
};

gulp.task('themes-init', function () {
    if (initialized) {
        return;
    }

    var t = [];

    if (options['t']) {
        t = options['t'].split(',');
    } else {
        // scan doko settings to find current themes
        var fileContent = fs.readFileSync('../config/settings/doko.php', 'utf8'),
            regexp = /'theme'\s*=>\s*'([^']*)'/gmi,
            matches = regexp.exec(fileContent);

        while (matches !== null) {
            t.push(matches[1]);
            matches = regexp.exec(fileContent);
        }
    }

    var stats;

    for (var i in t) {
        try {
            stats = fs.lstatSync('../themes/' + t[i] + '/webroot/');

            if (stats.isDirectory()) {
                themes.push(t[i]);
            }
        } catch (e) {
            console.log(t[i] + ' theme does not exists.');
        }
    }
    initialized = true;
});

gulp.task('clean', ['themes-init'], function () {
    for (var i in themes) {
        del('../webroot/theme/' + themes[i], {
            force: true
        });
    }
});

gulp.task('css', ['themes-init'], css_task);
gulp.task('js', ['themes-init'], js_task);
gulp.task('images', ['themes-init'], images_task);
gulp.task('fonts', ['themes-init'], fonts_task);

gulp.task('themes-css', ['themes-init'], css_task);
gulp.task('themes-js', ['themes-css'], js_task);
gulp.task('themes-images', ['themes-js'], images_task);
gulp.task('themes-fonts', ['themes-images'], fonts_task);
gulp.task('themes', ['themes-fonts']);

gulp.task('watch', ['themes'], function () {
    themes.map(function (theme) {
        gulp.watch([
            '../themes/' + theme + '/webroot/less/**/*.less'
        ], ['css']);
        gulp.watch([
            '../themes/' + theme + '/webroot/js/**/*.js'
        ], ['js']);
        gulp.watch([
            '../themes/' + theme + '/webroot/img/**/*'
        ], ['images']);
        gulp.watch([
            '../themes/' + theme + '/webroot/font/**/*.{eot,svg,ttf,woff,woff2}'
        ], ['fonts']);
    });
});

gulp.task('default', ['watch']);
