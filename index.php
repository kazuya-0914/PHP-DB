<?php
session_start();
ini_set( 'display_errors', 1 );

require_once 'header.php';
require_once 'footer.php';
require_once 'db.php';

?>
<?php header_php(); ?>

    <main class="p-4 border border-primary">
      <article class="w-75 m-auto">

        <!-- 検索フォーム -->
        <section class="mb-4">
          <form action="index.php" class="d-flex justify-content-center">
            <input type="text" name="keyword" placeholder="ふりがなで検索" class="me-2 w-75 form-control">
            <button type="submit" value="search" class="btn btn-primary">検索</button>
          </form>
        </section>

        <!-- ソート -->
        <section class="mb-4 d-flex justify-content-center">
          <a href="index.php?order=asc" class="me-2 btn btn-secondary">年齢順（昇順）</a>
          <a href="index.php?order=desc" class="btn btn-secondary">年齢順（降順）</a>
        </section>

        <!-- リスト一覧 -->
        <section>
          <?php $db->read(); ?>
        </section>
      </article>
    </main>

<?php footer(); ?>