<?php
  require 'connection.php';
  include 'functions.php';
  $form_type = 'add';
  if (isset($_POST['delete'])) {
    delete_transaction();
  } else if (isset($_POST['add'])) {
    add_transaction();
  } else if (isset($_POST['update'])) {
    $form_type = 'update';
  } else if (isset($_POST['save'])) {
    update_transaction();
  }
?>

<html>
  <head>
    <title>Expense Tracker</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <div class="header">
      <h1>Expense Tracker</h1>
    </div>
    
    <div class="container">
      
      <div class="left">

        <div class="balance">
          <h4>Your Balance</h4>
          <h1>&#8377;<?php get_balance() ?></h1>
        </div>

        <div class="inc-exp-container">
          <div>
            <h4>Income</h4>
            <p class="money plus">+ &#8377;<?php get_income() ?></p>
          </div>
          <div>
            <h4>Expense</h4>
            <p class="money minus">- &#8377;<?php get_expense() ?></p>
          </div>
        </div>

        <div class="add-update-form">
          <?php
            if ($form_type == 'add')
              get_add_form();
            else
              get_update_form();
          ?>
        </div>
      
      </div>
      
      <div class="right">
        <div class="transactions">
          <h3>Transactions</h3>

          <form method="post" action="home.php">
            <input type="hidden" name="search">
            <div>
              <input type="text" name="search_str" placeholder="Search..." 
                     value="<?php  
                              if (isset($_POST['search'])) echo $_POST['search_str'];
                            ?>">
              <input class="btn search" type="submit" value="Search"><br>
            </div>
            <div>
              <label>search by:</label>
              <label>
                <input type="radio" name="filter" value="text" <?php
                  if (!isset($_POST['search'])) echo 'checked'; 
                  else if ($_POST['filter'] == 'text' || $_POST['search_str']=='') echo 'checked'; 
                ?>>
                Text
              </label>
              <label>
                <input type="radio" name="filter" value="category" <?php 
                  if (isset($_POST['search']) && $_POST['filter']=="category" && $_POST['search_str']!='') echo 'checked';
                ?>>
                Category
              </label>
              <label>
                <input type="radio" name="filter" value="date" <?php
                  if (isset($_POST['search']) && $_POST['filter']=="date" && $_POST['search_str']!='') echo 'checked';
                ?>>
                Date
              </label>
              <label>
                <input type="radio" name="filter" value="type" <?php
                  if (isset($_POST['search']) && $_POST['filter']=="type" && $_POST['search_str']!='') echo 'checked';
                ?>>
                Type
              </label>
            </div>
          </form>
          <div>
            <ul class="list">
              <?php
                $sql = get_select_query();
                $result = $conn->query($sql);
                if(!$result) echo 'No Records Found';
                else while ($row = $result->fetch_assoc()) {
                  get_list_item($row);
                }
              ?>
            </ul>
          </div>
        </div>   
      </div>

    </div>
  </body>
</html>

<?php $conn->close(); ?>