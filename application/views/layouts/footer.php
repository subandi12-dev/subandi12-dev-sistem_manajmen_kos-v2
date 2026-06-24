    </div><!-- page-wrap -->
  </div><!-- main-content -->
</div><!-- app-layout -->
<script>
setTimeout(function(){
  document.querySelectorAll('.flash-msg').forEach(function(el){
    el.style.transition='opacity .4s';
    el.style.opacity='0';
    setTimeout(function(){el.remove();},400);
  });
},3500);
</script>
</body>
</html>
