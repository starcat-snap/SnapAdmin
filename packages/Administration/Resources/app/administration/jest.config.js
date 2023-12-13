/**
 * @package admin
 */

// For a detailed explanation regarding each configuration property, visit:
// https://jestjs.io/docs/en/configuration.html
const {existsSync} = require('fs');
const {join, resolve} = require('path');

process.env.PROJECT_ROOT = process.env.PROJECT_ROOT || process.env.INIT_CWD || '.';
process.env.ADMIN_PATH = process.env.ADMIN_PATH || __dirname;
process.env.TZ = process.env.TZ || 'Asia/Shanghai';

process.env.JEST_CACHE_DIR = process.env.JEST_CACHE_DIR || '<rootDir>.jestcache';

const isCi = (() => {
    return process.argv.some((arg) => arg === '--ci');
})();

if (isCi) {
    // eslint-disable-next-line no-console
    console.info('Run Jest in CI mode');
} else {
    // eslint-disable-next-line no-console
    console.info('Run Jest in local mode');
}
module.exports = {
    cacheDirectory: process.env.JEST_CACHE_DIR,
    globals: {
        adminPath: process.env.ADMIN_PATH,
        projectRoot: process.env.PROJECT_ROOT,
    },

    globalTeardown: '<rootDir>test/globalTeardown.js',
    testRunner: 'jest-jasmine2', coverageDirectory: join(process.env.PROJECT_ROOT, '/build/artifacts/jest'),
    collectCoverageFrom: [
        'src/**/*.js',
        'src/**/*.ts',
        '!src/**/*.spec.js',
    ],

    coverageReporters: [
        'text',
        'cobertura',
        'html-spa',
    ],
    transform: {
        // stringify svg imports
        '.*\\.(svg)$': '<rootDir>/test/transformer/svgStringifyTransformer.js',
    },

    reporters: isCi ? [
        [
            'jest-silent-reporter',
            {
                useDots: true,
                showWarnings: true,
                showPaths: true,
            },
        ],
        ['jest-junit', {
            suiteName: 'SnapAdmin Unit Tests',
            outputDirectory: join(process.env.PROJECT_ROOT, '/build/artifacts/jest'),
            outputName: 'administration.junit.xml',
        }],
    ] : [
        'default',
    ],
    testMatch: [
        '<rootDir>/src/**/*.spec.js'
    ],

    testEnvironmentOptions: {
        customExportConditions: ['node', 'node-addons'],
    },
}
