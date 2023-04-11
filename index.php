<?php
include_once "includes/config.php";

// showArray($_REQUEST, "post");
// exit;
// NOTE: USER LOGIN
if(isset($_REQUEST['login']) && $_REQUEST['login'] == true)
{
  // showArray($_REQUEST);
  $login = User::login($_REQUEST['loginName'], $_REQUEST['loginPassword']);
  // showArray($login, "login:");
  // if($login == 1) {
  //   header('Location: index.php');
  //   exit;
  // } else {
  //   return json_encode($login);
  // }
}
if (((int)USERID == 0))
{
	header('Location: login.php');
} else {
  $user = new User(USERID);
  // showArray($user, "User: ");
}
include("header.php");
?>
<div class="container-scroller">
  <?php include("sidebar.php");?>
  <!-- partial -->
  <div class="container-fluid page-body-wrapper">
    <!-- <div class="row header-row">
        <h3>Dashboard</h3>
    </div> -->
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="row">
          <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-9">
                    <div class="d-flex align-items-center align-self-start">
                      <h3 class="mb-0">$0.00</h3>
                      <!-- <p class="text-success ms-2 mb-0 font-weight-medium">+3.5%</p> -->
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="icon icon-box-success ">
                      <span class="mdi mdi-arrow-top-right icon-item"></span>
                    </div>
                  </div>
                </div>
                <h6 class="text-muted font-weight-normal">Container 1</h6>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-9">
                    <div class="d-flex align-items-center align-self-start">
                      <h3 class="mb-0">$0.00</h3>
                      <!-- <p class="text-success ms-2 mb-0 font-weight-medium">+11%</p> -->
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="icon icon-box-success">
                      <span class="mdi mdi-arrow-top-right icon-item"></span>
                    </div>
                  </div>
                </div>
                <h6 class="text-muted font-weight-normal">Container 2t</h6>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-9">
                    <div class="d-flex align-items-center align-self-start">
                      <h3 class="mb-0">$0.00</h3>
                      <!-- <p class="text-danger ms-2 mb-0 font-weight-medium">-2.4%</p> -->
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="icon icon-box-danger">
                      <span class="mdi mdi-arrow-bottom-left icon-item"></span>
                    </div>
                  </div>
                </div>
                <h6 class="text-muted font-weight-normal">Container 3</h6>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-9">
                    <div class="d-flex align-items-center align-self-start">
                      <h3 class="mb-0">$0.00</h3>
                      <!-- <p class="text-success ms-2 mb-0 font-weight-medium">+3.5%</p> -->
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="icon icon-box-success ">
                      <span class="mdi mdi-arrow-top-right icon-item"></span>
                    </div>
                  </div>
                </div>
                <h6 class="text-muted font-weight-normal">Container 4</h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- main-panel ends -->
  </div>
  <!-- page-body-wrapper ends -->
</div>
<!-- container-scroller -->
<?php include("footer.php")?>
<script>
  sizeContainerToWindow($(".page-body-wrapper"));
    $(document).ready(function() {
      closeGenericLoader();
    });
</script>