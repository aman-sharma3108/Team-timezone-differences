"use strict"


function init(){
    if (document.getElementById("new_sale") != null){
        var MaxInputs = 20;
        var InputsWrapper = $("#input_wrapper");
        var AddButton = document.getElementById("AddMoreFileBox");
        var FieldCount=1;
        var x = InputsWrapper.length;
        
        
        AddButton.onclick = function (){
            if(x < MaxInputs)
            {
                FieldCount++;
                $(InputsWrapper).append('<p class="row"><label for="productname">Product: </label><input type="text" placeholder="Product ID" class="products" name="products[]"> x <input type="text" placeholder="Quantity" class="quantities" name="quantities[]" maxlength="4" size="4"/> = <input type="text" placeholder="Subtotal" class="subtotal" onchange="calc()" name="subtotal[]" size="6"/> <button type="button" class="removeclass">x</button></p>');
                //$(InputsWrapper).append('<p class="row"><label for="productname">Product: </label><input type="text" placeholder="Product ID" id="product'+FieldCount+'" name="product'+FieldCount+'"> x <input type="text" placeholder="Quantity" id="quantity'+x+'" name="quantity'+x+'" maxlength="4" size="4"/> = <input type="text" readonly="readonly" placeholder="Subtotal" id="subtotal'+x+'" name="subtotal'+x+'" size="6"/> <button type="button" class="removeclass">x</button></p>');
                x++;
            }
        return false;
        };

        
        $("body").on("click",".removeclass", function(e){
            if(x > 1){
                $(this).parent('p').remove();
                x--;
            }
            calc();
        return false;
        });

        var aestTime = new Date().toLocaleString("en-US", {timeZone: "Australia/Melbourne"});
        aestTime = new Date(aestTime);
        document.getElementById('time').value = aestTime.getHours() + ":" +aestTime.getMinutes();
        var year = aestTime.getFullYear();
        var month = aestTime.getMonth() + 1;
        var day = aestTime.getDate();
        if (month < 10){
          month = "0" + month;
        }
        if (day < 10){
          day = "0" + day;
        }
        document.getElementById('dos').value = year + "-" +month + "-" +day;
        
    };

    var dropdowns = document.getElementById("dropbtn");
    dropdowns.onclick = function (){
      document.getElementById("myDropdown").classList.toggle("show");
    };
}
  
  // Close the dropdown menu if the user clicks outside of it
 

function calc(){
    var InputsWrapper = $("#input_wrapper");
    var x = InputsWrapper.length;
    var total = 0;
    
    $('.subtotal').each(function(){
        total = total+parseFloat($(this).val());
        
    });
    document.getElementById("tprice").value=total;
}

window.addEventListener("click", function(event) {
  if (!event.target.matches('.dropbtn')) {
    var myDropdown = document.getElementById("myDropdown");
      if (myDropdown.classList.contains('show')) {
        myDropdown.classList.remove('show');
      }
    }
});
window.onload = init;
