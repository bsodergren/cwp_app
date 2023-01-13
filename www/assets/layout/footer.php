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


document.querySelectorAll("#job_number").forEach(function(node){
	node.ondblclick=function(){
		var val=this.innerHTML;
    let n = this.attributes[1].nodeValue; //[1].name;

//console.log(n);

  var hidden=document.createElement("input");
  hidden.value="update_job";
  hidden.name="update_job";
  hidden.type = "hidden";

  var input=document.createElement("input");
		input.value=val;
    input.name='job_number';
//    input.
    //input.name=

    input.onchange=function(){
      //let f = document.getElementById("form");

      let elements = document.querySelectorAll("#process");

      elements.forEach(e => {
      //console.log(element);
        e.remove();

     // element.style.backgroundColor = "yellow";
})
      this.form.submit();
    }

		input.onblur=function(){
			var val=this.value;
			this.parentNode.innerHTML=val;
		}
		this.innerHTML="";
    this.appendChild(hidden);
		this.appendChild(input);
		input.focus();
	}
});



const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl, {
  boundary: document.body // or document.querySelector('#boundary')
}))

</script>



    </body>
</html>