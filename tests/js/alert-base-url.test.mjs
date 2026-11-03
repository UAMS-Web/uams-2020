// Pins the URLs UAMS.getBaseUrl() returns for the page hosts the theme runs on.
//
// Run from the theme root: node --test tests/js/
//
// Node's built-in test runner only. assets/js/uams.alert.js is loaded into a vm
// context with minimal stand-ins for jQuery, Backbone and Underscore, so no
// package is installed.

import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import { test } from 'node:test';
import vm from 'node:vm';

const source = readFileSync(new URL('../../assets/js/uams.alert.js', import.meta.url), 'utf8');

/**
 * Load uams.alert.js for one page location and return the UAMS object.
 *
 * The script wraps everything in a jQuery ready callback; the first one runs at
 * once and the ready callback it registers for initialization is not run.
 */
function loadUams(location) {
  let depth = 0;
  const jQuery = (target) => ({
    ready(callback) {
      if (depth > 0) {
        return;
      }
      depth += 1;
      callback.call(undefined, jQuery);
    },
  });
  const extend = () => function Stub() {};
  const context = vm.createContext({
    jQuery,
    document: {},
    window: {},
    Backbone: {
      history: { location },
      View: { extend },
      Model: { extend },
    },
    _: {
      first: (list) => list[0],
      compact: (list) => list.filter(Boolean),
      some: (list, test) => list.some(test),
    },
  });

  vm.runInContext(source, context);

  return context.UAMS;
}

function baseUrl(location) {
  return loadUams({ ...location }).getBaseUrl();
}

test('www.uams.edu with a site path keeps the first path segment', () => {
  assert.equal(
    baseUrl({ origin: 'https://www.uams.edu', hostname: 'www.uams.edu', pathname: '/uamshealth/about/' }),
    'https://www.uams.edu/uamshealth/',
  );
});

test('www.uams.edu at the root returns the origin alone', () => {
  assert.equal(
    baseUrl({ origin: 'https://www.uams.edu', hostname: 'www.uams.edu', pathname: '/' }),
    'https://www.uams.edu/',
  );
});

test('a uamsweb.com subdomain keeps the first path segment', () => {
  assert.equal(
    baseUrl({ origin: 'https://dev.uamsweb.com', hostname: 'dev.uamsweb.com', pathname: '/mysite/page/' }),
    'https://dev.uamsweb.com/mysite/',
  );
});

test('a host outside both returns the origin alone', () => {
  assert.equal(
    baseUrl({ origin: 'https://example.org', hostname: 'example.org', pathname: '/some/path/' }),
    'https://example.org/',
  );
});

test('a port stays in the result', () => {
  assert.equal(
    baseUrl({ origin: 'http://localhost:8080', hostname: 'localhost', pathname: '/site/' }),
    'http://localhost:8080/',
  );
});

test('a location with no origin builds one from protocol, hostname and port', () => {
  assert.equal(
    baseUrl({ protocol: 'https:', hostname: 'www.uams.edu', port: '', pathname: '/uamshealth/' }),
    'https://www.uams.edu/uamshealth/',
  );
  assert.equal(
    baseUrl({ protocol: 'http:', hostname: 'localhost', port: '8080', pathname: '/site/' }),
    'http://localhost:8080/',
  );
});

test('a host that only contains www.uams.edu currently receives the site path', () => {
  assert.equal(
    baseUrl({
      origin: 'https://www.uams.edu.example.org',
      hostname: 'www.uams.edu.example.org',
      pathname: '/uamshealth/',
    }),
    'https://www.uams.edu.example.org/uamshealth/',
  );
});
