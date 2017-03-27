var gulp         = require('gulp'),
	fs           = require('fs'),
	path         = require('path'),
	gutil        = require('gulp-util'),
	merge        = require('merge-stream'),
	pkg          = require('./package.json'),

	autoprefixer = require('gulp-autoprefixer'),
	clean        = require('gulp-clean'),
	concat       = require('gulp-concat'),
	header       = require('gulp-header'),
	jshint       = require('gulp-jshint'),
	modernizr    = require('gulp-modernizr'),
	sass         = require('gulp-sass'),
	sourcemaps   = require('gulp-sourcemaps'),
	svgmin       = require('gulp-svgmin'),
	uglify       = require('gulp-uglify'),

	banner       = '/*! ${pkg.name} - v${pkg.version}${suffix} */\n',
	basedir      = pkg.assetsDir || '',
	isprod       = !!gutil.env.prod;

function getFolders(dir) {
	return fs.readdirSync(dir)
		.filter(function(file) {
			return fs.statSync(path.join(dir, file)).isDirectory();
	});
}

gulp.task('modernizr', function() {
	var opts = {
		modernizr: {
			options: [
				"setClasses",
				"addTest",
				"html5printshiv",
				"testProp",
				"fnBind"
			]
		},
		uglify: {
			mangle: false,
			preserveComments: 'license',
		}
	};

	gulp.src('src/js/**/*.js', { cwd: basedir })
		.pipe(modernizr(opts.modernizr))
		.pipe(uglify(opts.uglify))
		.pipe(gulp.dest(basedir))
});

gulp.task('js', ['modernizr'], function() {
	var src = basedir + '/src/js',
		opts = {
			banner: {
				pkg: pkg,
				suffix: !isprod ? '-dev' : ''
			},
			uglify: {
				compress: {
					drop_console: isprod
				}
			}
		};

	return merge(getFolders(src).map(function(dir) {
		return gulp.src(path.join(src, dir, '/**/*.js'))
			.pipe(!isprod ? sourcemaps.init() : gutil.noop())
			.pipe(concat(dir + '.js'))
			.pipe(isprod ? header(banner, opts.banner) : gutil.noop())
			.pipe(uglify(opts.uglify))
			.pipe(!isprod ? sourcemaps.write('') : gutil.noop())
			.pipe(gulp.dest(basedir));
	}));
});

gulp.task('css', function() {
	var src = basedir + '/src/scss',
		opts = {
			banner: {
				pkg: pkg,
				suffix: !isprod ? '-dev' : ''
			},
			sass: {
				outputStyle: 'compressed',
				precision: 3,
			}
		};

	return gulp.src(path.join(src, '/*.scss'))
		.pipe(!isprod ? sourcemaps.init() : gutil.noop())
		.pipe(sass(opts.sass))
		.pipe(autoprefixer())
		.pipe(isprod ? header(banner, opts.banner) : gutil.noop())
		.pipe(!isprod ? sourcemaps.write('') : gutil.noop())
		.pipe(gulp.dest(basedir));
});

gulp.task('svg', function() {
	var src = basedir + '/src/svg',
		opts = {
			plugins: [
				{ removeViewBox: false },
				{ removeUselessStrokeAndFill: true },
				{ cleanupIDs: false }
			]
		};

	return gulp.src(path.join(src, '/**/*.svg'))
		.pipe(svgmin(opts))
		.pipe(gulp.dest(path.join(basedir, '/images')));
});

// Cleanup
gulp.task('clean-sourcemaps', function () {
	return gulp.src(path.join(basedir, '/*.map'), { read: false })
		.pipe(isprod ? clean() : gutil.noop());
});


// Watch these files for changes and run the task on update
gulp.task('watch', function() {
	gulp.watch(basedir + '/src/js/**/*.js', ['js']);
	gulp.watch(basedir + '/src/scss/**/*.scss', ['css']);
	gulp.watch(basedir + '/src/svg/**/*.svg', ['svg']);
});

// run the watch task when gulp is called without arguments
gulp.task('default', ['js', 'css', 'svg', 'clean-sourcemaps']);
