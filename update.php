<?php
session_start();
ini_set( 'display_errors', 1 );

require_once 'header.php';
require_once 'footer.php';
require_once 'db.php';

?>
<?php header_php(); ?>

    <main class="p-4 border border-primary">
      <article class="m-auto">
        <?php if (isset($_POST['submit'])): // 更新ボタンを押した時 ?>
        <section>
          <?php $db->update(); ?>
        </section>
        <?php elseif (isset($_GET['id']) && $db->user_data($_GET['id']) !== 1): // 編集ボタンを押した時かつ、ユーザーデータが存在した時 ?>
        <h4>ユーザー更新</h4>
        <p>更新するユーザー情報を入力してください</p>
        <form action="update.php" method="post">
          <label for="user_name">お名前<span>【必須】</span></label>
          <input type="text" id="user_name" name="user_name" value="<?= $db->user_data['name'] ?>" maxlength="60" required class="form-control">

          <label for="user_furigana">ふりがな<span>【必須】</span></label>
          <input type="text" id="user_furigana" name="user_furigana" value="<?= $db->user_data['furigana'] ?>" maxlength="60" required class="form-control">

          <label for="user_email">メールアドレス<span>【必須】</span></label>
          <input type="email" id="user_email" name="user_email" value="<?= $db->user_data['email'] ?>" maxlength="255" required class="form-control">

          <label for="user_age">年齢</label>
          <input type="number" id="user_age" name="user_age" value="<?= $db->user_data['age'] ?>" min="13" max="130" class="form-control">

          <label for="user_address">住所</label>
          <input type="text" id="user_address" name="user_address" value="<?= $db->user_data['address'] ?>" maxlength="255" class="form-control">

          <div class="mt-4 text-center">
            <button type="submit" name="submit" value="update" class="btn btn-primary">更新</button>
          </div>
        </form>
        <?php else: ?>
          <?php $db->list(); // リスト一覧表示 ?>
        <?php endif; ?>
      </article>
    </main>

<?php footer(); ?>