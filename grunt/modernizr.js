// https://modernizr.com/download?history-localstorage-sessionstorage-setclasses
module.exports = {
    dist: {
        'crawl': false,
        'customTests': [],
        'dest': '<%= path.dest.js %>/modernizr.min.js',
        'tests': [
            'history',
            'localstorage',
            'sessionstorage'
        ],
        'options': [
            'setClasses'
        ],
        'uglify': true
    }
};
