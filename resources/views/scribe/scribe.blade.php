<!doctype html>
<html>
<head>
    <title>Laravel Doctrine JSON:API Skeleton API Documentation</title>
    <meta charset="utf-8"/>
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"/>
    <style>
        body {
            margin: 0;
        }
    </style>
</head>
<body>
    <div id="app"></div>
    <script>
        // Store the original fetch function
        const originalFetch = window.fetch;

        // Override the global fetch with our version
        window.fetch = function(request, options = {}) {
            if (request instanceof Request) {
                if (!request.headers) {
                    request.headers = new Headers();
                }

                // Add XSRF token to headers
                const xsrfCookie = document.cookie
                    .split('; ')
                    .find(row => row.startsWith('XSRF-TOKEN='));
                
                if (xsrfCookie) {
                    const xsrfToken = xsrfCookie.split('=')[1];
                    request.headers.append('X-XSRF-TOKEN', decodeURIComponent(xsrfToken));
                }
            }
            
            // Call the original fetch with our modified options
            return originalFetch(request, options);
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@scalar/api-reference"></script>
    <script>
        const scalar = Scalar.createApiReference('#app', {
            // The URL of the OpenAPI/Swagger document
            url: '{{ route("scribe.openapi") }}',
        })
    </script>
</body>
</html>
