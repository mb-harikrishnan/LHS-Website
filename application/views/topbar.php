

<!-- <div class="topbar">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="pull-left">
        <span class="info"><a href="#."> Have any question?</a></span>
        <span class="info"><i class="icon-phone2"></i>+91-9744693905|0484-2446939</span>
        <span class="info"><i class="icon-mail"></i>littleheartsschool97@gmail.com</span>
        </div>
        <ul class="social_top pull-right">
          <li><a href="#."><i class="fa fa-facebook"></i></a></li>
          <li><a href="#."><i class="icon-twitter4"></i></a></li>
          <li><a href="#."><i class="icon-google"></i></a></li>
        </ul>
      </div>
    </div>
  </div>
</div> -->

<!-- 
<div class="topbar">
  <div class="container">
    <div class="row">
      <div class="col-md-12">

        <div class="news-scroll">
          <marquee behavior="scroll" direction="left" scrollamount="5">

            <?php
            $sql = "SELECT c_news FROM school_news WHERE c_status='Y'";
            $query = $this->db->query($sql);
            $result = $query->result();

            foreach($result as $row)
            {
                echo $row->c_news . " &nbsp;&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;&nbsp; ";
            }
            ?>

          </marquee>
        </div>

      </div>
    </div>
  </div>
</div>


<style>
  .topbar{
    background:#ffb400;
    padding:10px 0;
}

.news-scroll{
    color:#fff;
    font-size:15px;
    font-weight:600;
}
</style> -->