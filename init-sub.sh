faversion=4.2.0

echo initializing submodules...
git submodule init
git submodule update
# cd shariff
# git fetch git://github.com/yanniks/shariff.git master
# git cherry-pick 85abf6b3f1e80c16fb87c99c73e28240005035c3
mkdir -p fontawesome
cd fontawesome
curl -O http://fortawesome.github.io/Font-Awesome/assets/font-awesome-$faversion.zip
unzip font-awesome-$faversion.zip
rm font-awesome-$faversion.zip
mv font-awesome*/fonts .
rm -rf font-awesome*
echo done!
