(function(global){
  function today_jalali(){
    var d=new Date(), gy=d.getFullYear(), gm=d.getMonth()+1, gd=d.getDate();
    function gregorian_to_jalali(gy,gm,gd){
      var g_d_m=[0,31,((gy%4==0&&gy%100!=0)||gy%400==0)?29:28,31,30,31,30,31,31,30,31,30,31];
      var gy2=gy-1600, gm2=gm-1, gd2=gd-1;
      var g_day_no=365*gy2+((gy2+3)/4|0)-((gy2+99)/100|0)+((gy2+399)/400|0);
      for(var i=0;i<gm2;++i) g_day_no+=g_d_m[i+1];
      g_day_no+=gd2;
      var j_day_no=g_day_no-79, j_np=(j_day_no/12053|0);
      j_day_no%=12053;
      var jy=979+33*j_np+4*(j_day_no/1461|0);
      j_day_no%=1461;
      if(j_day_no>=366){ jy+=(j_day_no-366)/365|0; j_day_no=(j_day_no-366)%365; }
      var jm=(j_day_no<186)?1+(j_day_no/31|0):7+((j_day_no-186)/30|0);
      var jd=1+((j_day_no<186)?(j_day_no%31):((j_day_no-186)%30));
      return [jy,jm,jd];
    }
    return gregorian_to_jalali(gy,gm,gd);
  }
  function buildCalendar(el, input){
    var t=today_jalali(); var jy=t[0], jm=t[1], jd=t[2];
    function monthLength(y,m){ return (m<=6)?31:(m<=11?30:29); }
    function pad(n){return n<10?'0'+n:n;}
    function render(y,m,sel){
      el.innerHTML='';
      var head=document.createElement('div'); head.className='ta-cal-head';
      var prev=document.createElement('button'); prev.type='button'; prev.textContent='‹';
      var next=document.createElement('button'); next.type='button'; next.textContent='›';
      var title=document.createElement('div'); title.textContent= y+' / '+pad(m);
      head.appendChild(prev); head.appendChild(title); head.appendChild(next); el.appendChild(head);
      var grid=document.createElement('div'); grid.className='ta-cal-grid'; el.appendChild(grid);
      var days=monthLength(y,m);
      for(var i=1;i<=days;i++){
        var c=document.createElement('div'); c.className='ta-cal-cell'; c.textContent=pad(i);
        if(sel && sel[0]==y && sel[1]==m && sel[2]==i) c.classList.add('selected');
        (function(ii){
          c.addEventListener('click', function(){
            input.value=y+'/'+pad(m)+'/'+pad(ii);
            var olds=el.querySelectorAll('.ta-cal-cell.selected'); olds.forEach(function(n){n.classList.remove('selected');});
            c.classList.add('selected');
          });
        })(i);
        grid.appendChild(c);
      }
      prev.addEventListener('click', function(){ var nm=m-1, ny=y; if(nm<1){ nm=12; ny--; } render(ny,nm,sel); });
      next.addEventListener('click', function(){ var nm=m+1, ny=y; if(nm>12){ nm=1; ny++; } render(ny,nm,sel); });
    }
    render(jy,jm,[jy,jm,jd]);
  }
  global.TA_Jalali={buildCalendar:buildCalendar};
})(window);

document.addEventListener('DOMContentLoaded', function(){
  var cal=document.getElementById('ta-calendar');
  var input=document.getElementById('ta-jdate');
  if(cal && input && window.TA_Jalali){ window.TA_Jalali.buildCalendar(cal,input); }
});
