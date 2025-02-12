<html>
	<head>
		<title>Laravel</title>

		<script src="//js.pusher.com/3.0/pusher.min.js"></script>
		<script>

		Pusher.log = function(msg) {
  console.log(msg);
};

		var pusher = new Pusher("{{env("PUSHER_KEY")}}", {
			cluster: 'ap1',
			encrypted: false
		});

		var channel = pusher.subscribe('test-channel');
		channel.bind('test-event', function(data) {
		  alert(data.text);
		});
		</script>

		<link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>

		<style>
			body {
				margin: 0;
				padding: 0;
				width: 100%;
				height: 100%;
				color: #B0BEC5;
				display: table;
				font-weight: 100;
				font-family: 'Lato';
			}

			.container {
				text-align: center;
				display: table-cell;
				vertical-align: middle;
			}

			.content {
				text-align: center;
				display: inline-block;
			}

			.title {
				font-size: 96px;
				margin-bottom: 40px;
			}

			.quote {
				font-size: 24px;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="content">
				<div class="title">Laravel 5</div>
				<div class="quote">{{ Inspiring::quote() }}</div>
			</div>
		</div>
	</body>
</html>
