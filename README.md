# Weblex Importer

Import posts from a **Weblex** RSS feed. What the plugin does, how to install it and its changelog are in [readme.txt](readme.txt).

## Development

```sh
composer install
```

### Coding standards

The code follows the [WordPress coding standards](https://github.com/WordPress/WordPress-Coding-Standards), checked by `phpcs` with the rules of `phpcs.xml`:

```sh
vendor/bin/phpcs
vendor/bin/phpcbf
```

### Tests

The tests run on the test suite of WordPress: the core installed by Composer, the plugin loaded as a must-use plugin, and the feeds of `tests/fixtures` served without the network.

The suite empties its database on each run: give it one of its own, never the database of a site.

```sh
mysql -uroot -proot -e 'CREATE DATABASE IF NOT EXISTS weblex_importer_tests'
```

`tests/wp-tests-config.php` reads it from the environment, with these defaults:

| Variable | Default |
| --- | --- |
| `WP_TESTS_DB_NAME` | `weblex_importer_tests` |
| `WP_TESTS_DB_USER` | `root` |
| `WP_TESTS_DB_PASSWORD` | `root` |
| `WP_TESTS_DB_HOST` | `127.0.0.1` |

With Local, the host is the socket of a running site's MySQL:

```sh
export WP_TESTS_DB_HOST="localhost:$HOME/Library/Application Support/Local/run/<id>/mysql/mysqld.sock"
composer test
```

A push to `wordpress-plugin` and a pull request run the coding standards and the tests.

## Releasing

Sites update the plugin from the latest [GitHub release](https://github.com/19h47/weblex-importer/releases): the `Update URI` header of `weblex-importer.php` routes the update check to GitHub, and WordPress installs the archive of the release's tag, without the files listed `export-ignore` in `.gitattributes`.

1. Bump `Version` in `weblex-importer.php`, `version` in `composer.json` and `Stable tag` in `readme.txt`, with an entry in its changelog.
2. Push to `wordpress-plugin` and wait for the checks.
3. Publish the release, its tag the version, its notes the changelog entry:

   ```sh
   gh release create x.y.z --target wordpress-plugin --title x.y.z --notes "…"
   ```
