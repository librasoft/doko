var gulp = require('gulp'),
	gutil = require('gulp-util'),
    plumber = require('gulp-plumber'),
	notify = require('gulp-notify'),
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
	tinypng = require('gulp-tinypng-compress'),
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
			.pipe(plumber({errorHandler: notify.onError('Error: <%= error.message %>')}))
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
                title: theme + ' CSS'
            }))
            .pipe(minifyCSS({
				keepSpecialComments: 0,
				processImport: false
            }))
            .pipe(rename(function (path) {
                path.basename = path.basename.replace('.full', '.min');
            }))
            .pipe(gulp.dest('../webroot/theme/' + theme + '/css'))
            .pipe(size({
                showFiles: true,
                title: theme + ' CSS'
            }))
            .pipe(size({
                showFiles: true,
                gzip: true,
                title: theme + ' CSS'
			}))
			.pipe(notify({
				'title': 'CSS task completed',
				'subtitle': theme,
				'message': 'Last file processed: <%= file.relative %>',
				'onLast': true
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
			.pipe(plumber({errorHandler: notify.onError('Error: <%= error.message %>')}))
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
                title: theme + ' JS'
            }))
            .pipe(uglify())
            .pipe(rename(function (path) {
                path.basename = path.basename.replace('.full', '.min');
            }))
            .pipe(gulp.dest('../webroot/theme/' + theme + '/js'))
            .pipe(size({
                showFiles: true,
                title: theme + ' JS'
            }))
            .pipe(size({
                showFiles: true,
                gzip: true,
                title: theme + ' JS'
			}))
			.pipe(notify({
				'title': 'JS task completed',
				'subtitle': theme,
				'message': 'Last file processed: <%= file.relative %>',
				'onLast': true
            }));
    });

    return merge(tasks);
};
var tinypng_task = function () {
	// scan doko settings to find deploy settings
	var fileContent = fs.readFileSync('../config/settings/doko.php', 'utf8'),
		apikey = /'tinypng'\s*=>\s*'([^']*)'/gmi.exec(fileContent);

	if (!apikey) {
		return;
	}

	// we need to run one task for each theme
	// @see https://github.com/gulpjs/gulp/blob/master/docs/recipes/running-task-steps-per-folder.md
	var tasks = themes.map(function (theme) {
		return gulp.src([
				'../themes/' + theme + '/webroot/img/**/*.{jpg,jpeg,png}'
			])
			.pipe(plumber({errorHandler: notify.onError('Error: <%= error.message %>')}))
			.pipe(tinypng({
				key: apikey[1],
				sigFile: '../tmp/.tinypng-sigs',
				sameDest: true,
				summarize: true,
				log: true
			}))
			.pipe(gulp.dest('../themes/' + theme + '/webroot/img'));
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
			.pipe(plumber({errorHandler: notify.onError('Error: <%= error.message %>')}))
            .pipe(changed('../webroot/theme/' + theme + '/img'))
            .pipe(imagemin({
                interlaced: true,
                pngquant: true,
                progressive: true
            }))
            .pipe(gulp.dest('../webroot/theme/' + theme + '/img'))
            .pipe(size({
                showFiles: true,
                title: theme + ' IMG'
            }))
            .pipe(size({
                showFiles: true,
                gzip: true,
                title: theme + ' IMG'
			}))
			.pipe(notify({
				'title': 'Images task completed',
				'subtitle': theme,
				'message': 'Last file processed: <%= file.relative %>',
				'onLast': true
            }));
    });

    return merge(tasks);
};
var other_task = function () {
    // we need to run one task for each theme
    // @see https://github.com/gulpjs/gulp/blob/master/docs/recipes/running-task-steps-per-folder.md
    var tasks = themes.map(function (theme) {
        return gulp.src([
                '../themes/' + theme + '/webroot/**/*',
                '!../themes/' + theme + '/webroot/less/**/*',
                '!../themes/' + theme + '/webroot/js/**/*',
                '!../themes/' + theme + '/webroot/img/**/*',
            ])
			.pipe(plumber({errorHandler: notify.onError('Error: <%= error.message %>')}))
            .pipe(changed('../webroot/theme/' + theme + '/'))
            .pipe(gulp.dest('../webroot/theme/' + theme + '/'))
            .pipe(size({
                showFiles: true,
                title: theme + ' OTHER'
            }))
            .pipe(size({
                showFiles: true,
                gzip: true,
                title: theme + ' OTHER'
			}))
			.pipe(notify({
				'title': 'Other files task completed',
				'subtitle': theme,
				'message': 'Last file processed: <%= file.relative %>',
				'onLast': true
            }));
    });

    return merge(tasks);
};
var build_import_leaves = function (leaves, globPath, regexp) {
    var files = glob.sync(globPath),
        tree = {};

    files.map(function (file) {
        var filePath = path.resolve(__dirname, file),
            fileContent = fs.readFileSync(file, 'utf8'),
            matches = regexp.exec(fileContent),
            node,
            fileStat = fs.statSync(filePath),
            nodeStat,
            mtime;

        while (matches !== null) {
            node = path.resolve(path.dirname(file), matches[1]);
            tree = resolve_leaves(tree, [filePath], node);
			try {
                nodeStat = fs.statSync(node);
                mtime = fileStat.mtime > nodeStat.mtime ? fileStat.mtime : nodeStat.mtime;

                for (var i in tree[node]) {
                    if (leaves[tree[node][i]]) {
                        if (leaves[tree[node][i]] < mtime) {
                            leaves[tree[node][i]] = mtime;
                        }
                    } else {
                        leaves[tree[node][i]] = mtime;
                    }
                }
			} catch (e) {
				console.log(node + ' does not exists.');
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

	var u = {};
    var stats;

    for (var i in t) {
		//skip repeated themes
		if (u.hasOwnProperty(t[i])) {
			continue;
		}
		u[t[i]] = 1;

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
gulp.task('tinypng', ['themes-init'], tinypng_task);
gulp.task('images', ['tinypng'], images_task);
gulp.task('other', ['themes-init'], other_task);

gulp.task('themes-css', ['themes-init'], css_task);
gulp.task('themes-js', ['themes-css'], js_task);
gulp.task('themes-tinypng', ['themes-js'], tinypng_task);
gulp.task('themes-images', ['themes-tinypng'], images_task);
gulp.task('themes-other', ['themes-images'], other_task);
gulp.task('themes', ['themes-other']);

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
            '../themes/' + theme + '/webroot/**/*',
            '!../themes/' + theme + '/webroot/less/**/*',
            '!../themes/' + theme + '/webroot/js/**/*',
            '!../themes/' + theme + '/webroot/img/**/*',
        ], ['other']);
    });
});

gulp.task('default', ['watch']);
