(function(){
  function openMenu(){
    var ov = document.getElementById('taMenuOverlay');
    if(!ov) return;
    ov.style.display='block'; ov.removeAttribute('hidden');
  }
  function closeMenu(){
    var ov = document.getElementById('taMenuOverlay');
    if(!ov) return;
    ov.style.display='none'; ov.setAttribute('hidden','hidden');
  }
  document.addEventListener('click', function(e){
    if (e.target.closest('[data-ta-menu-open]')) { openMenu(); }
    if (e.target.closest('[data-ta-menu-close]')) { closeMenu(); }
    var ov = document.getElementById('taMenuOverlay');
    if (ov && e.target === ov) { closeMenu(); }
  });
})();
