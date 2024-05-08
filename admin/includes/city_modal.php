<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Add New City</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="city_add.php">
                <div class="form-group">
                    <label for="name" class="col-sm-3 control-label">Name</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-sm-3 control-label">zip_code</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="zip" name="zip" required>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-primary btn-flat" name="add"><i class="fa fa-save"></i> Save</button>
              </form>
            </div>
        </div>
    </div>
</div>
<!-- Edit modal -->
<!-- Edit modal --><div class="modal fade" id="editModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><b>Edit City</b></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="city_edit.php" method="POST">
                    <div class="form-group">
                        <label for="edit_name" class="col-sm-3 control-label">Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="edit_name" name="edit_name" required>
                            <!-- Hidden input field for city ID -->
                            <input type="hidden" id="edit_id" name="edit_id">
                        </div>
                    </div>
                    <div class="form-group">
                    <label for="name" class="col-sm-3 control-label">zip_code</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="zip" name="zip" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success btn-flat" name="editcity"><i class="fa fa-check-square-o"></i> Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete modal -->
<div class="modal fade" id="deleteModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><b>Delete City</b></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong><span id="deleteCityName"></span></strong>?</p>
            
              </div>
            <div class="modal-footer">
              <form action="city_delete.php" method="POST">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Close</button>
            
                <input type="hidden" name="delete_id" id="delete_id">
                <button type="submit" class="btn btn-danger btn-flat" name="confirmDelete" id="confirmDelete"><i class="fa fa-trash"></i> Delete</button>
                </form>
              </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // $(document).ready(function() {
    //     // AJAX request for deleting city
    //     $('#confirmDelete').on('click', function() {
    //         var city_id = $(this).data('id');
    //         $.ajax({
    //             type: 'POST',
    //             url: 'city_delete.php', // The same page where you handle deletion
    //             data: { delete: true, id: city_id },
    //             success: function(response) {
    //                 $('#deleteModal').modal('hide');
    //                 // Reload the page or update the table after deletion
    //                 location.reload();
    //             },
    //             error: function(xhr, status, error) {
    //                 console.error(xhr.responseText);
    //             }
    //         });
    //     });
    // });
</script>

<?php
// Edit city
if(isset($_POST['edit'])) {
  $edit_name = $_POST['edit_name'];
  $city_id = $_POST['edit_id']; // Change 'id' to 'edit_id'

  $sql = "UPDATE city SET name='$edit_name' WHERE id=$city_id";

  if ($conn->query($sql) === TRUE) {
      echo "<script>alert('City updated successfully');</script>";
  } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

// // Delete city
// if(isset($_POST['delete'])) {
//   $city_id = $_POST['confirmDelete']; // Change 'city_id' to 'id'

//   $sql = "DELETE FROM city WHERE id=$city_id";

//   if ($conn->query($sql) === TRUE) {
//       echo "<script>alert('City deleted successfully');</script> <script>
//       $(document).ready(function() {
//           $('#confirmDelete').on('click', function() {
//               var city_id = $(this).data('id');
//               $.ajax({
//                   type: 'POST',
//                   url: 'city.php', // The same page where you handle deletion
//                   data: { delete: true, id: city_id },
//                   success: function(response) {
//                       $('#deleteModal').modal('hide');
//                       // Reload the page or update the table after deletion
//                       location.reload();
//                   },
//                   error: function(xhr, status, error) {
//                       console.error(xhr.responseText);
//                   }
//               });
//           });
//       });
//       </script>
//       ";
//   } else {
//       echo "Error: " . $sql . "<br>" . $conn->error;
//   }
// }

?>
