<?php
 // nhung ket noi database
require('connect.php');
?>

<html>

<head>
    <title>QUAN LY SACH</title>
    <link rel="canonical" href="https://coreui.io/docs/components/bootstrap/forms/">
    <link rel="stylesheet"
        href="https://coreui.io/scss/docs.min.0d3c73ed0a8b57dc8347f29d9f0ae89cbf00b4584cad51eb2fae9274100ab3ae.css">
    <link rel="stylesheet"
        href="https://coreui.io/scss/coreui-pro.min.62161f2d1fe4008ba8902ca5b0c11919a52a9b98afcc1bc8d314353d10620371.css">
    <link rel="stylesheet"
        href="https://coreui.io/css/all.min.5fd217fb213b82cc07e1b7047a5c316f1b6a71ade5c36bbf0bd34c69c00c9192.css">
</head>

<body>
    <div class="container">

        <div class="cd-example">
        <?php

            // xu ly hanh dong them, sua, xoa
            if(isset($_POST['data'])) {
                if($_POST['masach'] &&
                    $_POST['tensach'] &&
                    $_POST['tacgia'] &&
                    $_POST['nhaxuatban'] &&
                    $_POST['gia'] &&
                    $_POST['theloai']) {

                        /*** THEM ***/
                    if(isset($_POST['add'])) {
                        mysqli_query($conn, "INSERT INTO `SACH` (`masach`, `tensach`, `tacgia`, `nhaxuatban`, `gia`, `theloai`) VALUES ('". $_POST['masach'] ."', '". $_POST['tensach'] ."', '". $_POST['tacgia'] ."', '". $_POST['nhaxuatban'] ."', '". $_POST['gia'] ."', '". $_POST['theloai'] ."')");
                        echo '<div class="alert alert-success" role="alert">Them sach thanh cong!</div>';
                    }

                    if(isset($_POST['edit'])) {
                        mysqli_query($conn, "UPDATE `SACH` 
                                SET 
                                `masach` = '". $_POST['masach'] ."',
                                `tensach` = '". $_POST['tensach'] ."',
                                `tacgia` = '". $_POST['tacgia'] ."',
                                `nhaxuatban` = '". $_POST['nhaxuatban'] ."',
                                `gia` = '". $_POST['gia'] ."',
                                `theloai` = '". $_POST['th'] ."' 
                                 WHERE `SACH`.`id` = '".$_POST['id']."'");
                        echo '<div class="alert alert-success" role="alert">SUA sach thanh cong!</div>';

                    }

                    if(isset($_POST['delete'])) {
                        mysqli_query($conn, "DELETE FROM `SACH` WHERE `id`='".$_POST['id']."'");
                        echo '<div class="alert alert-success" role="alert">Xoa sach thanh cong!</div>';

                    }

                }else {
                    echo '<div class="alert alert-danger" role="alert">Thieu Du Lieu!</div>';
                }                


            }
        ?>



            <form action="" method="post">
                <input name="data" type="hidden" value="loz">
                <p id="data_id" style="display:none;"></p>
                <div class="row">
                    <div class="col-md-4 col-lg-4">
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Ma Sach</label>
                            <input type="text" class="form-control" id="ma sach" placeholder="Ma Sach" name="masach">
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Ten Sach</label>
                            <input type="text" class="form-control" id="ma sach" placeholder="Ten Sach" name="tensach">
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Tac Gia</label>
                            <input type="text" class="form-control" id="ma sach" placeholder="Tac Gia" name="tacgia">
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Nha Xuat Ban</label>
                            <input type="text" class="form-control" id="ma sach" placeholder="Nha Xuat Ban"
                                name="nhaxuatban">
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Gia</label>
                            <input type="number" class="form-control" id="ma sach" placeholder="Gia Tien" name="gia">
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="form-group">
                            <label for="exampleFormControlInput1">The Loai Sach</label>
                            <select class="form-control" name="theloai">
                                <option value="tinhoc">Tin Hoc</option>
                                <option value="ngoaingu">Ngoai Ngu</option>
                                <option value="toan">Toan</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-12">
                        <center>
                            <button type="submit" name="add" class="btn btn-md btn-success">Thêm</button>
                            <button type="submit" name="edit" class="btn btn-md btn-warning">Sửa</button>
                            <button type="submit" name="delete" class="btn btn-md btn-danger">Xóa</button>
                        </center>
                    </div>
                </div>
            </form>
        </div>

        <div class="cd-example">
            <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>STT</th>
                    <th>MA SACH</th>
                    <th>TEN SACH</th>
                    <th>TAC GIA</th>
                    <th>NHA XUAT BAN</th>
                    <th>GIA</th>
                    <th>THE LOAI</th>
                    <th>HANH DONG</th>
                  </tr>
                </thead>
                <tbody>
                    <?php
                    $query = mysqli_query($conn, "SELECT * FROM `SACH` ORDER BY `id` DESC");
                    while($rows = mysqli_fetch_array($query)) {
                    ?>

                    <tr>
                        <td>#<?php echo $rows['id'];?></td>
                        <td><?php echo $rows['masach'];?></td>
                        <td><?php echo $rows['tensach'];?></td>
                        <td><?php echo $rows['tacgia'];?></td>
                        <td><?php echo $rows['nhaxuatban'];?></td>
                        <td><?php echo $rows['gia'];?></td>
                        <td><?php echo $rows['theloai'];?></td>
                        <td><center><button type="button" onclick="detail('<?=$rows['id'];?>', '<?=$rows['masach'];?>', '<?=$rows['tensach'];?>', '<?=$rows['tacgia'];?>', '<?=$rows['nhaxuatban'];?>', '<?=$rows['gia'];?>', '<?=$rows['theloai'];?>')" class="btn btn-sm btn-success">DETAIL</button></center></td>
                    </tr>                    
                       
                    <?
                    } 
                    ?>
                </tbody>
              </table>
        </div>




    </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    // phần xử lý js
function detail(id, masach, tensach, tacgia, nhaxuatban, gia, theloai) {
    $('#data_id').html('<input name="id" type="hidden" value="'+ id +'">');
    $('input[name=masach]').val(masach);
    $('input[name=tensach]').val(tensach);
    $('input[name=tacgia]').val(tacgia);
    $('input[name=nhaxuatban]').val(nhaxuatban);
    $('input[name=gia]').val(gia);
    $('input[name=theloai]').removeAttr("selected");
    $('input[name=theloai]').val(theloai);
    $('input[name=theloai]').change();
}
</script>
</html>