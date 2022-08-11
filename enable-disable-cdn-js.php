
<!DOCTYPE html>

<h3>Enable and Disable CDN</h3>

   <!--  <input type="text" id="client_id" placeholder="Enter Client ID"></br>
     <input type="text" id="client_secrect" placeholder="Enter Client Secret"></br>
   
   <button onclick="submitform()">Save</button></br></br>
 -->


<button onclick="enableCDN()">Enable CDN</button>

<button onclick="disableCDN()">Disable CDN</button>



</body>
</html>


<script type="text/javascript">
	
// const btn= document.getElementById("btn");

// btn.addEventListener('click', function(){
//   var name = document.getElementById("client_id").value;
//   alert("Name: "+ name);
// });


//Function to enable CDN 
function enableCDN() {

fetch('https://my.pressable.com/v1/sites/ADD-SITE-ID-HERE/cdn', {
  method: 'POST',
 headers: {'Content-Type': 'application/json',    "Authorization": 'Bearer ADD-ACCESSTOKEN-HERE',},  
  body: JSON.stringify({id: "200"})
  
}).then(response => {
  if(response.ok){
      return response.json();  
  }
    throw new Error('Request failed!');
}, networkError => {
  console.log(networkError.message);
}).then(jsonResponse => {
  console.log(jsonResponse);
})

}



//Function to disable CDN
function disableCDN() {

fetch('https://my.pressable.com/v1/sites/ADD-SITE-ID-HERE/cdn', {
  method: 'DELETE',
 headers: {'Content-Type': 'application/json',    "Authorization": 'Bearer ADD-ACCESSTOKEN-HERE',},  
  body: JSON.stringify({id: "200"})
  
}).then(response => {
  if(response.ok){
      return response.json();  
  }
    throw new Error('Request failed!');
}, networkError => {
  console.log(networkError.message);
}).then(jsonResponse => {
  console.log(jsonResponse);
})

}




// function submitform()
// {
//     if(document.myform.onsubmit &&
//     !document.myform.onsubmit())
//     {
//         return;
//     }
//  document.myform.submit();
// }

</script>
