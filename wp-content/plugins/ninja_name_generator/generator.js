jQuery(document).ready( function() {

   //define a var for konami code input and a timer
   var input = "",
       timer

   jQuery(document).keyup(function(e) {

      //log user keyup in input
      input += e.which;

      //clear the timeout if one was started
      clearTimeout(timer);

      //Create a timeout between each logged input
      timer = setTimeout(function() { input = ""; }, 500);

      checkInput(input); 
   });

   //When send button is clicked we fetch inputs replace some characters and send
   jQuery("#send").click( function(e) {
      e.preventDefault(); 
      user_entry = jQuery("#buzzwords").val().replace(/[^a-zA-Z ]/g, "").replace(/\s+/g, ',').replace(',,','').trim();
      sendRequest(user_entry);
   })

})


//Send request to the api
function sendRequest (user_entry) {
      jQuery.ajax({
         type : "get",
         dataType : "json",
         url : name_generator.restURL+"ninjify/v1/generate/"+user_entry,
         beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nounce', name_generator.restNounce);
         },
         success: function(response) {
            if(response) {
               jQuery('#result').html(response.ninja_name);
            }
            else {
               jQuery(".alert").html("An error has occured while creating your ninja name try again or set new search terms").css('display','block');;
            }
         }
      }) 
}


//Check if user entry is the konami code
function checkInput (input) {
   var konami = "3838404037393739666513"//Konami Code

   //if it's a success we sendRequest with konami as a parameter
   if (input == konami) {
      jQuery("#buzzwords").val("konami");
      sendRequest("konami");
   }
       
}