<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    @vite('resources/js/app.ts')
    @inertiaHead
</head>
<body>
@inertia
</body>
</html>
