<div class="pageNav2"><?php if ($p > 1): ?><a class="leftFoot" href="content.php<?php print '?p='.$back; ?>"><img src="/modules/mod_ppp/images/previous.gif" width="114" height="23" alt="Previous Page" /></a><?php endif; if (($p < $limit) && ($p > 1)): ?><a class="leftFoot" href="content.php<?php print '?p='.$next; ?>"><img src="/modules/mod_ppp/images/next.gif" width="93" height="23" alt="Next Page" /></a><?php endif; echo $p > 1 ? '<a class="startOver" href="content.php?p=1"><img src="/modules/mod_ppp/images/start_over.gif" width="82" height="23" alt="Back to Start" /></a>': ''; if(($p > 1) && ($p != 2) && ($p != 15)): ?><a class="tOc" href="content.php?p=<?php echo $p < 15 ? '2' : '15' ?>"><img src="/modules/mod_ppp/images/contents.gif" width="82" height="23" alt="Table of Contents" /></a><?php endif; ?></div></body>
</html>