version=1.4.3
rm -rf shariff-wp
mkdir -p shariff-wp
cp -R src/ shariff-wp
mkdir -p shariff-wp/dep
cp shariff/build/shariff.min.* shariff-wp/dep
cp -R backend-php/build/src shariff-wp/backend/src
cp -R backend-php/build/vendor shariff-wp/backend/vendor
zip -r shariff-wp-$version.zip shariff-wp
rm -rf shariff-wp
