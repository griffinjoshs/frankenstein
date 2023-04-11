<?php
include_once "includes/config.php";
if (((int)USERID == 0))
{
	header('Location: login.php');
} else {
    $user = new User(USERID);
    $portfolio = new Portfolio(USERID);
    // showArray($portfolio, "portfolio");
    // showArray(($_POST["portfolioItemId"]), "portfolioItemId");
    if(isset($_POST) && isPost($_POST))
    {
        if(isset($_POST["portfolioId"]) && $_POST["portfolioId"] > 0)
        {
            $portfolioItem = new PortfolioItem($_POST["portfolioItemId"]);
        } else {
            $portfolioItem = new PortfolioItem();   
        }
        $data = array(
            "portfolioId"=>(int)$portfolio->id,
            "portfolioItemName"=>$_POST["portfolioItemName"],
            "portfolioItemType"=>$_POST["portfolioItemType"],
            "pricePurchased"=>$_POST["pricePurchased"],
            "datePurchased"=>$_POST["datePurchased"],
            "currentPrice"=>$_POST["currentPrice"]
        );
        $portfolioItem->addItem($data);
        // showArray($_POST, "_POST");

        // if($portfolioItem->id == NULL) {
        //     $portfolioItem->id = 0;
        // }


        // if($portfolioItem->validate())
        // {
        //     showArray($portfolioItem, "portfolioItem");
        //     $portfolioItem->save();
        // } 
    }
}
// exit;
include("header.php");
?>
<style>
    #datePurchased {
        color: white;
    }
</style>
<div id="portfolioTracker" class="container-scroller">
    <?php include("sidebar.php");?>
    <div class="container-fluid page-body-wrapper">
        <div class="header-row">
            <h3><?=(isset($_GET['new']) && $_GET['new'] == true ? "Add" : "Edit")?> Finance Portfolio Item</h3>
            <a class="add-btn frankenstein-btn" href="<?=BASE_URL?>/portfolio_tracker_summary.php"><i class="fa-solid fa-arrow-left"></i> Back</a>
        </div>
        <div class="main-panel">
            <div class="content-wrapper">
                <form id="portfolioTrackerForm" action="<?=BASE_URL?>/portfolio_tracker_edit.php" method="POST">
                    <input type="hidden" name="portfolioItemId" value="<?=$portfolioItem->id?>">
                    <div class="form-col-wrapper">
                        <div class="form-col-50">
                             <div class="form-outline mb-4">
                                <label class="form-label" for="portfolioItemName">Item Name</label>
                                <input type="text" id="portfolioItemName" class="form-control" name="portfolioItemName"/>
                            </div>
                            <div class="form-outline mb-4">
                                <label class="form-label" for="portfolioItemTicker">Ticker</label>
                                <input type="text" id="portfolioItemTicker" class="form-control" name='portfolioItemTicker'/>
                            </div>
                            <!-- TODO: change this to a database driven dropdown -->
                            <div class="form-outline mb-4">
                                <label class="form-label" for="portfolioItemType">Item Type</label>
                                <input type="text" id="portfolioItemType" class="form-control" name='portfolioItemType'/>
                            </div>
                        </div>
                        <!-- TODO: see if you can make this a number input and append a $ in front.  -->
                        <div class="form-col-50">
                             <div class="form-outline currency-input mb-4">
                                <label class="form-label" for="pricePurchased">Price Purchased</label>
                                <input type="number" id="pricePurchased" class="form-control" name='pricePurchased'/> 
                                <i>$</i>
                            </div>
                            <div class="form-outline mb-4">
                                <label class="form-label" for="datePurchased">Date Purchased</label>
                                <input type="date" id="datePurchased" class="form-control" name='datePurchased'/> 
                            </div>
                            <div class="form-outline currency-input mb-4">
                                <label class="form-label" for="currentPrice">Current Price</label>
                                <input type="number" id="currentPrice" class="form-control" name='currentPrice'/> 
                                <i>$</i>
                            </div>
                        </div>
                        <div class="form-col-100">
                            <button type="submit" class="frankenstein-btn">Submit</button>
                        </div>
                    </div>
                </form>
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