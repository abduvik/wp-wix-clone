rm -rf build
mkdir build

cp -R packages/wix-host build/wix-host
cp -R packages/wix-client build/wix-client

rm -rf build/wix-host/vendor
rm -rf build/wix-client/vendor

(cd build/wix-host && composer install --no-dev)
(cd build/wix-client && composer install --no-dev)


zip -r build/wix-host.zip build/wix-host
zip -r build/wix-client.zip build/wix-client

rm -rf build/wix-host
rm -rf build/wix-client