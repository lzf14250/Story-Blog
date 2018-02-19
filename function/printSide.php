<div class="side">
  <form id="category_form" action="category.php">
    <div>Categories</div>
    <input id="category_id" type="hidden" name="categoryID" />
  <?php
    $stmt = $mysqli->prepare("select * from category");
    if(!$stmt){
      print("Can not get categories");
    }else{
      $stmt->execute();
      $stmt->bind_result($category_id,$category_name);
      while($stmt->fetch()){
        printf("<a onclick=\"category_id.value=%d;category_form.submit()\">%s</a>",$category_id,$category_name);
      }
    }
  ?>
  </form>
</div>
