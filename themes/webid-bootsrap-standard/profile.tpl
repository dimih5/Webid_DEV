<!-- IF B_AUCID -->
<div class="form-actions"> <a class="btn btn-primary" href="{SITEURL}item.php?id={AUCTION_ID}">{L_138}</a> </div>
<!-- ENDIF -->
<legend> {L_206}<br />
</legend>
<div class="row">
<div class="span4">
  <div class="hero-unit"> <b>{USER} ({SUM_FB})</b>{RATE_VAL}<br>
  </div>
  <!-- IF B_VIEW -->
  <!-- IF B_CONTACT -->
  <div class="well" style="padding:8px 0px;">
    <ul class="nav nav-list" style="margin-bottom:8px;">
      <li><a href="{SITEURL}email_request.php?user_id={USER_ID}&amp;username={USER}"><i class="icon-envelope"></i> {L_210}{USER}</a></li>
    </ul>
  </div>
<!-- ENDIF -->
  
  {L_209} <b>{REGSINCE}</b><br>
  {L_240} <b>{COUNTRY}</b><br>

</div>
<div class="span8">
   <h3>{L_219}</h3>
  <table class="table table-condensed">
    <tr align="center">
      <th width="10%">{L_167}</th>
      <th width="45%">{L_168}</th>
      <th width="15%">{L_169}</th>
      <th width="15%">{L_171a}</th>
    </tr>
    <!-- BEGIN auctionsa -->
    <tr  style="text-align:center;" class="{auctionsa.BGCOLOUR}">
      <td width="10%"><a href="{SITEURL}item.php?id={auctionsa.ID}"><img src="{auctionsa.PIC_URL}" width="{THUMBWIDTH}" border='0' alt="image"></a></td>
      <td width="45%"><a href="{SITEURL}item.php?id={auctionsa.ID}">{auctionsa.TITLE}</a>

      <td width="15%">{auctionsa.NUM_BIDS}</td>
      <td width="15%">{auctionsa.TIMELEFT}</td>
    </tr>
    <!-- END auctions -->
  </table>
  </td>
  </tr>
  </table>

  <h3>{L_220}</h3>
  <table class="table table-condensed">
    <tr align="center">
      <th width="10%">{L_167}</th>
      <th width="45%">{L_168}</th>
      <th width="15%">{L_169}</th>
      <th width="15%">{L_171a}</th>
    </tr>
    <!-- BEGIN auctions -->
    <tr  style="text-align:center;" class="{auctions.BGCOLOUR}">
      <td width="10%"><a href="{SITEURL}item.php?id={auctions.ID}"><img src="{auctions.PIC_URL}" width="{THUMBWIDTH}" border='0' alt="image"></a></td>
      <td width="45%"><a href="{SITEURL}item.php?id={auctions.ID}">{auctions.TITLE}</a>

      <td width="15%">{auctions.NUM_BIDS}</td>
      <td width="15%">{auctions.TIMELEFT}</td>
    </tr>
    <!-- END auctions -->
  </table>
  </td>
  </tr>
  </table>
</div>
<!-- ELSE -->
<div class="info"> {MSG}</div>
<!-- ENDIF -->
