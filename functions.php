<?php
  function get_balance() {
    $sql = 'SELECT sum(`amount`) FROM `expenses`';
    $result = $GLOBALS['conn']->query($sql);
    $balance = $result->fetch_array()[0];
    if ($balance == '') $balance = 0;
    echo $balance;
  }

  function get_income() {
    $sql = 'SELECT sum(`amount`) FROM `expenses` WHERE `type`="income"';
    $result = $GLOBALS['conn']->query($sql);
    $income = $result->fetch_array()[0];
    if ($income == '') $income = 0;
    echo $income;
  }

  function get_expense() {
    $sql = 'SELECT sum(`amount`) FROM `expenses` WHERE `type`="expense"';
    $result = $GLOBALS['conn']->query($sql);
    $expense = $result->fetch_array()[0];
    echo abs($expense);
  }

  function get_action_links($id) {
    echo '<form action="home.php" method="post">';
    echo '<input type="hidden" name="update">';
    echo '<input type="hidden" name="id" value="'.$id.'">';
    echo '<input type="image" class="btn edit" alt="edit" src="image/edit-16.png">';
    echo '</form>';
    echo '<form action="home.php" method="post">';
    echo '<input type="hidden" name="delete">';
    echo '<input type="hidden" name="id" value="'.$id.'">';
    echo '<input type="image" class="btn delete" alt="delete" src="image/x-mark-16.png">';
    echo '</form>';
  }

  function get_select_query() {
    if (isset($_POST['search']) && $_POST['search_str'] != '') {
      return 'SELECT * FROM `expenses` WHERE `'.$_POST['filter'] . '` = "'.$_POST['search_str'].'" ORDER BY `date` DESC';
    } else {
      return 'SELECT * FROM `expenses` ORDER BY `date` DESC';
    }
  }

  function delete_transaction() {
    $sql = 'DELETE FROM `expenses` WHERE `id`='.$_POST['id'];
    $GLOBALS['conn']->query($sql);
  }

  function add_transaction() {
    $sql = 'INSERT INTO `expenses` VALUES(0, "'.$_POST['text'].'","'.$_POST['category'].'","'.$_POST['date'].'",'.$_POST['amount'].',"'.($_POST['amount'] >= 0 ? 'income' : 'expense').'")';
    $GLOBALS['conn']->query($sql);
  }

  function update_transaction() {
    $sql = 'UPDATE `expenses` SET '.'`text` = "'.$_POST['text'].'", `category` = "'.$_POST['category'].'", `date` = "'.$_POST['date'].'", `amount` = '.$_POST['amount'].', `type` = "'.($_POST['amount'] >= 0 ? 'income' : 'expense').'" WHERE `id` = '.$_POST['id'];
    $GLOBALS['conn']->query($sql);
  }

  function get_add_form() {
    echo '<h3>Add Transaction</h3>';
    echo '<form method="post" action="home.php">';
    echo '<input type="hidden" name="add">';
    echo '<input type="text" name="text" placeholder="Text" required>';
    echo '<input type="text" name="category" placeholder="Category" required>';
    echo '<input type="date" name="date" placeholder="Date" required>';
    echo '<input type="number" name="amount" placeholder="Amount (positive - income, negative - expense)" required>';
    echo '<input class="btn add-update" type="submit" value="Add Transaction">';
    echo '</form>';
  }

  function get_update_form() {
    $result = $GLOBALS['conn']->query('SELECT * FROM `expenses` WHERE `id`='.$_POST['id']);
    $row = $result->fetch_assoc();
    echo '<h3>Update Transaction</h3>';
    echo '<form method="post" action="home.php">';
    echo '<input type="hidden" name="save">';
    echo '<input type="hidden" name="id" value="'.$_POST['id'].'" required>';
    echo '<input type="text" name="text" value="'.$row['text'].'" placeholder="Text" required>';
    echo '<input type="text" name="category" value="'.$row['category'].'" placeholder="Category" required>';
    echo '<input type="date" name="date" value="'.$row['date'].'" placeholder="Date" required>';
    echo '<input type="number" name="amount" value="'.$row['amount'].'" placeholder="Amount (positive - income, negative - expense)" required>';
    echo '<input class="btn add-update" type="submit" value="Update Transaction">';
    echo '</form>';   
  }

  function get_list_item($row) {
    $text = $row['text'];
    $category = strtolower($row['category']);
    $date = $row['date'];
    $amount = abs($row['amount']);
    $type = $row['type'];
    $sign = ($type == 'income' ? '+' : '-');
    $class = ($type == 'income' ? 'plus' : 'minus');

    echo '<li class="'.$class.'">';
    echo '<span>' . $text . '</span>';
    echo '<span>' . $category . '</span>';
    echo '<span>' . $date . '</span>';
    echo '<span>' . $sign . '&#8377;' . $amount . '</span>';
    get_action_links($row['id']);
    echo '</li>';
  }
?>