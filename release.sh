version=1.0.3-linkedin
if [ "$1" = "zip" ]; then
	rm -rf shariff-wp
	mkdir -p shariff-wp
	cp -R src/ shariff-wp
	mkdir -p shariff-wp/dep
	cp shariff/build/shariff.min.* shariff-wp/dep
	cp -R backend-php/build/src shariff-wp/backend/src
	cp -R backend-php/build/vendor shariff-wp/backend/vendor
	# remove backend/vendor/guzzlehttp/guzzle/tests/perf.php so WordPress.org doesn't complain
	rm -f shariff-wp/backend/vendor/guzzlehttp/guzzle/tests/perf.php
	zip -r shariff-wp-$version.zip shariff-wp
	rm -rf shariff-wp
elif [ "$1" = "svn" ]; then
	mkdir -p shariff-svn
	svn co http://plugins.svn.wordpress.org/shariff-sharing/ shariff-svn
	rm -rf shariff-svn/trunk/*
	cp -R src/ shariff-svn/trunk
	mkdir -p shariff-svn/trunk/dep
	cp shariff/build/shariff.min.* shariff-svn/trunk/dep
	cp -R backend-php/build/src shariff-svn/trunk/backend/src
	cp -R backend-php/build/vendor shariff-svn/trunk/backend/vendor
	# remove backend/vendor/guzzlehttp/guzzle/tests/perf.php so WordPress.org doesn't complain
	rm -f shariff-svn/trunk/backend/vendor/guzzlehttp/guzzle/tests/perf.php
else
	echo "Nothing specified! Exiting..."
	exit 1
fi