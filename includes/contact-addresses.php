<?php
session_start();
$userid = $_SESSION['userid'];
require_once 'dbconfig.php';
if (isset($_GET['fetchAll'])) {
	// query to select all contacts added by this user
	$contQuery = $conn->prepare("SELECT * from contact_addresses where added_by = :userid");
	$contQuery->bindParam(":userid" , $userid);
	$contQuery->execute();
    if($contQuery->rowCount() == 0){
?>
<div class="text-center" style="padding: 100px 0;">
    <span class="alert alert-danger">Seems you have no contacts in your records</span>
</div>
<?php }else{
?>
<table class="table table-success table-hover" id="contactData">
    <thead>
        <tr>
            <th>First name</th>
            <th>Second name</th>
            <th>Phone number</th>
            <th>Email address</th>
            <th>Trash</th>
        </tr>
    </thead>
    <tbody>

        <?php
        while ($contQueryRow = $contQuery->fetch()) {

        ?>
        <tr>
            <td>
                <?php  echo $contQueryRow['fname']  ?>
            </td>
            <td>
                <?php  echo $contQueryRow['sname']  ?>
            </td>
            <td>
                <?php  echo $contQueryRow['phone']  ?>
            </td>
            <td>
                <?php  echo $contQueryRow['email']  ?>
            </td>
            <td>
                <button class="btn btn-sm btn-outline-warning deleteEmail" id="<?php  echo $contQueryRow['id']  ?>">
                    <span class="mdi mdi-delete"></span>
                </button>
            </td>
        </tr><?php
        }

             ?>
    </tbody>
</table>
<?php
    }
}

if (isset($_GET['saveRecord'])) {
	//save record
	$fname = trim(ucwords(strtolower($_POST['fname'])));
	$sname = trim(ucwords(strtolower($_POST['sname'])));
	(!empty($_POST['phone'])) ? $phone = "+254" . substr( trim($_POST['phone']) , -9) : $phone = "";
	$email = trim($_POST['email']);
	try{
		$insertQuery = $conn->prepare("INSERT into contact_addresses(fname,sname,phone,email,added_by) values(:fname, :sname, :phone, :email,:added_by)");
		$insertQuery->bindParam(":fname" , $fname);
		$insertQuery->bindParam(":sname" , $sname);
		$insertQuery->bindParam(":phone" , $phone);
		$insertQuery->bindParam(":email" , $email);
		$insertQuery->bindParam(":added_by" , $userid);
		if ($insertQuery->execute()) {
?>
<span class="alert alert-success">Saved successfully!</span>
<?php
		}else {
?>
<span class="alert alert-warning">Failed to add new contact!</span>
<?php
		}
	}
    catch(PDOException $e){
		echo "Error ".$e->getMessage();
	}
}
?>

<script type="text/javascript">
    $(function () {

        $('#contactData').dataTable();

        $('.deleteEmail').click(function (event) {
            /* Act on the event */
            var $emailId = $(this).attr('id');

            $.ajax({
                url: '../includes/manage-profile-inc.php',
                type: 'GET',
                data: { emailId: $emailId },
                async: false,
                dataType: 'text',
                success: function (data) {
                    $.get('../includes/contact-addresses.php?fetchAll=true', function (data) {
                        //optional stuff to do after success
                        $('.tableData').html(data);
                    });
                },
                error: function () {
                    alert("Could not be deleted");
                }
            })
        });
    })
</script>

