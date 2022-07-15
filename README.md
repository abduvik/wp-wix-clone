# Wix SaaS Clone using WordPress, WooCommerce, WPCS & k8s

This is a Wix Clone build using various technologies to illustrate how easy it is to build SaaS products using WordPress

## [YouTube Video](http://www.youtube.com/watch?v=shEh0-P7pz0)

[![Building Wix SaaS Clone using WordPress, WooCommerce, WPCS & k8s](http://img.youtube.com/vi/######/0.jpg)](http://www.youtube.com/watch?v=###### "Building Wix SaaS Clone using WordPress, WooCommerce, WPCS & k8s")

## Local Development

### Required tools

- Docker and Docker-Compose
- Composer
- `fswarch` & `rsync`

### Steps

- `git clone` the project
- Run `composer install` inside `src` directory
- Create `.db` & `dist` directory in the root
- Run `docker-compose up`
- Run the following command to sync files between src and dist

```fswatch -o src | xargs -n1 -I{} rsync -a src/ dist/plugins/wpcs-woo-subscriptions```

