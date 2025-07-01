<div style="width: 100%;margin-bottom: 20px; ">
  <form>
<input type="text" placeholder="Search" name="search" value="<?=(isset($_GET['search']))?$_GET['search']:''?>">
<select  name="contentProvider" id="contentProvider">
    <option value="" selected="">Filter by Content Provider</option>
    <option value="AXS"> AXS</option>
    <option value="Allvipp">Allvipp</option>
    <option value="Automoto%20TV%20-%20Gaming">Automoto TV - Gaming</option>
    <option value="Automoto%20TV%20-%20Gaming%20News">Automoto TV - Gaming News </option>
    <option value="Automoto%20TV%20-%20Movie%20Trailers">Automoto TV - Movie Trailers  </option>
    <option value="Bang%20Showbiz">      Bang Showbiz   </option>
    <option value="Bang%20Showbiz%20-%20Bang%20Bizarre">Bang Showbiz - Bang Bizarre </option>
    <option value="Bang%20Showbiz%20-%20Bang%20Extra">Bang Showbiz - Bang Extra </option>
    <option value="Bang%20Showbiz%20-%20Gaming">Bang Showbiz - Gaming</option>
    <option value="Bang%20Showbiz%20-%20Music">Bang Showbiz - Music   </option>
    <option value="Bang%20Showbiz%20-%20Tech"> Bang Showbiz - Tech  </option>
    <option value="Celeb%20Presto"> Celeb Presto </option>
    <option value="Chimney%20Swift">  Chimney Swift </option>
    <option value="Chris%20Smoove"> Chris Smoove </option>
    <option value="Cover%20Media"> Cover Media  </option>
    <option value="Cover%20Media%20-%20Gaming">Cover Media - Gaming</option>
    <option value="Cover%20Media%20-%20Shareable"> Cover Media - Shareable </option>
    <option value="Decider.com"> Decider.com </option>
    <option value="Dexerto">Dexerto </option>
    <option value="Digital%20Trends"> Digital Trends </option>
    <option value="Enthusiast%20Gaming%20-%20Arcade%20Cloud"> Enthusiast Gaming - Arcade Cloud </option>
    <option value="Enthusiast%20Gaming%20-%20Upcomer"> Enthusiast Gaming - Upcomer </option>
    <option value="Enthusiast%20Gaming%20-%20Wisecrack">Enthusiast Gaming - Wisecrack</option>
    <option value="FCCE">FCCE</option>
    <option value="FSC%20-%20Jam%20in%20the%20Van">FSC - Jam in the Van</option>
    <option value="FYI%20News%20-%20Celebrities">FYI News - Celebrities</option>
    <option value="FYI%20News%20-%20Entertainment">FYI News - Entertainment</option>
    <option value="FYI%20News%20-%20Technology">FYI News - Technology</option>
    <option value="Fuse">Fuse</option>
    <option value="GreatLobbyist"> GreatLobbyist</option>
    <option value="IVA%20-%20Gaming">IVA - Gaming</option>
    <option value="IVA%20-%20Movie%20Extras">IVA - Movie Extras</option>
    <option value="IVA%20-%20Movie%20Trailers">IVA - Movie Trailers</option>
    <option value="Jimmy%20Lloyd">Jimmy Lloyd</option>
    <option value="Jon%20Rettinger">Jon Rettinger</option>
    <option value="Knowmore%20Tech%20English">Knowmore Tech English</option>
    <option value="Level%20Up%20Media">Level Up Media</option>
    <option value="Level%20Up%20Media%20-%20PG">Level Up Media - PG</option>
    <option value="Maven%20-%20Sports%20Illustrated%20Swimsuit">Maven - Sports Illustrated Swimsuit</option>
    <option value="Money%20Talks%20News">Money Talks News</option>
    <option value="Natcom%20-%20Topsify">Natcom - Topsify</option>
    <option value="Network%20N%20-%20Gaming">Network N - Gaming</option>
    <option value="New%20York%20Post">New York Post</option>
    <option value="New%20York%20Post%20-%20Page%20Six">New York Post - Page Six</option>
    <option value="Newsy">Newsy</option>
    <option value="Nowthis%20-%20Entertainment">Nowthis - Entertainment</option>
    <option value="Nowthis%20-%20US">Nowthis - US</option>
    <option value="PA%20Media%20-%20Entertainment">PA Media - Entertainment</option>
    <option value="Planet%20Rock%20Profiles">Planet Rock Profiles</option>
    <option value="Playbill">Playbill</option>
    <option value="Professor%20Of%20Rock">Professor Of Rock</option>
    <option value="Radio.com%20-%20Music">Radio.com - Music</option>
    <option value="Real%20Music%20TV">Real Music TV</option>
    <option value="Savage%20Ventures%20-%20American%20Songwriter">Savage Ventures - American Songwriter</option>
    <option value="Shandy%20-%20Hollyscoop">Shandy - Hollyscoop</option>
    <option value="Slay">Slay</option>
    <option value="Us%20Weekly%20-%20Exclusive%20Celebrity%20Interviews">Us Weekly - Exclusive Celebrity views</option>
    <option value="Us%20Weekly%20-%20Latest%20News">Us Weekly - Latest News</option>
    <option value="What's%20Trending">What's Trending</option>
    <option value="Wibbitz">Wibbitz</option>
    <option value="Wibbitz%20-%20Entertainment">Wibbitz - Entertainment</option>
    <option value="Wibbitz%20-%20Technology">Wibbitz - Technology</option>
    <option value="Win.gg">Win.gg</option>
    <option value="Young%20Hollywood">Young Hollywood</option>
    <option value="ZMG%20-%20Amaze%20Lab">ZMG - Amaze Lab</option>
    <option value="ZMG%20-%20Buzz60">ZMG - Buzz60</option>
    <option value="ZMG%20-%20Hit%20Points">ZMG - Hit Points</option>
    <option value="ZMG%20-%20Veuer">ZMG - Veuer</option>
    <option value="Zoomin%20TV">Zoomin TV</option>
    <option value="iOne%20Digital%20-%20Bossip">IOne Digital - Bossip</option>
    <option value="iOne%20Digital%20-%20Cassius">IOne Digital - Cassius</option>
    <option value="iOne%20Digital%20-%20Hello%20Beautiful">IOne Digital - Hello Beautiful</option>
    <option value="iOne%20Digital%20-%20Madam%20Noire">IOne Digital - Madam Noire    </option>
    <option value="iOne%20Digital%20-%20News%20One">IOne Digital - News One    </option>
    <option value="unbranded%20-%20Entertainment">Unbranded - Entertainment    </option>
    <option value="unbranded%20-%20Gaming">Unbranded - Gaming    </option>
