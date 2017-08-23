function hideIntro(){
   var w = document.getElementById('intro');
   w.innerHTML = "";
}
function toggleSidebar(){
   var x = document.getElementById("sidebar");
   x.classList.toggle("visible");
   var y = document.getElementById("main");
   y.classList.toggle("left");
   
}

function checkPass(){
  var x = document.getElementById("password");
  var y = document.getElementById("reenter");
  var z = document.getElementById("passworderror");
  



  if(x.value == y.value){
     z.innerHTML = "Same Password";
  }
  else{
     z.innerHTML = "NOT THE SAME";
  }
  if(y.value=="") z.innerHTML = "";
}
   

