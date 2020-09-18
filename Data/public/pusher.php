<?php
  require('Pusher.php');

  $options = array(
    'cluster' => 'ap1',
    'encrypted' => true
  );
  $pusher = new Pusher(
    '866bb1c87e1268afb90e',
    '5fe23fc98b510b97da2e',
    '303914',
    $options
  );

  $data['message'] = 'hello world';
  $pusher->trigger('my-channel', 'my-event', $data);
?>
