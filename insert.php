<?php
session_start();
ini_set( 'display_errors', 1 );

require_once 'header.php';
require_once 'footer.php';
require_once 'db.php';

?>
<?php header_php(); ?>

    <main class="p-4 border border-primary">
      <section class="m-auto w-75">
        <?php if (isset($_POST['submit'])): ?>
          <?php $db->create(); ?>
        <?php else: ?>
        <h4>ユーザー登録</h4>
        <p>ユーザー情報を入力してください</p>
        <form action="insert.php" method="post">
          <label for="user_name">お名前<span>【必須】</span></label>
          <input type="text" id="user_name" name="user_name" maxlength="60" required class="form-control">

          <label for="user_furigana">ふりがな<span>【必須】</span></label>
          <input type="text" id="user_furigana" name="user_furigana" maxlength="60" required class="form-control">

          <label for="user_email">メールアドレス<span>【必須】</span></label>
          <input type="email" id="user_email" name="user_email" maxlength="255" required class="form-control">

          <label for="user_age">年齢</label>
          <input type="number" id="user_age" name="user_age" min="13" max="130" class="form-control">

          <label for="user_address">住所</label>
          <input type="text" id="user_address" name="user_address" maxlength="255" class="form-control">
          
          <div class="mt-4 text-center">
            <button type="submit" name="submit" value="insert" class="btn btn-primary">登録</button>
          </div>
        </form>
        <?php endif; ?>
      </section>
    </main>

<?php footer(); ?>