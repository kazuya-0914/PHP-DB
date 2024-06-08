<?php
class DB {
  // 定数
  private $dsn;
  private $user;
  private $password;
  private $pdo;

  // 変数
  public $error_flag;
  public $user_data;

  public function __construct() {
    $this->dsn = 'mysql:dbname=php_db;host=localhost;charset=utf8mb4';
    $this->user = 'root';
    $this->password = 'root';
    $this->pdo = new PDO($this->dsn, $this->user, $this->password);
    $this->error_flag = 0;
    $this->user_data = [];
  }

  // データ登録ページ
  public function create() {
    if(isset($_POST['submit'])) {
      try {
        $sql = '
            INSERT INTO users (name, furigana, email, age, address)
            VALUES (:name, :furigana, :email, :age, :address)
        ';
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':name', $_POST['user_name'], PDO::PARAM_STR);
        $stmt->bindValue(':furigana', $_POST['user_furigana'], PDO::PARAM_STR);
        $stmt->bindValue(':email', $_POST['user_email'], PDO::PARAM_STR);
        $stmt->bindValue(':age', $_POST['user_age'], PDO::PARAM_INT);
        $stmt->bindValue(':address', $_POST['user_address'], PDO::PARAM_STR);

        // $stmt->execute();
        $_SESSION['msg'] = 'ユーザー情報を登録しました';

        header('Location: index.php');
      } catch (PDOException $e) {
        echo "エラー： {$e->getMessage()}";
      }
    }
  }

  // トップページ
  public function read() {
    try {
      $sql = 'SELECT COUNT(*) AS total FROM users';
      $stmt = $this->pdo->query($sql);
      $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $total = $data[0]['total'];

      // SQL文
      $sql = "SELECT id AS 'ID', name AS '名前', furigana AS 'ふりがな', age AS '年齢' FROM users";

      // 検索キーワード
      $keyword = !empty($_GET['keyword']) ? $_GET['keyword'] : '';

      // ソート
      $order = !empty($_GET['order']) ? $_GET['order'] : '';

      // --- ユーザー情報出力（検索 + ソートも含む）
      if ($keyword !== '') {
        // SQL文
        $sql = $sql . " WHERE furigana LIKE :keyword";
        $stmt = $this->pdo->prepare($sql);
        $partial_match = "%{$keyword}%";
        $stmt->bindValue(':keyword', $partial_match, PDO::PARAM_STR);

        // SQL文を実行する（プレースフォルダあり）
        $stmt->execute();
      } elseif ($order !== '') {
        // SQL文
        $sql = $sql . " ORDER BY age {$order}";

        // SQL文を実行する（プレースフォルダなし）
        $stmt = $this->pdo->query($sql);
      } else {
        // SQL文を実行する（プレースフォルダなし）
        $stmt = $this->pdo->query($sql);
      }

      $datas = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $i = 0;

      // --- ユーザー登録からのフラッシュメッセージ
      if(isset($_SESSION['msg'])) {
        echo "<div class='msg p-2 mb-4 m-auto border border-danger'>{$_SESSION['msg']}</div>\n";
        // 全セッション破棄
        $_SESSION = array();
        session_destroy();
      }
  
      // --- ユーザー情報一覧
      echo "<p>合計人数： {$total}人</p>\n";
      echo "<section class='table read'>\n";
      foreach ($datas as $data) {
        if(!$i) {
          foreach ($data as $key => $val) {
            echo "<div class='th'>{$key}</div>";
          }
          echo "\n";
          $i = 1;
        }
        foreach ($data as $val) {
          echo "<div>{$val}</div>";
        }
        echo "\n";
      }
    } catch(PDOException $e) {
      echo "エラー： {$e->getMessage()}";
    }
    echo "</section>\n";
  }

  // リスト一覧表示
  public function list() {
    try {
      $sql = 'SELECT COUNT(*) AS total FROM users';
      $stmt = $this->pdo->query($sql);
      $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $total = $data[0]['total'];

      $sql = 'SELECT * FROM users';
      $stmt = $this->pdo->query($sql);
      $datas = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $i = 0;

      if(isset($_SESSION['msg'])) {
        echo "<div class='msg p-2 mb-4 m-auto border border-danger'>{$_SESSION['msg']}</div>\n";
        // 全セッション破棄
        $_SESSION = array();
        session_destroy();
      }
  
      echo "<p>合計人数： {$total}人</p>\n";
      echo "<section class='table update'>\n";
      foreach ($datas as $data) {
        if(!$i) {
          foreach ($data as $key => $val) {
            echo "<div class='th'>{$key}</div>";
          }
          echo "<div class='th'></div>\n";
          $i = 1;
        }
        foreach ($data as $val) {
          echo "<div>{$val}</div>";
        }
        echo <<< EOM
        <div>
          <a href='update.php?id={$data['id']}' class='btn btn-primary'>編集</a>
          <a href='delete.php?id={$data['id']}' class='btn btn-danger'>削除</a>
        </div>
        EOM;
      }
    } catch(PDOException $e) {
      echo "エラー： {$e->getMessage()}";
    }
    echo "</section>\n";
  }

  // 個人データ表示
  public function user_data($id) {
    $_SESSION['id'] = $id; // IDデータをセッションに格納

    try {
      $sql = 'SELECT * FROM users WHERE id = :id';
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);

      // SQL文を実行する
      $stmt->execute();

      $this->user_data = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($this->user_data === FALSE) {
        $this->error_flag = 1;
      }
    } catch (PDOException $e) {
      echo "エラー： {$e->getMessage()}";
    }

    return $this->error_flag;
  }

  // リスト更新
  public function update() {
    if(isset($_POST['submit'])) {
      try {
        $sql = '
            UPDATE users
            SET name = :name,
            furigana = :furigana,
            email = :email,
            age = :age,
            address = :address
            WHERE id = :id
        ';
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':name', $_POST['user_name'], PDO::PARAM_STR);
        $stmt->bindValue(':furigana', $_POST['user_furigana'], PDO::PARAM_STR);
        $stmt->bindValue(':email', $_POST['user_email'], PDO::PARAM_STR);
        $stmt->bindValue(':age', $_POST['user_age'], PDO::PARAM_INT);
        $stmt->bindValue(':address', $_POST['user_address'], PDO::PARAM_STR);
        $stmt->bindValue(':id', $_SESSION['id'], PDO::PARAM_INT);

        // SQL文を実行する
        $stmt->execute();

        // $stmt->execute();
        $_SESSION['msg'] = "ID={$_SESSION['id']} {$_POST['user_name']}さんのユーザー情報を更新しました";

        header('Location: update.php');
      } catch (PDOException $e) {
        echo "エラー： {$e->getMessage()}";
      }
    }
  }

  // リスト削除
  public function delete($id) {
    $_SESSION['id'] = $id; // IDデータをセッションに格納

    try {
      $sql = 'DELETE FROM users WHERE id = :id';
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);

      // SQL文を実行する
      $stmt->execute();

      // $stmt->execute();
      $_SESSION['msg'] = "ID={$_SESSION['id']} のユーザー情報を削除しました";

      header('Location: update.php');
    } catch (PDOException $e) {
      echo "エラー： {$e->getMessage()}";
    }
  }
}
$db = new DB();

?>