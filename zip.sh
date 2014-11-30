version=1.0.2
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
