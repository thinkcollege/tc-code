<p>Please rate this item.</p>
<div class="floatleft stardiv">
<?php $placeholder = $this->model->getStarData(); ?>
<div id="rating_<?php $rating = $placeholder[0]->rating;
$pageids = JRequest::getVar( 'cid', 0, '', 'array' );
		$pageid = $pageids[0];
echo $pageid; 

 ?>">
<span class="star_1"><img src="/components/com_ttadb/views/item/images/star_blank.png" alt="" <?php if($rating > 0) { echo"class='hover'"; } ?> /></span>
<span class="star_2"><img src="/components/com_ttadb/views/item/images/star_blank.png" alt="" <?php if($rating > 1.5) { echo"class='hover'"; } ?> /></span>
<span class="star_3"><img src="/components/com_ttadb/views/item/images/star_blank.png" alt="" <?php if($rating > 2.5) { echo"class='hover'"; } ?> /></span>
<span class="star_4"><img src="/components/com_ttadb/views/item/images/star_blank.png" alt="" <?php if($rating > 3.5) { echo"class='hover'"; } ?> /></span>
<span class="star_5"><img src="/components/com_ttadb/views/item/images/star_blank.png" alt="" <?php if($rating > 4.5) { echo"class='hover'"; } ?> /></span>
</div>
</div>
<div class="star_rating stardiv">
(Rated <strong><?php  echo $rating; ?></strong> Stars)
</div>

<div class="clearleft">&nbsp;</div>