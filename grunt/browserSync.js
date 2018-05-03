module.exports = function (grunt, options) {
    return {
        dev: {
            bsFiles: {
                src: [
                    'web/assets/css/*.css',
                    'web/assets/js/*.js',
                    './**/*.php'
                ]
            },
            options: {
                watchTask: true,
                proxy: options.config.domain.url,
                online: options.config.bs.online,
                reload_delay: options.config.bs.delay,
                open: options.config.bs.open,
                notify: options.config.bs.notify,
                browser: options.config.bs.browser
            }
        }
    };
};
