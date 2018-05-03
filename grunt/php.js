module.exports = function (grunt, options) {
    return {
        dev: {
            options: {
                hostname: options.config.domain.host,
                port: options.config.domain.port,
                base: 'web',
                keepalive: false,
                open: false
            }
        }
    };
};
