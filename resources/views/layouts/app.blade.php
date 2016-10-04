<!DOCTYPE html>
<html>
	<head>
        <meta charset="utf-8">
		<title><?= config('main.application.title') ?></title>
        <meta name="description" content="<?= config('main.application.description') ?>">
        <meta name="author" content="<?= config('main.application.author') ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="{{ elixir('css/all.css') }}">
	</head>
	<body>
 		@yield('content')
        <script src="{{ elixir('js/all.js') }}"></script>
	</body>
</html>