</select>
<select name="duration" id="duration">
    <option value="" selected="" >Filter by Duration</option>
    <option value="30">Under 30 Seconds</option>
    <option value="60">Under 60 Seconds</option>
    <option value="90">Under 90 Seconds</option>
    <option value="120">Under 120 Seconds</option>
    <option value="180">Under 180 Seconds</option>
</select>
<button type="submit" name="search_data">
  Submit
</button>
</form>
</div>


<?php

if(isset($_GET['contentProvider'])){
  $contentProvider=$_GET['contentProvider'];
}else{
  $contentProvider="";
}
if(isset($_GET['duration'])){
  $duration=$_GET['duration'];
}else{
  $duration="";
}
if(isset($_GET['search'])){
  $search=$_GET['search'];
}else{
  $search="";
}
//echo "https://mrss.videoelephant.com/mrss?contentProvider=".$contentProvider."&search=".$search."&duration".$duration;
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://mrss.videoelephant.com/mrss?contentProvider=".$contentProvider."&search=".$search."&duration".$duration,
  CURLOPT_USERPWD=>"charles@discovered.tv:EbibnIFk5sp9zhNe5vIppROoSlTy1K08",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    'Accept: application/json'
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  
   $Json = json_encode(simplexml_load_string($response,'SimpleXMLElement', LIBXML_NOCDATA));
   //echo "<pre>";
   //print_r($Json);
  $ar_data=json_decode($Json,true);
  $iten =$ar_data['channel']['item'];
 //print_r($ar_data);  
$i=0;
 foreach ($iten as $key => $value) {
  $i++; ?>

  <div style="width: 33.33%;float: left;">
    <video controls src="<?php echo $value['enclosure']['@attributes']['url']?>" style="width: 350px;"></video>
<p><?php echo $value['title']?></p>
  </div>
    <?php
  } 
  
}?>
<script type="text/javascript">
  document.getElementById("contentProvider").value = "<?=$contentProvider?>";
</script>

<script type="text/javascript">
  document.getElementById("duration").value = "<?=$duration?>";
</script>