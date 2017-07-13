Streams API
===========

A simple API for retrieving video stream and ad details so that content
can be played back on a diverse set of devices.

## Requirements

This application requires the following external dependencies.

- PHP 7.1 or greater with the following extensions
    - CURL (generally included by default)

## Setup

- Install dependencies.

    ```
    composer install
    ```

- Modify configuration file, if desired. The following configuration values in `config/config.yml` can be modified to fit your needs.

    - `stream.fixture.uri`: The URI of the streams fixture file that will be used when displaying information about streams.
    - `stream.ad.service.uri`: The URI of the AD Service that will be used to determine which ads to show for each stream.
    - `jwt.secret`: The shared secret for generating and reading JWT secrets. Used by the Authentication middleware.
    - `debug`: A boolean value that enables or disables debug mode.

- Start the development server.

    ```
    bin/serve
    ```

    Once started, the application will be available for use at [http://localhost:8080](http://localhost:8080). Note that this server should not be used in production! Instead, use Nginx or another web server to handle and dispatch requests to this application.

- Run tests.

    This application is bundled with a small set of unit and acceptance tests. Note that the tests assume that the application is up and responding to requests at `http://localhost:8080` and that `debug` mode is true.

    ```
    bin/test
    ```

- Generate a JWT auth token

    This application uses a simple JWT based auth token to authentication each request to the API. You can easily generate a token by running the following command. This authentication is disabled when `debug` is true.

    ```
    bin/jwt
    ```

    To use the token, just include it in the `Authorization` header along with each request.

## Authentication

When `debug` is set to false, a valid auth token will be required to use this API. The token must be a valid JWT token signed using the `jwt.secret  value. This value must be passed in the `Authentication` header along with all requests, other than the health check.

## Usage

There are three routes available for use in this API.


- `GET /`

    A simple health check route that can be used to determine the health
    of the application. The load balancer should be configured to check
    for a `200` HTTP status.

    ```
    HTTP/1.1 200 OK
    Content-Type: application/json

    {
        "status": "good"
    }
    ```

- `GET /v1/streams`

    Retrieve a collection of streams. This route currently returns all available streams. In a real application, the collection would be limited to a reasonable size and paginated.

    ```
    HTTP/1.1 200 OK
    Content-Type: application/json

    [
        {
            "id": "...",
            "streamUrl": "...",
            "captions": {
                "vtt": {
                    "en": "..."
                },
                "scc": {
                    "en": "..."
                }
            },
            "ads": {
                ...
            }
        },
        ...
    ]
    ```

- `GET /v1/streams/{id}`

    Retrieve a single stream. Note that ID string are assumed to be 24 character hex encoded streams! All other ID formats will result in a 404 error.

    ```
    HTTP/1.1 200 OK
    Content-Type: application/json

    {
        "id": "...",
        "streamUrl": "...",
        "captions": {
            "vtt": {
                "en": "..."
            },
            "scc": {
                "en": "..."
            }
        },
        "ads": {
            ...
        }
    }
    ```

# Project Details

This project is built with the following tools.

- **PHP 7.1**: The latest stable release of PHP
- **Slim Framework**: A PHP micro framework
- **Symfony DI**: The Symfony DI component
- **Codeception**: A PHP based testing framework

This application is bootstrapped a bit differently than most Slim applications.

- Use of Symfony DI container instead of the internal Slim Pimple container for greater flexibility and feature set.
- Use of a route loader (`src/Bootstrap/RouteLoader.php`) instead of calling the Slim `match()` method directly. This provides the ability to keep routing details in a configuration file.
- Controllers and middleware are lazy loaded from the DI Container when a route is matched.
- Routes are comprised of a simple stack of callable middleware and controllers.

While this application does not cache the DI container or routes, it't not a good idea to do that in a production application as it is much slower. Normally, the DI container should be compiled and cached to a file and the routes should be cached to a file. This greatly improves performance.

This application is organized into the following structure.

- `bin/`: Scripts for managing the application
- `config/`: Configuration files
- `public/`: The server web root
    - `index.php`: All requests should be routed to this file
- `src/`: All application source code
    - `Bootstrap/`: Application setup and supporting code
    - `Controller/`: Application controllers
    - `Data/`: Application data management
    - `Middleware/`: Middleware objects
    - `Utility/`: Generic utility methods
- `test/`:
    - `unit/`: Unit tests
    - `acceptance/`: Acceptance tests

