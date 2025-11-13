
<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Fine</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="assets\css\fine_form.css"> -->
    <link rel="icon" type="image/png" href="img/fine_mate_logo.png">
</head>
<style>
    .card {
    width: auto !important;
    }
    .btn-primary {
        margin-left: 0% !important;
    }
</style>
<body>
    <?php include 'header.php'; ?>
    <div class="container py-5">
        <div class="card mx-auto" style="max-width: 800px;">
            <div class="card-body">
                <h1 class="fine-title-1 text-center mb-4">Add Fine</h1>

                <form action="process_fine.php" method="POST">


                    <h3 class="text-primary mb-2 mt-3">Offender Details</h3>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Offender Name</label>
                            <input type="text" name="offender_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">National ID (NIC)</label>
                            <input type="text" name="offender_nic" class="form-control" placeholder="Enter NIC number"
                                maxlength="13" pattern="[0-9]{13}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">License No</label>
                            <input type="text" name="offender_license_no" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Vehicle No</label>
                            <input type="text" name="vehicle_no" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Offender Mobile</label>
                            <input type="text" name="offender_mobile" placeholder="07XXXXXXXXX" class="form-control"
                                maxlength="10" pattern="[0-9]{10}"
                                title="Please enter a 10-digit mobile number starting with 07">

                        </div>
                    </div>

                    <h3 class="text-primary mb-2 mt-3">Violation Details</h3>
                    <div class="mb-3">
                        <label class="form-label">Fine Type</label>
                        <select name="fine_type" class="form-select" required>
                            <option value="">-- Select Fine Type --</option>
                            <option>Speeding</option>
                            <option>No Helmet</option>
                            <option>Illegal Parking</option>
                            <option>Signal Violation</option>
                            <option>Drunk Driving</option>
                            <option>Other</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fine Amount (Rs)</label>
                        <input type="number" step="0.01" name="fine_amount" class="form-control" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Date</label>
                            <input type="date" name="fine_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Time</label>
                            <input type="time" name="fine_time" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <input type="text" name="fine_location" class="form-control"
                            placeholder="e.g., Galle Road, Colombo">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Weather Conditions</label>
                        <select name="weather" class="form-select">
                            <option value="">-- Select Condition --</option>
                            <option>Clear</option>
                            <option>Rainy</option>
                            <option>Foggy</option>
                            <option>Nighttime</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="3" class="form-control"
                            placeholder="Describe the violation..."></textarea>
                    </div>

                    <h3 class="text-primary mb-2 mt-3">Payment Details</h3>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Payment Status</label>
                            <select name="payment_status" class="form-select">
                                <option value="Pending">Pending</option>
                                <option value="Paid">Paid</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Due Date</label>
                            <input type="date" name="due_date" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" rows="2" class="form-control"
                            placeholder="Any officer notes..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary ">Submit Fine</button>
                </form>
            </div>
        </div>

    </div>
    <?php include 'footer.php'; ?>



    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function () {
            $('form').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'process_fine.php',
                    method: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            // Reset form if needed
                            //  $('form')[0].reset();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function (jqXHR) {
                        let message = "Something went wrong. Try again!";
                        if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                            message = jqXHR.responseJSON.message;
                        } else if (jqXHR.responseText) {
                            message = jqXHR.responseText;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: message
                        });
                    }

                });
            });
        });
    </script>



</body>

</html>