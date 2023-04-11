<?php
include_once "includes/config.php";
if (((int)USERID == 0))
{
	header('Location: login.php');
} else {
    $user = new User(USERID);
    $portfolio = new Portfolio(USERID);
    if($portfolio->id == null)
    {
        $portfolio->createPortfolio($user->firstname . " " . $user->lastname, USERID);
    }
}
include("header.php");
?>
<div id="portfolioTracker" class="container-scroller">
    <?php include("sidebar.php");?>
    <div class="container-fluid page-body-wrapper">
        <div class="header-row">
            <h3>Finance Portfolio Tracker</h3>
            <a class="add-btn frankenstein-btn" href="<?=BASE_URL?>/portfolio_tracker_edit.php?new=true"><i class="fa fa-plus" aria-hidden="true"></i> Add Portfolio Item</a>
        </div>
        <div class="subheader-row">
            <h3><?=$portfolio->portfolioName?></h3>
        </div>
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row flex-row">
                    <div class="col-lg-4 col-sm-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Menu Item 1</h4>
                                <div class="bg-gray-dark d-flex d-md-block d-xl-flex flex-row py-3 px-4 px-md-3 px-xl-4 rounded mt-3">
                                    <!-- <div class="text-md-center text-xl-left">
                                        <h6 class="mb-1">Tranfer to Stripe</h6>
                                        <p class="text-muted mb-0">07 Jan 2019, 09:12AM</p>
                                    </div> -->
                                    <div class="align-self-center flex-grow text-right text-md-center text-xl-right py-md-2 py-xl-0">
                                        <h6 class="font-weight-bold mb-0">$593</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Menu Item 2</h4>
                                <div class="bg-gray-dark d-flex d-md-block d-xl-flex flex-row py-3 px-4 px-md-3 px-xl-4 rounded mt-3">
                                    <!-- <div class="text-md-center text-xl-left">
                                        <h6 class="mb-1">Tranfer to Stripe</h6>
                                        <p class="text-muted mb-0">07 Jan 2019, 09:12AM</p>
                                    </div> -->
                                    <div class="align-self-center flex-grow text-right text-md-center text-xl-right py-md-2 py-xl-0">
                                        <h6 class="font-weight-bold mb-0">$593</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Menu Item 3</h4>
                                <div class="bg-gray-dark d-flex d-md-block d-xl-flex flex-row py-3 px-4 px-md-3 px-xl-4 rounded mt-3">
                                    <!-- <div class="text-md-center text-xl-left">
                                        <h6 class="mb-1">Tranfer to Stripe</h6>
                                        <p class="text-muted mb-0">07 Jan 2019, 09:12AM</p>
                                    </div> -->
                                    <div class="align-self-center flex-grow text-right text-md-center text-xl-right py-md-2 py-xl-0">
                                        <h6 class="font-weight-bold mb-0">$593</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Section 2</h3>
                                <div class="bg-gray-dark d-flex d-md-block d-xl-flex flex-row py-3 px-4 px-md-3 px-xl-4 rounded mt-3">
                                    <div class="text-md-center text-xl-left">
                                        <h6 class="mb-1">Tranfer to Stripe</h6>
                                        <p class="text-muted mb-0">07 Jan 2019, 09:12AM</p>
                                    </div>
                                    <div class="align-self-center flex-grow text-right text-md-center text-xl-right py-md-2 py-xl-0">
                                        <h6 class="font-weight-bold mb-0">$593</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php")?>
<script>
    sizeContainerToWindow($(".page-body-wrapper"));
    $(document).ready(function() {
        closeGenericLoader();
    });
</script>