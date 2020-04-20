<?php require_once('scripts/data.php'); ?>
<!doctype html>
<html lang="ja">
  <head>
    <?php $head_tpl->render('head.tpl'); ?>
  </head>
  <body>
    <?php $header_tpl->render('header.tpl'); ?>
    <?php $main_tpl->render('main.tpl'); ?>
    <?php $post_tpl->render('post.tpl'); ?>
    <?php $debug_tpl->render('debug.tpl'); ?>
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <!-- jQuery はAjax機能搭載のものに変更済み -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <!-- Project JavaScript -->
    <?php $script_tpl->render('script.tpl'); ?>
  </body>
</html>