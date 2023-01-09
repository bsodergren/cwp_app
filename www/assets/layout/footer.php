<p>&nbsp;</p>
</main>
<?php     if (MediaSettings::isTrue('__SHOW_DEBUG_PANEL__')) { ?>
<nav class="navbar  fixed-bottom  navbar-expand-md navbar-dark bg-dark shadow-sm p-2">
	<div class="container">
			<ul class="navbar-nav">


      <? 
      $errorArray = getErrorLogs();

      foreach($errorArray as $k =>$file){
        $file = basename($file);
        $key = str_replace(".","_",basename($file));
        echo '<li class="nav-item"><a class="nav-link" target="_blank" href="debug.php?log='.$key.'">'.$file.'</a></li>';
      }
      ?>
				</ul>
		</div>
</nav>
<?php } ?>

        <script src="<?php echo __URL_LAYOUT__;?>/js/jquery-3.4.1.min.js"></script>


        <script type="text/javascript">
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl, {
  boundary: document.body // or document.querySelector('#boundary')
}))

</script>



    </body>
</html>