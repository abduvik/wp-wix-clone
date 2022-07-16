fswatch -o packages/wix-host | xargs -n1 -I{} rsync -a packages/wix-host dist/host/plugins
fswatch -o packages/wix-client | xargs -n1 -I{} rsync -a packages/wix-client dist/client/plugins
