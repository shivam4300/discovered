<style>
.dis_store_reqWrap {
    display: flex;
    justify-content: center;
    align-items: center;
    height: calc(100vh - 194px);
}
.dis_store_reqinner {
    background-color: rgb(255 255 255);
    padding: 50px 40px;
    max-width: 650px;
}
.dis_sreq_ttl {
    font-size: 22px;
    color: #3f3f59;
    font-weight: 800;
    margin: 0;
}
.dis_sreq_des {
    margin: 20px 0 25px;
}
</style>
<div class="dis_store_reqWrap muli_font text-center">
    <div class="dis_store_reqinner">
        <div class="dis_sinfo_box">
            <h2 class="dis_sreq_ttl">Discovered Store</h2>
            <p class="dis_sreq_des">With the help of Discovered Marketplace , you <br>can build your own store where you can sale your product. </p>
                                        
            <div class="dis_sreq_btn">
            <?php  $mess =  $store_status == 0 ? 'Request for store': ( ( $store_status == 1) ? 'Requested for store' :  ( ( $store_status == 3)? 'Admin blocked Your Store' : ''  )  ) ; ?>
                <a href="javascript:;" class="dis_btn <?= $store_status == 0 ? 'requestForStore' : ''; ?> "><?= $mess; ?></a>
            </div>
        </div>
    </div>
</div>