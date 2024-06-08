<?php
session_start();
ini_set( 'display_errors', 1 );

require_once 'header.php';
require_once 'footer.php';
require_once 'db.php';

if (isset($_GET['id'])) {
  $db->delete($_GET['id']);
} else {
  header_php();
  echo <<< EOM
  <main class="p-4 border border-primary">
    <article class="m-auto">
      {$db->list()}
    </article>
  </main>
  EOM;
  footer(); 
}
?>